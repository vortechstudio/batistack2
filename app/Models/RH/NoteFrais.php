<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\StatusNoteFrais;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class NoteFrais extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

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

    public function paiement(): HasOne
    {
        return $this->hasOne(NoteFraisPaiement::class);
    }

    /**
     * Accesseurs
     */
    public function getNumeroCompletAttribute(): string
    {
        return $this->numero ?? 'NF-'.mb_str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getMontantTotalCalculeAttribute(): float
    {
        return $this->details->sum('montant_ttc');
    }

    public function getMontantTotalHtAttribute(): float
    {
        return $this->details->sum('montant_ht');
    }

    public function getMontantTotalTvaAttribute(): float
    {
        return $this->details->sum('montant_tva');
    }

    public function getNombreJustificatifsAttribute(): int
    {
        return $this->details->whereNotNull('justificatif_path')->count();
    }

    public function getMontantPaiementAttribute()
    {
        return $this->paiement?->sum('montant');
    }

    public function getEstValidableAttribute(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);

        return $statut === StatusNoteFrais::SOUMISE &&
               $this->details->count() > 0;
    }

    public function getEstModifiableAttribute(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);

        return in_array($statut, [StatusNoteFrais::BROUILLON, StatusNoteFrais::REFUSEE]);
    }

    public function getEstPayableAttribute(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);

        return $statut === StatusNoteFrais::VALIDEE;
    }

    public function getEstRefuseAttribute(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);

        return $statut === StatusNoteFrais::REFUSEE;
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', StatusNoteFrais::SOUMISE);
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', StatusNoteFrais::VALIDEE);
    }

    public function scopePourEmploye($query, $employeId)
    {
        return $query->where('employe_id', $employeId);
    }

    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_debut', [$dateDebut, $dateFin])
            ->orWhereBetween('date_fin', [$dateDebut, $dateFin]);
    }

    /**
     * Méthodes métier
     */
    public function soumettre(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);
        if ($statut !== StatusNoteFrais::BROUILLON) {
            return false;
        }

        $this->update([
            'statut' => StatusNoteFrais::SOUMISE,
            'date_soumission' => now(),
            'montant_total' => $this->montant_total_calcule,
        ]);

        return true;
    }

    public function valider($validateur, ?string $commentaire = null): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);
        if ($statut !== StatusNoteFrais::SOUMISE) {
            return false;
        }

        $validateurId = $validateur instanceof User ? $validateur->id : $validateur;

        $this->update([
            'statut' => StatusNoteFrais::VALIDEE,
            'validateur_id' => $validateurId,
            'date_validation' => now(),
            'commentaire_validateur' => $commentaire,
            'montant_valide' => $this->montant_total,
        ]);

        return true;
    }

    public function refuser($validateur, string $motif): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);
        if ($statut !== StatusNoteFrais::SOUMISE) {
            return false;
        }

        $validateurId = $validateur instanceof User ? $validateur->id : $validateur;

        $this->update([
            'statut' => StatusNoteFrais::REFUSEE,
            'validateur_id' => $validateurId,
            'date_validation' => now(),
            'motif_refus' => $motif,
        ]);

        return true;
    }

    public function marquerPayee(string $referencePaiement): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);
        if ($statut !== StatusNoteFrais::VALIDEE) {
            return false;
        }

        $this->update([
            'statut' => StatusNoteFrais::PAYEE,
            'date_paiement' => now(),
            'reference_paiement' => $referencePaiement,
        ]);

        return true;
    }

    public function payer(): bool
    {
        $statut = $this->statut instanceof StatusNoteFrais
            ? $this->statut
            : StatusNoteFrais::from($this->statut ?? StatusNoteFrais::BROUILLON->value);
        if ($statut !== StatusNoteFrais::VALIDEE) {
            return false;
        }

        $this->update([
            'statut' => StatusNoteFrais::PAYEE,
            'date_paiement' => now(),
        ]);

        return true;
    }

    public function calculerMontants(): void
    {
        $this->load('details'); // Rafraîchir la relation
        $montantTotal = $this->details->sum('montant_ttc');

        $this->update([
            'montant_total' => $montantTotal,
        ]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($noteFrais) {
            if (empty($noteFrais->numero)) {
                // Générer un numéro unique avec retry en cas de conflit
                $year = date('Y');
                $attempts = 0;
                $maxAttempts = 10;

                do {
                    $attempts++;

                    // Utiliser un timestamp microseconde + random pour plus d'unicité
                    $microtime = (int) (microtime(true) * 1000000);
                    $random = mt_rand(1000, 9999);
                    $sequence = mb_substr((string) ($microtime + $random), -4);

                    $numero = 'NF-'.$year.'-'.$sequence;

                    // Vérifier si le numéro existe déjà
                    $exists = static::where('numero', $numero)->exists();

                    if (! $exists) {
                        $noteFrais->numero = $numero;
                        break;
                    }

                    // Si on arrive ici, il y a eu un conflit, on réessaie
                    usleep(1000); // Attendre 1ms avant de réessayer

                } while ($attempts < $maxAttempts);

                // Si on n'a pas réussi après plusieurs tentatives, utiliser un UUID tronqué
                if (empty($noteFrais->numero)) {
                    $uuid = str_replace('-', '', (string) \Illuminate\Support\Str::uuid());
                    $noteFrais->numero = 'NF-'.$year.'-'.mb_substr($uuid, 0, 4);
                }
            }
        });

        self::deleting(function ($noteFrais) {
            // Supprimer les détails associés
            $noteFrais->details()->delete();
            $noteFrais->paiement()->delete();
        });
    }

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
            'date_paiement' => 'datetime',
            'montant_total' => 'float',
            'montant_valide' => 'float',
            'statut' => StatusNoteFrais::class,
            'metadata' => 'array',
        ];
    }
}
