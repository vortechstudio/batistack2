<?php

namespace App\Livewire\Humans\Frais;

use App\Mail\Core\MailToTiers;
use App\Mail\Core\ProfessionalMail;
use App\Models\RH\NoteFrais;
use App\Notifications\Core\SendMessageTo;
use Auth;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
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

    public function sendMailAction(): Action
    {
        return Action::make('sendMail')
            ->color('primary')
            ->label("Envoyer un email")
            ->icon(Heroicon::Envelope)
            ->schema([
                TextInput::make('from')
                    ->label('De')
                    ->required()
                    ->default(Auth::user()->email),

                TextInput::make('to')
                    ->label('À')
                    ->required()
                    ->default($this->frais->employe->email),

                TextInput::make('subject')
                    ->label('Objet')
                    ->default("Votre note de frais n°{$this->frais->numero}")
                    ->required(),

                RichEditor::make('message')
                    ->label('Message')
                    ->default(function () {
                        return new HtmlString("Bonjour {$this->frais->employe->nom} {$this->frais->employe->prenom},<br><br>Veuillez trouvez ci-dessous vos frais de {$this->frais->date_debut->format('d/m/Y')} à {$this->frais->date_fin->format('d/m/Y')}.<br><br>Merci de ne pas répondre à ce message.");
                    }),
            ])
            ->action(function (array $data) {
                Mail::to($data['to'])
                    ->send(new ProfessionalMail(
                        emailSubject: $data['subject'],
                        content: $data['message'],
                    ));
            });
    }

    #[Title('Note de frais - Fiche')]
    #[Layout('components.layouts.humans')]
    public function render()
    {
        return view('livewire.humans.frais.frais-show');
    }
}
