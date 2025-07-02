<?php

namespace App\Livewire\Chantiers\Widgets;

use App\Enums\Chantiers\StatusChantierTask;
use App\Helpers\Helpers;
use App\Models\Chantiers\ChantierDepense;
use App\Models\Chantiers\Chantiers;
use App\Models\Chantiers\ChantierTask;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class NbChantiersStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nombre Total de chantiers', Chantiers::count())
            ->icon('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIGNsYXNzPSJsdWNpZGUgbHVjaWRlLWJyaWNrLXdhbGwtaWNvbiBsdWNpZGUtYnJpY2std2FsbCI+PHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiB4PSIzIiB5PSIzIiByeD0iMiIvPjxwYXRoIGQ9Ik0xMiA5djYiLz48cGF0aCBkPSJNMTYgMTV2NiIvPjxwYXRoIGQ9Ik0xNiAzdjYiLz48cGF0aCBkPSJNMyAxNWgxOCIvPjxwYXRoIGQ9Ik0zIDloMTgiLz48cGF0aCBkPSJNOCAxNXY2Ii8+PHBhdGggZD0iTTggM3Y2Ii8+PC9zdmc+'),

            Stat::make('Nombre total de tache active', ChantierTask::where('status', '!=', StatusChantierTask::Todo)->get()->count())
            ->icon('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIGNsYXNzPSJsdWNpZGUgbHVjaWRlLWxpc3QtdG9kby1pY29uIGx1Y2lkZS1saXN0LXRvZG8iPjxyZWN0IHg9IjMiIHk9IjUiIHdpZHRoPSI2IiBoZWlnaHQ9IjYiIHJ4PSIxIi8+PHBhdGggZD0ibTMgMTcgMiAyIDQtNCIvPjxwYXRoIGQ9Ik0xMyA2aDgiLz48cGF0aCBkPSJNMTMgMTJoOCIvPjxwYXRoIGQ9Ik0xMyAxOGg4Ii8+PC9zdmc+'),

            Stat::make('Total Dépense / Budget estimé', Number::currency(Chantiers::sum('budget_estime'), 'EUR', 'fr', 2)."/".Number::currency(ChantierDepense::sum('montant'), 'EUR', 'fr', 2))
            ->icon('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIGNsYXNzPSJsdWNpZGUgbHVjaWRlLWV1cm8taWNvbiBsdWNpZGUtZXVybyI+PHBhdGggZD0iTTQgMTBoMTIiLz48cGF0aCBkPSJNNCAxNGg5Ii8+PHBhdGggZD0iTTE5IDZhNy43IDcuNyAwIDAgMC01LjItMkE3LjkgNy45IDAgMCAwIDYgMTJjMCA0LjQgMy41IDggNy44IDggMiAwIDMuOC0uOCA1LjItMiIvPjwvc3ZnPg==')
        ];
    }
}
