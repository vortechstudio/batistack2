<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Models\RH\Employe;
use App\Models\RH\EmployeAbscence;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;

final class TableConges extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public ?Employe $employe;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Les derniers congés & Absences')
            ->query(isset($this->employe) ? EmployeAbscence::where('employe_id', $this->employe->id) : EmployeAbscence::query())
            ->columns([
                TextColumn::make('reference')
                    ->label('Réf.'),

                TextColumn::make('employe_id')
                    ->label('Employé')
                    ->formatStateUsing(fn (?Model $record) => $record->full_name),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (?Model $record) => $record->type->label()),

                TextColumn::make('date_debut')
                    ->label('Date de début')
                    ->date('d/m/Y'),

                TextColumn::make('date_fin')
                    ->label('Date de fin')
                    ->date('d/m/Y'),

                TextColumn::make('status')
                    ->label('Etat')
                    ->tooltip(fn (?Model $record) => $record->status->label())
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString('<div aria-label="status" class="status status-xl bg-'.$record->status->color().'-100"></div>');
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-conges');
    }
}
