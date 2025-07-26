<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Enums\RH\ModePaiementFrais;
use App\Enums\RH\TypeFrais;
use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class NoteFraisDetail extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'note_frais_details';

    protected $guarded = [];

    /**
     * Relations
     */
    public function noteFrais(): BelongsTo
    {
        return $this->belongsTo(NoteFrais::class);
    }

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantiers::class)->nullable();
    }

    /**
     * Accesseurs
     */
    public function getMontantTtcCalculeAttribute(): float
    {
        if ($this->montant_ht && $this->taux_tva) {
            return $this->montant_ht + ($this->montant_ht * $this->taux_tva / 100);
        }

        return $this->montant_ttc ?? 0;
    }

    public function getMontantTvaCalculeAttribute(): float
    {
        if ($this->montant_ht && $this->taux_tva) {
            return $this->montant_ht * $this->taux_tva / 100;
        }

        return $this->montant_tva ?? 0;
    }

    public function getAJustificatifAttribute(): bool
    {
        return ! empty($this->justificatif_path);
    }

    public function getDistanceAttribute(): ?string
    {
        if ($this->lieu_depart && $this->lieu_arrivee) {
            return $this->lieu_depart.' → '.$this->lieu_arrivee;
        }

        return null;
    }

    public function getLibelleCompletAttribute(): string
    {
        $libelle = $this->libelle;

        if ($this->type_frais === TypeFrais::TRANSPORT && $this->distance) {
            $libelle .= ' ('.$this->distance.')';
        }

        if ($this->kilometrage) {
            $libelle .= ' - '.$this->kilometrage.' km';
        }

        return $libelle;
    }

    /**
     * Scopes
     */
    public function scopeParType($query, TypeFrais $type)
    {
        return $query->where('type_frais', $type);
    }

    public function scopeRemboursables($query)
    {
        return $query->where('remboursable', true);
    }

    public function scopeAvecJustificatif($query)
    {
        return $query->whereNotNull('justificatif_path');
    }

    public function scopeSansJustificatif($query)
    {
        return $query->whereNull('justificatif_path');
    }

    public function scopePourChantier($query, $chantierId)
    {
        return $query->where('chantier_id', $chantierId);
    }

    public function scopePourPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_frais', [$dateDebut, $dateFin]);
    }

    /**
     * Méthodes métier
     */
    public function calculerMontants(): void
    {
        if ($this->montant_ht && $this->taux_tva) {
            $this->montant_tva = $this->montant_ht * $this->taux_tva / 100;
            $this->montant_ttc = $this->montant_ht + $this->montant_tva;
        } elseif ($this->montant_ttc && $this->taux_tva) {
            $this->montant_ht = $this->montant_ttc / (1 + $this->taux_tva / 100);
            $this->montant_tva = $this->montant_ttc - $this->montant_ht;
        }
    }

    public function attacherJustificatif($file): void
    {
        $media = $this->addMediaFromRequest('justificatif')
            ->toMediaCollection('justificatifs');

        $this->update(['justificatif_path' => $media->getPath()]);
    }

    public function supprimerJustificatif(): void
    {
        $this->clearMediaCollection('justificatifs');
        $this->update(['justificatif_path' => null]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        self::saving(function ($detail) {
            // Calculer automatiquement les montants si nécessaire
            $detail->calculerMontants();
        });

        self::saved(function ($detail) {
            // Mettre à jour le montant total de la note de frais
            if ($detail->noteFrais) {
                $detail->noteFrais->update([
                    'montant_total' => $detail->noteFrais->montant_total_calcule,
                ]);
            }
        });

        self::deleted(function ($detail) {
            // Mettre à jour le montant total de la note de frais
            if ($detail->noteFrais) {
                $detail->noteFrais->update([
                    'montant_total' => $detail->noteFrais->montant_total_calcule,
                ]);
            }
        });
    }

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'date_frais' => 'date',
            'montant_ht' => 'decimal:2',
            'montant_tva' => 'decimal:2',
            'montant_ttc' => 'decimal:2',
            'taux_tva' => 'decimal:2',
            'kilometrage' => 'decimal:2',
            'remboursable' => 'boolean',
            'type_frais' => TypeFrais::class,
            'mode_paiement' => ModePaiementFrais::class,
            'metadata' => 'array',
        ];
    }
}
