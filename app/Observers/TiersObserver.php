<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tiers\Tiers;

final class TiersObserver
{
    public function created(Tiers $tiers): void
    {
        $tiers->logs()->create([
            'title' => 'Tiers '.$tiers->name.' crée',
            'user_id' => auth()->user()->id,
            'tier_id' => $tiers->id,
        ]);
    }

    public function updated(Tiers $tiers): void
    {
        $tiers->logs()->create([
            'title' => 'Tiers '.$tiers->name.' mis à jours',
            'user_id' => auth()->user()->id,
            'tier_id' => $tiers->id,
        ]);
    }
}
