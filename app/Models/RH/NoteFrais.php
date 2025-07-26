<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\StatusNoteFrais;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class NoteFrais extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'note_frais';

    protected $guarded = [];

    /**
     * Relations
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(NoteFraisDetail::class);
    }

    /**
     * Accesseurs
     */
    public function getNumeroCompletAttribute(): string
    {
        return 'NF-'.mb_str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getMontantTotalCalculeAttribute(): float
    {
        return $this->details->sum('montant_ttc');
    }

    public function getNombreJustificatifsAttribute(): int
    {
        return $this->details->whereNotNull('justificatif_path')->count();
    }

    public function getEstValidableAttribute(): bool
    {
        return $this->status === StatusNoteFrais::SOUMISE &&
               $this->details->count() > 0;
    }

    public function getEstModifiableAttribute(): bool
    {
        return in_array($this->status, [StatusNoteFrais::BROUILLON, StatusNoteFrais::REFUSEE]);
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('status', StatusNoteFrais::SOUMISE);
    }

    public function scopeValidees($query)
    {
        return $query->where('status', StatusNoteFrais::VALIDEE);
    }

    public function scopePourEmploye($query, $employeId)
    {
        return $query->where('employe_id', $employeId);
    }

    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_debut_periode', [$dateDebut, $dateFin])
            ->orWhereBetween('date_fin_periode', [$dateDebut, $dateFin]);
    }

    /**
     * Méthodes métier
     */
    public function soumettre(): bool
    {
        if ($this->status !== StatusNoteFrais::BROUILLON) {
            return false;
        }

        $this->update([
            'status' => StatusNoteFrais::SOUMISE,
            'date_soumission' => now(),
            'montant_total' => $this->montant_total_calcule,
        ]);

        return true;
    }

    public function valider(User $validateur, ?string $commentaire = null): bool
    {
        if ($this->status !== StatusNoteFrais::SOUMISE) {
            return false;
        }

        $this->update([
            'status' => StatusNoteFrais::VALIDEE,
            'validateur_id' => $validateur->id,
            'date_validation' => now(),
            'commentaire_validateur' => $commentaire,
            'montant_valide' => $this->montant_total,
        ]);

        return true;
    }

    public function refuser(User $validateur, string $commentaire): bool
    {
        if ($this->status !== StatusNoteFrais::SOUMISE) {
            return false;
        }

        $this->update([
            'status' => StatusNoteFrais::REFUSEE,
            'validateur_id' => $validateur->id,
            'date_validation' => now(),
            'commentaire_validateur' => $commentaire,
        ]);

        return true;
    }

    public function marquerPayee(string $referencePaiement): bool
    {
        if ($this->status !== StatusNoteFrais::VALIDEE) {
            return false;
        }

        $this->update([
            'status' => StatusNoteFrais::PAYEE,
            'date_paiement' => now(),
            'reference_paiement' => $referencePaiement,
        ]);

        return true;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($noteFrais) {
            if (empty($noteFrais->numero)) {
                $noteFrais->numero = 'NF-'.date('Y').'-'.mb_str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        self::deleting(function ($noteFrais) {
            // Supprimer les détails associés
            $noteFrais->details()->delete();
        });
    }

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'date_debut_periode' => 'date',
            'date_fin_periode' => 'date',
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
            'date_paiement' => 'datetime',
            'montant_total' => 'decimal:2',
            'montant_valide' => 'decimal:2',
            'status' => StatusNoteFrais::class,
            'metadata' => 'array',
        ];
    }
}
