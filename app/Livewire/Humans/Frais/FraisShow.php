<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Frais;

use App\Actions\RH\GenerateNoteFrais;
use App\Mail\Core\ProfessionalMail;
use App\Models\Core\ModeReglement;
use App\Models\RH\Employe;
use App\Models\RH\NoteFrais;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class FraisShow extends Component implements HasActions, HasSchemas
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
            ->label('Envoyer un email')
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

                Select::make('employe_id')
                    ->label('Employé')
                    ->options(Employe::pluck('nom', 'id'))
                    ->required(),

                Textarea::make('commentaire_employe')
                    ->label('Commentaire'),
            ])
            ->using(function (array $data) {});
    }

    public function validateAction(): Action
    {
        return Action::make('validate')
            ->label('Valider pour approbation')
            ->icon(Heroicon::CheckCircle)
            ->schema([
                TextInput::make('montant_total')
                    ->label('Montant déclaré')
                    ->default($this->frais->montant_total)
                    ->disabled(),

                Textarea::make('commentaire_validateur')
                    ->label('commentaire'),

                CheckboxList::make('details')
                    ->label('Détails à valider')
                    ->options($this->frais->details->mapWithKeys(function ($detail) {
                        $dateFormatted = $detail->date_frais->format('d/m/Y');
                        $montantFormatted = number_format($detail->montant_ttc_calcule, 2, ',', ' ') . ' €';
                        $label = "{$dateFormatted} - {$detail->libelle} - {$montantFormatted}";
                        return [$detail->id => $label];
                    }))
                    ->default($this->frais->details->pluck('id')->toArray()),
            ])
            ->action(function (array $data) {
                // Récupérer les détails sélectionnés depuis le formulaire
                $detailsSelectionnes = $data['details'] ?? [];

                $this->frais->valider(Auth::user(), $data['commentaire_validateur'], $detailsSelectionnes);
                app(GenerateNoteFrais::class)->handle($this->frais);
                Mail::to($this->frais->employe->user->email)
                    ->send(new ProfessionalMail(
                        emailSubject: "Votre note de frais n°{$this->frais->numero} a été validée",
                        greeting: "Bonjour {$this->frais->employe->full_name},",
                        content: "Votre note de frais n°{$this->frais->numero} a été validée le {$this->frais->date_validation->format('d/m/Y')}.<br><br>Le document relatif à cette note est disponible dans votre espace.<br><br>Merci de ne pas répondre à ce message.",
                    ));
            });
    }

    public function payerAction(): CreateAction
    {
        return CreateAction::make('payer')
            ->label('Payer')
            ->icon(Heroicon::CurrencyEuro)
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('mode_reglement_id')
                            ->label('Mode de Paiement')
                            ->options(ModeReglement::pluck('name', 'id'))
                            ->required(),

                        TextInput::make('reference_paiement')
                            ->label('Référence de Paiement'),

                        TextInput::make('montant')
                            ->label('Montant de Paiement')
                            ->default($this->frais->montant_valide)
                            ->required(),
                    ]),
            ])
            ->action(function (array $data) {
                $paiement = $this->frais->paiement()->create([
                    'note_frais_id' => $this->frais->id,
                    'mode_reglement_id' => $data['mode_reglement_id'],
                    'numero_paiement' => $data['reference_paiement'] ?? null,
                    'date_paiement' => now(),
                    'montant' => $data['montant'],
                ]);
                $this->frais->marquerPayee($paiement->numero_paiement);
            });
    }

    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label('Soumettre')
            ->icon(Heroicon::PaperAirplane)
            ->action(function () {
                $this->frais->soumettre();
                $this->frais->refresh();
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

    public function refuseAction(): Action
    {
        return Action::make('refuse')
            ->label('Refuser')
            ->icon(Heroicon::XMark)
            ->requiresConfirmation()
            ->modalHeading("Refuser la note de frais")
            ->modalDescription("Êtes-vous sûr de vouloir refuser la note de frais ?")
            ->schema([
                Textarea::make('commentaire_validateur')
                    ->label('Commentaire')
                    ->required(),
            ])
            ->action(function (array $data) {
                $this->frais->refuser(Auth::user(), $data['commentaire_validateur']);
                Mail::to($this->frais->employe->user->email)
                    ->send(new ProfessionalMail(
                        emailSubject: "Votre note de frais n°{$this->frais->numero} a été refusée",
                        greeting: "Bonjour {$this->frais->employe->full_name},",
                        content: "Votre note de frais n°{$this->frais->numero} a été refusée le {$this->frais->date_refus->format('d/m/Y')}.<br><br>{$this->frais->commentaire_validateur}<br><br>Merci de ne pas répondre à ce message.",
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
