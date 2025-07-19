<?php

namespace App\Livewire\Humans\Salarie;

use App\Enums\RH\ProcessEmploye;
use App\Helpers\RH\GenerateDPAE;
use App\Models\RH\Employe;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Process;

class Transmission extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Employe $salarie;
    public ?array $transmitData = [];
    public ?array $validatingData = [];
    public ?array $sendingData = [];
    public ?array $sendingContractData = [];

    public function mount(int $id)
    {
        $this->salarie = Employe::find($id);
    }

    protected function getForms(): array
    {
        return [
            'transmitForm',
            'validatingForm',
            'sendingForm',
            'sendingContractForm'
        ];
    }

    public function transmitForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(5)
                    ->schema([
                        FileUpload::make('cni_recto')
                            ->label('Carte Identité (Recto)')
                            ->required()
                            ->disk('public')
                            ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'cni-recto.'.$file->getClientOriginalExtension()),

                        FileUpload::make('cni_verso')
                            ->label('Carte Identité (Verso)')
                            ->required()
                            ->disk('public')
                            ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'cni-verso.'.$file->getClientOriginalExtension()),

                        FileUpload::make('btp_card')
                            ->label('Carte BTP')
                            ->disk('public')
                            ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'btp-card.'.$file->getClientOriginalExtension()),

                        FileUpload::make('vital_card')
                            ->label('Carte Vital')
                            ->required()
                            ->disk('public')
                            ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'carte-vital.'.$file->getClientOriginalExtension()),

                        FileUpload::make('rib')
                            ->label('RIB')
                            ->required()
                            ->disk('public')
                            ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'rib.'.$file->getClientOriginalExtension()),
                    ]),
            ])
            ->statePath('transmitData');
    }

    public function validatingForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Toggle::make('cni_validate')
                            ->label('Valider la carte Identité'),

                        Toggle::make('carte_vital_validate')
                            ->label('Valider la carte Vital'),
                    ])
            ])
            ->statePath('validatingData');
    }

    public function sendingForm(Schema $schema): Schema
    {
        return $schema
            ->components([

            ])
            ->statePath('sendingData');
    }

    public function sendingContractForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('contract')
                    ->label('Contrat de travail')
                    ->required()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('rh/salarie/'.$this->salarie->id.'/documents')
                    ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) 'contrat_travail.'.$file->getClientOriginalExtension()),
            ])
            ->statePath('sendingContractData');
    }

    public function transmit()
    {
        $this->salarie->info->update([
            'cni_transmit' => true,
            'vital_card_transmit' => true,
            'rib_transmit' => true,
            'process' => ProcessEmploye::VALIDATING,
        ]);
    }

    public function validating()
    {
        $this->salarie->info->update([
            'cni_verified_at' => $this->validatingForm->getState()['cni_validate'] ? now() : null,
            'vital_verified_at' => $this->validatingForm->getState()['carte_vital_validate'] ? now() : null,
            'process' => ProcessEmploye::DPAE,
        ]);
    }

    public function sending()
    {
        $dp = new GenerateDPAE();
        $dpae_name = 'dpae_'.$this->salarie->nom.'_'.$this->salarie->prenom.'_'.now()->format('Ymd_His').'.xml';
        $dp->generate($this->salarie, $dpae_name);

        $this->salarie->info->update([
            'process' => ProcessEmploye::SENDING_EXP
        ]);
    }

    public function sendingContract()
    {
        $this->salarie->contrat->update([
            'status' => 'draft'
        ]);

        $this->salarie->info->update([
            'process' => ProcessEmploye::CONTRACT_DRAFT,
        ]);

        $this->redirect(route('humans.salaries.view', $this->salarie->id));
    }

    #[Title('Fiche d\'un Salarie')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.transmission');
    }
}
