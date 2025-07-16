<?php

namespace App\Livewire\Humans\Components\Widgets;

use App\Models\Commerce\Facture;
use App\Models\RH\Employe;
use App\Models\RH\Paie\FichePaie;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class CountAllSalaries extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Salariés', Employe::count()),
            Stat::make('Salariés Actif', Employe::where('status', 'actif')->count()),
            Stat::make('Salariés Inactif', Employe::where('status', 'inactif')->count()),
            Stat::make('Masse Salariales', $this->calcMasseSalariale()['value'])
                ->description('Soit '.$this->calcMasseSalariale()['percent'].'% absorbé par les salariés')
                ->color(function() {
                    if($this->calcMasseSalariale()['percent'] <= 25) {
                        return 'success';
                    } elseif($this->calcMasseSalariale()['percent'] > 25 && $this->calcMasseSalariale()['percent'] <= 40) {
                        return 'warning';
                    } else {
                        return 'danger';
                    }
                }),
        ];
    }

    private function calcMasseSalariale()
    {
        $ca = Facture::whereDate('date_facture', '>=', now()->subYear())->sum('amount_ht');
        $brut = FichePaie::whereDate('periode', '>=', now()->subYear())->sum('salaire_brut');
        $cotisation = FichePaie::whereDate('periode', '>=', now()->subYear())->sum('total_cotisation');

        $calc = $brut+$cotisation;
        return [
            "value" => Number::currency($calc, 'EUR', 'fr'),
            "percent" => $ca !== 0 ? ($calc / $ca) * 100 : 0,
        ];
    }
}
