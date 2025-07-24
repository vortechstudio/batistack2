<?php

namespace App\Livewire\Humans\Components\Tables;

use App\Models\RH\Employe;
use App\Models\RH\Paie\FichePaie;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Tables\Table;

class TablePaie extends Component implements HasActions, HasSchemas, HasTable
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
