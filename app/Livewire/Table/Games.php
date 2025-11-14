<?php

namespace App\Livewire\Table;

use App\Filament\Tables\Columns\CommentColumn;
use App\Filament\Tables\Columns\StarRatingColumn;
use App\Models\Game;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Games extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Game::query())
            ->columns([
                    TextColumn::make('name')
                        ->sortable()
                        ->searchable(),
                    TextColumn::make('genre')
                        ->sortable()
                        ->searchable(),
                    TextColumn::make('player_count')
                        ->numeric()
                        ->sortable(),
                    TextColumn::make('price')
                        ->money()
                        ->sortable(),
                    TextColumn::make('source')
                        ->sortable()
                        ->searchable(),
                    IconColumn::make('already_played')
                        ->sortable()
                        ->boolean(),
                    TextColumn::make('note')
                        ->searchable(),
//                    TextColumn::make('ratings_avg_rating')
//                        ->avg('ratings','rating')
//                        ->visibleFrom('md'),
                    StarRatingColumn::make('rating')
                        ->label('Bewertung'),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->extraAttributes(['class' => 'neon-button-primary'])
                    ->slideOver()
                    ->model(Game::class)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('genre')
                            ->required(),
                        Select::make('player_count')
                            ->required()
                            ->options([
                                '1', '2', '3', '4', '5',
                                '6', '7', '8', '9', '10',
                                '11', '12', '13', '14', '15',
                                '16', '17', '18', '19', '20',
                            ]),
                        TextInput::make('price')
                            ->required(),
                        TextInput::make('source')
                            ->required(),
                        Toggle::make('already_played'),
                        Textarea::make('note'),

                    ])
            ])
            ->recordActions([
                ViewAction::make('view')
                    ->schema([
//                        TextInput::make('name')
//                            ->required(),
//                        TextInput::make('genre')
//                            ->required(),
//                        Select::make('player_count')
//                            ->required()
//                            ->options([
//                                '1', '2', '3', '4', '5',
//                                '6', '7', '8', '9', '10',
//                                '11', '12', '13', '14', '15',
//                                '16', '17', '18', '19', '20',
//                            ]),
//                        TextInput::make('price')
//                            ->required(),
//                        TextInput::make('source')
//                            ->required(),
//                        Checkbox::make('already_played'),
                        Textarea::make('note'),
                    ]),

                EditAction::make('edit')
                    ->slideOver()
                    ->schema([
//                        TextInput::make('name')
//                            ->required(),
                        TextInput::make('genre')
                            ->required(),
                        Select::make('player_count')
                            ->required()
                            ->options([
                                '1', '2', '3', '4', '5',
                                '6', '7', '8', '9', '10',
                                '11', '12', '13', '14', '15',
                                '16', '17', '18', '19', '20',
                            ]),
                        TextInput::make('price')
                            ->required(),
                        TextInput::make('source')
                            ->required(),
                        Toggle::make('already_played'),
                        Textarea::make('note'),
                        TextColumn::make('id'),
//                        CommentColumnTest::make('name'),
                        // Livewire-Komponente als eingebettete View
                        \Filament\Schemas\Components\View::make('components.comment.wrapper')
//                            ->viewData(function (EditAction $action) {
//                                return [
//                                    'record' => $action->getRecord(),
//                                ];
//                            })
                    ]),
                DeleteAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn ( $record) => $record->delete())
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.table.games');
    }
}
