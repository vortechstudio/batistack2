<?php

declare(strict_types=1);

namespace App\Livewire\Tiers\Supply\Component;

use App\Mail\Core\MailToTiers;
use App\Models\Tiers\Tiers;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

final class TabTiers extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Tiers $tiers;

    public ?array $data = [];

    public function mount(Tiers $tiers): void
    {
        $this->tiers = $tiers;
    }

    public function draftMailAction(): Action
    {
        return Action::make('draftMail')
            ->label('Envoyer un email')
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->default($this->tiers->contacts()->first()->email),

                TextInput::make('subject')
                    ->label('Sujet'),

                RichEditor::make('message')
                    ->label('Message'),
            ])
            ->action(function (array $data) {
                Mail::to($data['email'])->send(new MailToTiers($data['email'], $data['message'], $data['subject']));
            });
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Modifier')
            ->url(route('tiers.supply.edit', $this->tiers->id));
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->color('danger')
            ->icon('solar-trash-bin-2-bold-duotone')
            ->label('Supprimer')
            ->url('/');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Derniers Evènements')
            ->query(Tiers::query()->where('tiers.id', $this->tiers->id)->newQuery())
            ->columns([
                TextColumn::make('id')
                    ->label('# Ref'),

                TextColumn::make('title')
                    ->label('Titre'),
            ]);
    }

    public function render()
    {
        return view('livewire.tiers.supply.component.tab-tiers');
    }
}
