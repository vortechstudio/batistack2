<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Commande;
use App\Models\Commerce\Devis;
use App\Models\Commerce\Facture;

final class ChantierObserver
{
    public function created(Chantiers $chantier): void
    {
        $chantier->logs()->create([
            'libelle' => 'Création',
            'user_id' => auth()->user()->id,
        ]);
        $model->chantiers->mettreAJourBudgets();
    }

    public function saved(Devis|Commande|Facture|ChantierDepense $model): void
    {
        $model->chantiers->mettreAJourBudgets();
    }
}
