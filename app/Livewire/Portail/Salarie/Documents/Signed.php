<?php

declare(strict_types=1);

namespace App\Livewire\Portail\Salarie\Documents;

use App\Enums\RH\ProcessEmploye;
use App\Enums\RH\StatusContrat;
use App\Models\RH\EmployeContrat;
use App\Notifications\RH\SendOTPCodeToSignContrat;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Signed extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;

    public EmployeContrat $contrat;

    public ?array $data = [];

    public bool $sendingOtp = false;

    public function mount(int $id)
    {
        $this->contrat = EmployeContrat::findOrFail($id);
        $this->form->fill();
        if ($this->contrat->status->value === 'checked') {
            $this->contrat->employe->user->notify(new SendOTPCodeToSignContrat($this->contrat));
            $this->sendingOtp = true;
        }
    }

    public function form(Schema $schema)
    {
        return $schema
            ->components([
                Checkbox::make('accept_tos')
                    ->label("J'accepte les informations transmise sur présent contrat de travail."),

                TextInput::make('code_otp')
                    ->label('Code OTP')
                    ->required(),

                Grid::make()
                    ->schema([
                        TextInput::make('nom')
                            ->label('Votre nom de famille')
                            ->required(),

                        TextInput::make('prenom')
                            ->label('Votre prénom')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function signContract()
    {
        $data = $this->form->getState();

        if ($data['accept_tos']) {
            if ($data['code_otp'] === $this->contrat->signed_code_otp && Str::lower($data['nom']) === Str::lower($this->contrat->employe->nom) && Str::lower($data['prenom']) === Str::lower($this->contrat->employe->prenom)) {
                $this->contrat->status = StatusContrat::ACTIF;
                $this->contrat->signed_start_at = null;
                $this->contrat->signed_code_otp = null;
                $this->contrat->save();

                $this->contrat->employe->info->process = ProcessEmploye::CONTRACT_SIGN;
                $this->contrat->employe->info->save();

                Notification::make()
                    ->danger()
                    ->title('Félicitations!')
                    ->body('Votre contrat a été signé avec succès.')
                    ->send();

                $this->redirect(route('portail.salarie.dashboard'));
            } else {
                Notification::make()
                    ->danger()
                    ->title('Erreur!')
                    ->body("Votre contrat n'a pas été signé.")
                    ->send();
            }
        } else {
            Notification::make()
                ->danger()
                ->title('Erreur!')
                ->body('Veuillez accepter les informations transmise sur présent contrat de travail.')
                ->send();
        }
    }

    #[Title('Mes Documents')]
    #[Layout('components.layouts.portail.salarie')]
    public function render()
    {
        return view('livewire.portail.salarie.documents.signed');
    }
}
