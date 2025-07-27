<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Frais;

use App\Enums\RH\StatusNoteFrais;
use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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
            ->query(NoteFrais::query())
            ->heading('Liste des notes de frais')
            ->emptyStateHeading('Aucune note de frais trouvée')
            ->headerActions([
                CreateAction::make('create')
                    ->label('Créer une note de frais')
                    ->icon(Heroicon::PlusCircle)
                    ->schema([
                        DatePicker::make('date_debut')
                            ->label('Date de début')
                            ->required(),

                        DatePicker::make('date_fin')
                            ->label('Date de fin')
                            ->required(),

                        Select::make('employe_id')
                            ->label('Employé')
                            ->options(Employe::pluck('nom', 'id'))
                            ->required(),

                        Textarea::make('commentaire_employe')
                            ->label('Commentaire'),
                    ]),
            ])
            ->recordUrl(fn (?Model $record) => route('humans.frais.show', $record->id))
            ->columns([
                TextColumn::make('numero')
                    ->label('Réf.')
                    ->searchable(isIndividual: true),

                TextColumn::make('employe.full_name')
                    ->label('Employé')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $employe = $record->employe;
                        if (! $employe) {
                            return '-';
                        }

                        $avatar = $employe->getFirstMediaUrl();
                        $avatarHtml = "<img src='{$avatar}' class='w-8 h-8 rounded-full mr-2 inline-block' alt='Avatar'>";

                        return new HtmlString($avatarHtml.$employe->full_name);
                    })
                    ->html(),

                TextColumn::make('date_debut')
                    ->label('Date début')
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
            ]);
    }

    #[Title('Note de Frais')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.frais.frais');
    }
}
