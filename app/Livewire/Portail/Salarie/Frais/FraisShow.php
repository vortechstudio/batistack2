<?php

namespace App\Livewire\Portail\Salarie\Frais;

use App\Models\RH\NoteFrais;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class FraisShow extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;
    public NoteFrais $frais;

    public function mount(int $id)
    {
         $this->frais = NoteFrais::find($id);
    }

    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->label('Modifier')
            ->icon(Heroicon::Pencil)
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
            ->using(function (array $data) {});
    }

    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label('Soumettre')
            ->icon(Heroicon::PaperAirplane)
            ->action(function () {
                $this->frais->soumettre();
                $this->frais->refresh();
                Notification::make()
                    ->info()
                    ->title("Nouvelle soumission de note de frais")
                    ->body("La note de frais n°{$this->frais->numero} a été soumise et est en attente de validation.")
                    ->sendToDatabase(User::find(1));
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Supprimer')
            ->icon(Heroicon::Trash)
            ->requiresConfirmation()
            ->action(function () {
                $this->frais->delete();
            });
    }

    #[Title('Mes Frais')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.frais.frais-show');
    }
}
