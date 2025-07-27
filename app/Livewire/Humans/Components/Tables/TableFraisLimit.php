<?php

declare(strict_types=1);

namespace App\Livewire\Humans\Components\Tables;

use App\Models\RH\NoteFrais;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;

final class TableFraisLimit extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions, InteractsWithSchemas, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(NoteFrais::query()->with('employe')->latest()->take(3))
            ->heading('Les 3 dernières notes de frais')
            ->paginated(false)
            ->searchable(false)
            ->columns([
                TextColumn::make('numero')
                    ->label(''),

                TextColumn::make('employe.full_name')
                    ->label('')
                    ->formatStateUsing(function ($state, $record) {
                        $employe = $record->employe;
                        if (! $employe) {
                            return '-';
                        }

                        $avatar = $employe->getFirstMediaUrl();
                        $avatarHtml = "<img src='{$avatar}' class='w-8 h-8 rounded-full mr-2 inline-block' alt='Avatar'>";

                        return new HtmlString($avatarHtml.$employe->full_name);
                    })
                    ->html(),

                TextColumn::make('montant_total')
                    ->label('')
                    ->money('EUR', 0, 'fr'),

                TextColumn::make('updated_at')
                    ->label('')
                    ->date('d/m/Y'),

            ]);
    }

    public function render()
    {
        return view('livewire.humans.components.tables.table-frais-limit');
    }
}
