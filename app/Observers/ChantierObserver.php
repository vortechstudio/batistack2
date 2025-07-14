<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;
use Illuminate\Support\Facades\Auth;

final class ChantierObserver
{
    public function created(Chantiers|ChantierDepense $chantier): void
    {
        $chantier->logs()->create([
            'libelle' => 'Création',
            'user_id' => Auth::user()->id,
        ]);
        $chantier->chantiers->mettreAJourBudgets();
    }

    public function saved(Devis|Commande|Facture|ChantierDepense $model): void
    {
        $model->chantiers->mettreAJourBudgets();
    }
}
