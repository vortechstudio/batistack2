<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Chantiers\Chantiers;

final class ChantierObserver
{
    public function created(Chantiers $chantier): void
    {
        $chantier->logs()->create([
            'libelle' => 'Création',
            'user_id' => auth()->user()->id,
        ]);
    }
}
