<?php

namespace App\Livewire\Humans\Salarie;

use App\Enums\RH\ProcessEmploye;
use App\Jobs\RH\VerifyBTPCard;
use App\Jobs\RH\VerifyCarteVital;
use App\Jobs\RH\VerifyCNI;
use App\Models\RH\Employe;
use App\Services\TesseractService;
use Bus;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Storage;

class Transmission extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Employe $salarie;
    public ?array $data = [];

    public function mount(int $id)
    {
        $this->salarie = Employe::find($id);
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
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
            ->statePath('data');
    }

    public function transmit()
    {
        $this->salarie->info->update([
            'cni_transmit' => true,
            'btp_card_transmit' => !empty($this->form->getState()['btp_card']),
            'vital_card_transmit' => true,
            'rib_transmit' => true,
            'process' => ProcessEmploye::VALIDATING,
        ]);

        Bus::chain([
            new VerifyCNI($this->salarie, $this->form->getState()['cni_recto']),
            new VerifyCarteVital($this->salarie, $this->form->getState()['vital_card']),
            new VerifyBTPCard($this->salarie, $this->form->getState()['btp_card']),
        ])
        ->dispatch();


        Notification::make()
            ->title('Vérification OCR en cours.')
            ->info()
            ->send();

        $this->redirect(route('humans.salaries.view', $this->salarie->id));
    }

    #[Title('Fiche d\'un Salarie')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.salarie.transmission');
    }
}
