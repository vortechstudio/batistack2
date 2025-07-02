<?php

namespace App\Observers;

use App\Models\Chantiers\Chantiers;

class ChantierObserver
{
    public function created(Chantiers $chantier): void
    {
        $chantier->logs()->create([
            'libelle' => "Création",
            'user_id' => auth()->user()->id,
        ]);
    }
}
