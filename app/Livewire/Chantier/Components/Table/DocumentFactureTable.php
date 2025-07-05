<?php

declare(strict_types=1);

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Facture;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;

final class DocumentFactureTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(Facture::query()->where('chantiers_id', $this->chantier->id))
            ->recordUrl(fn (Model $record) => route('chantiers.view', $record->id))
            ->columns([
                TextColumn::make('num_facture')
                    ->label('Réference')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type_facture')
                    ->label('Type de facture')
                    ->sortable()
                    ->formatStateUsing(fn (?Model $record) => 'Facture '.$record->type_facture->label()),

                TextColumn::make('status')
                    ->label('Etat de la facture')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(function (?Model $record) {
                        return new HtmlString("<span class='inline-flex items-center rounded-md bg-{$record->status->color()}-100 px-2 py-1 text-sm font-medium text-{$record->status->color()}-600 ring-1 ring-{$record->status->color()}-500/10 ring-inset'>{$record->status->label()}</span>");
                    }),

                TextColumn::make('date_facture')
                    ->label('Date du devis')
                    ->sortable()
                    ->date(),

                TextColumn::make('date_echeance')
                    ->label("Date d'échéance")
                    ->sortable()
                    ->date(),

                TextColumn::make('amount_ht')
                    ->label('Montant HT')
                    ->money('EUR'),

                TextColumn::make('amount_ttc')
                    ->label('Montant TTC')
                    ->money('EUR'),

            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.document-facture-table');
    }
}
