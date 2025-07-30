<?php

declare(strict_types=1);

namespace App\Livewire\Portail\Salarie;

use App\Models\RH\NoteFrais;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
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
                    ]),
            ])
            ->filters([

            ])
            ->query(NoteFrais::query()->where('employe_id', Auth::user()->employe->id))
            ->columns([

            ]);
    }

    #[Title('Mes Frais')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.frais');
    }
}
