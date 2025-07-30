<?php

declare(strict_types=1);

namespace App\Livewire\Portail\Salarie;

use App\Enums\RH\StatusNoteFrais;
use App\Models\RH\NoteFrais;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Frais extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Mes notes de frais')
            ->emptyStateHeading('Aucune note de frais')
            ->emptyStateDescription('Veuillez ajouter une note de frais')
            ->headerActions([
                CreateAction::make('create')
                    ->label('Ajouter une note de frais')
                    ->icon(Heroicon::PlusCircle)
                    ->schema([
                        DatePicker::make('date_debut')
                            ->label('Date de début')
                            ->required(),

                        DatePicker::make('date_fin')
                            ->label('Date de fin')
                            ->required(),

                        Textarea::make('commentaire_employe')
                            ->label('Commentaire'),
                    ])
                    ->using(function (array $data) {
                        $data['employe_id'] = Auth::user()->employe->id;
                        NoteFrais::create([
                            'employe_id' => $data['employe_id'],
                            'date_debut' => $data['date_debut'],
                            'date_fin' => $data['date_fin'],
                            'commentaire_employe' => $data['commentaire_employe'],
                        ]);
                    }),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options(StatusNoteFrais::getSelectOptions()),

                QueryBuilder::make()
                    ->constraints([
                        DateConstraint::make('date_debut'),
                        DateConstraint::make('date_fin'),
                    ]),
            ])
            ->filtersFormWidth(Width::FourExtraLarge)
            ->query(NoteFrais::query()->where('employe_id', Auth::user()->employe->id))
            ->recordUrl(fn (?Model $record) => route('portail.salarie.frais.show', $record->id))
            ->columns([
                TextColumn::make('numero')
                    ->label('Réf.')
                    ->searchable(isIndividual: true),

                TextColumn::make('date_debut')
                    ->label('Date de début')
                    ->sortable()
                    ->date('d/m/Y'),

                TextColumn::make('date_fin')
                    ->label('Date de fin')
                    ->sortable()
                    ->date('d/m/Y'),

                TextColumn::make('date_soumission')
                    ->label('Date de Soumission')
                    ->sortable()
                    ->date('d/m/Y'),

                TextColumn::make('date_validation')
                    ->label('Date de validation')
                    ->sortable()
                    ->date('d/m/Y'),

                TextColumn::make('montant_total')
                    ->label('Montant total')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->money('EUR', 0, 'fr')
                    ->summarize(Sum::make()->label('Total')->money('EUR', locale: 'fr')),

                TextColumn::make('montant_valide')
                    ->label('Montant validé')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->money('EUR', 0, 'fr')
                    ->summarize(Sum::make()->label('Total')->money('EUR', locale: 'fr')),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        return new HtmlString("<span class=inline-flex items-center rounded-md bg-{$record->statut->color()}-100 px-2 py-1 text-md font-medium text-{$record->statut->color()}-600 ring-1 ring-{$record->statut->color()}-500/10 ring-inset'>{$record->statut->label()}</span>");
                    }),
            ]);
    }

    #[Title('Mes Frais')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.frais');
    }
}
