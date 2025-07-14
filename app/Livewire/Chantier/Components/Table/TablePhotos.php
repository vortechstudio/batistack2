<?php

namespace App\Livewire\Chantier\Components\Table;

use App\Models\Chantiers\ChantierPhotos;
use App\Models\Chantiers\Chantiers;
use Exception;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TablePhotos extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public Chantiers $chantier;

    public function table(Table $table): Table
    {
        return $table
            ->query(ChantierPhotos::where('chantiers_id', $this->chantier->id))
            ->selectable()
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter une photo')
                    ->schema([
                        FileUpload::make('photos')
                            ->multiple()
                            ->directory('chantiers/'.$this->chantier->id)
                            ->visibility('public')
                            ->disk('public'),
                    ])
                    ->using(function (array $data, string $model) {
                        try {
                            foreach ($data['photos'] as $photo) {
                                $model::create([
                                    'chantiers_id' => $this->chantier->id,
                                    'photo_path' => $photo,
                                ]);
                            }
                        } catch(Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    })
            ])
            ->toolbarActions([
                BulkAction::make('delete')
                    ->label('Suppression en masse')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        try {
                            foreach ($records as $record) {
                                Storage::disk('public')->delete($record->photo_path);
                                $record->delete();
                            }
                        } catch(Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),
            ])
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->disk('public'),

                TextColumn::make('description')
                    ->label('Description'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/Y'),
            ])
            ->recordActions([
                EditAction::make()
                        ->iconButton()
                        ->schema([
                            Textarea::make('description')
                                ->label('Description'),
                        ]),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->color('danger')
                    ->iconButton()
                    ->action(function (?Model $record) {
                        try {
                            Storage::disk('public')->delete($record->photo_path);
                            $record->delete();
                        } catch(Exception $ex) {
                            Log::channel('github')->emergency($ex);
                        }
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.chantier.components.table.table-photos');
    }
}
