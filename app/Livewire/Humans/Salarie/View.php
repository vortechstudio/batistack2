<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Salarie;

use App\Enums\RH\ProcessEmploye;
use App\Enums\RH\StatusContrat;
use App\Models\RH\Employe;
use App\Notifications\RH\NewSalarieNotification;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class View extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Employe $salarie;

    public ?array $contractValidatedData = [];

    public function mount(int $id)
    {
        $this->salarie = Employe::find($id);
    }

    public function contractValidatedForm(Schema $schema): Schema
    {
        return $schema
            ->components([

            ])
            ->statePath('contractValidatedData');
    }

    public function validateContract()
    {
        $pass = Str::random(8);

        // Activer le compte salarié
        $this->salarie->user->update([
            'blocked' => false,
            'email_verified_at' => now(),
            'password' => Hash::make($pass),
        ]);

        $this->salarie->info->update([
            'process' => ProcessEmploye::CONTRACT_VALIDATE,
        ]);

        $this->salarie->contrat->update([
            'status' => StatusContrat::CHECKED,
            'signed_start_at' => now(),
            'signed_code_otp' => rand(1000, 9999),
        ]);

        Storage::disk('public')->copy('rh/salarie/'.$this->salarie->id.'/documents/contrat.pdf', 'ged/'.$this->salarie->matricule.'/documents/rh/contrat.pdf');

        $this->salarie->user->notify(new NewSalarieNotification($this->salarie, $pass));

        Notification::make()
            ->success()
            ->title('Contrat valider avec succès')
            ->send();

    }

    #[Title('Fiche d\'un Salarie')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.view');
    }

    protected function getForms()
    {
        return [
            'contractValidatedForm',
        ];
    }
}
