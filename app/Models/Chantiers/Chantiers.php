<?php

declare(strict_types=1);

namespace App\Models\Chantiers;

use App\Enums\Chantiers\StatusChantier;
use App\Models\Commerce\Avoir;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Chantiers extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tiers(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(ChantierAddress::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(ChantierIntervention::class);
    }

    public function depenses(): HasMany
    {
        return $this->hasMany(ChantierDepense::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ChantierTask::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ChantierLog::class);
    }

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    public function avoirs(): HasMany
    {
        return $this->hasMany(Avoir::class);
    }

    public function ressources(): HasMany
    {
        return $this->hasMany(ChantierRessources::class);
    }

    public function getAvancements(): array
    {
        $total_task = $this->tasks->count();
        $finish_task = $this->tasks()->where('status', '!=', 'todo')->count();
        $percent = $finish_task !== 0 ? $total_task / $finish_task * 100 : 0;

        return [
            'percent' => $percent,
            'color' => $percent <= 33 ? 'red' : ($percent > 34 && $percent <= 66 ? 'amber' : 'green'),
        ];
    }

    public function calculerBudgetEstime(): float
    {
        // Budget estimé = somme des montants des devis et commandes
        $totalDevis = $this->devis()->where('status', 'accepted')->sum('amount_ht');
        $totalCommandes = $this->commandes()->sum('amount_ht');

        return (float) max($totalDevis, $totalCommandes);
    }

    public function calculerBudgetReel(): float
    {
        // Budget réel = somme des montants des factures + dépenses
        $totalFactures = $this->factures()->sum('amount_ht');
        $totalDepenses = $this->depenses()->sum('montant');

        return (float) $totalFactures + $totalDepenses;
    }

    public function mettreAJourBudgets(): void
    {
        $this->update([
            'budget_estime' => $this->calculerBudgetEstime(),
            'budget_reel' => $this->calculerBudgetReel(),
        ]);
    }

    public function getEcartBudgetPercentAttribute(): ?float
    {
        if ($this->budget_estime === 0) {
            return null; // Avoid division by zero
        }

        return round((($this->budget_reel - $this->budget_estime) / $this->budget_estime) * 100, 2);
    }

    public function getMargeChantierAttribute(): float
    {
        return $this->calculerBudgetEstime() - $this->calculerBudgetReel();
    }

    public function getMainOeuvreAttribute(): float
    {
        return $this->ressources()->sum('amount_fee');
    }

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin_prevu' => 'date',
            'date_fin_reel' => 'date',
            'status' => StatusChantier::class,
        ];
    }
}
