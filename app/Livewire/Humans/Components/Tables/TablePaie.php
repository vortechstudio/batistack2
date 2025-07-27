<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Models\RH\Employe;
use App\Models\RH\Paie\FichePaie;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

final class TablePaie extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public Employe $employe;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Les derniers salaires')
            ->query(FichePaie::where('employe_id', $this->employe->id))
            ->columns([

            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-paie');
    }
}
