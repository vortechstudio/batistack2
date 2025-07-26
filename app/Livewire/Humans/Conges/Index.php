<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Conges;

use App\Enums\RH\StatusAbsence;
use App\Enums\RH\TypeAbsence;
use App\Models\RH\Employe;
use App\Models\RH\EmployeAbscence;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Index extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Liste des congés & absences')
            ->query(EmployeAbscence::query())
            ->toolbarActions([
                BulkAction::make('delete')
                    ->color('danger')
                    ->icon(Heroicon::Trash)
                    ->label('Supprimer')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),

                BulkAction::make('validate')
                    ->color('success')
                    ->icon(Heroicon::Check)
                    ->label('Valider')
                    ->action(fn (Collection $records) => $records->each->update(['status' => StatusAbsence::APPROVED])),
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->label('Ajouter un congés/absence')
                    ->schema([
                        Select::make('employe_id')
                            ->label('Salarié')
                            ->searchable()
                            ->options(Employe::all()->pluck('full_name', 'id'))
                            ->required(),

                        Select::make('type')
                            ->label('Type')
                            ->options(TypeAbsence::class)
                            ->required(),

                        Grid::make()
                            ->schema([
                                DateTimePicker::make('date_debut')
                                    ->label('Date de début')
                                    ->required(),

                                DateTimePicker::make('date_fin')
                                    ->label('Date de fin')
                                    ->required(),
                            ]),

                        MarkdownEditor::make('motif')
                            ->label('Motif'),
                    ])
                    ->using(function (array $data) {
                        EmployeAbscence::create($data);
                    }),
            ])
            ->columns([
                TextColumn::make('reference')
                    ->label('Reférence')
                    ->sortable(),

                TextColumn::make('employe.full_name')
                    ->label('Salarié')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->formatStateUsing(fn (?Model $record) => $record->type->label()),

                TextColumn::make('nb_jour')
                    ->label('Jours'),

                TextColumn::make('date_debut')
                    ->label('Date de début')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->date('d/m/Y'),

                TextColumn::make('date_fin')
                    ->label('Date de fin')
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->date('d/m/Y'),

                TextColumn::make('status')
                    ->label('Etat')
                    ->sortable()
                    ->tooltip(fn (?Model $record) => $record->status->label())
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString('<div aria-label="status" class="status status-xl bg-'.$record->status->color().'-100"></div>');
                    }),
            ])
            ->recordActions([
                ViewAction::make('view')
                    ->icon(Heroicon::Eye)
                    ->iconButton()
                    ->color('info')
                    ->schema([
                        Select::make('employe_id')
                            ->label('Salarié')
                            ->searchable()
                            ->options(Employe::all()->pluck('full_name', 'id'))
                            ->required(),

                        Select::make('type')
                            ->label('Type')
                            ->options(TypeAbsence::class)
                            ->required(),

                        Grid::make()
                            ->schema([
                                DateTimePicker::make('date_debut')
                                    ->label('Date de début')
                                    ->required(),

                                DateTimePicker::make('date_fin')
                                    ->label('Date de fin')
                                    ->required(),
                            ]),

                        MarkdownEditor::make('motif')
                            ->label('Motif'),
                    ]),

                EditAction::make('editing')
                    ->icon(Heroicon::Pencil)
                    ->iconButton()
                    ->color('primary')
                    ->schema([
                        Select::make('employe_id')
                            ->label('Salarié')
                            ->searchable()
                            ->options(Employe::all()->pluck('full_name', 'id'))
                            ->required(),

                        Select::make('type')
                            ->label('Type')
                            ->options(TypeAbsence::class)
                            ->required(),

                        Grid::make()
                            ->schema([
                                DateTimePicker::make('date_debut')
                                    ->label('Date de début')
                                    ->required(),

                                DateTimePicker::make('date_fin')
                                    ->label('Date de fin')
                                    ->required(),
                            ]),

                        MarkdownEditor::make('motif')
                            ->label('Motif'),
                    ]),

                DeleteAction::make('delete')
                    ->icon(Heroicon::Trash)
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation(),
            ]);
    }

    #[Title('Gestion des congés & absences')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.conges.index');
    }
}
