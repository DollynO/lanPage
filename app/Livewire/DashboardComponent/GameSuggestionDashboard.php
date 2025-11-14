<?php

namespace App\Livewire\DashboardComponent;

use App\Models\Game;
use App\Models\GameSuggestion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GameSuggestionDashboard extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public $party;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => GameSuggestion::query())
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('game.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('game.genre')
                    ->label('Genre')
                    ->sortable(),
                TextColumn::make('game.player_count')
                    ->label('Player Count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('game.price')
                    ->label('Price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('game.source')
                    ->label('Source')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Suggested')
                    ->sortable(),
                IconColumn::make('played')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('addGame')
                    ->extraAttributes(['class' => 'neon-button-primary'])
                    ->label('Suggest Game')
//                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        Select::make('game_id')
                            ->label('Game')
                            ->searchable()
                            ->preload()
                            ->options(Game::query()->pluck('name', 'id'))
                            ->required(),
                        // Optional: Pivot-Extra-Feld
                        // Forms\Components\Select::make('role')
                        //     ->options(['guest' => 'Guest', 'admin' => 'Admin']),
                    ])
                    ->action(function (array $data, Table $table): void {

                        $gameId = $data['game_id'];
                        $partyId = $this->party->id;
                        $userId = Auth::id();
                        $played = 0;

                        $gameSuggestions = GameSuggestion::query()->where([
                            ['game_id', $gameId],
                            ['party_id', $partyId],
                            ['user_id', $userId],
                        ])->first();

                        if  ($gameSuggestions) {
                            Notification::make()
                                ->title('Already assigned.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $game = Game::query()->whereKey($gameId)->firstOrNew();
//                        if (!$this->editGame){
//                            $game->name = $this->edit['name'];
//                        }
//                        $game->genre = $this->edit['genre'];
//                        $game->player_count = $this->edit['player_count'];
//                        $game->price = $this->edit['price'] ?? 0;
//                        $game->source = $this->edit['source'];
//                        $game->save();

                        $gameSuggestion = new GameSuggestion();
                        $gameSuggestion->party_id = $partyId;
                        $gameSuggestion->user_id = $userId;
                        $gameSuggestion->game_id = $gameId;
                        $gameSuggestion->save();

                    })
            ])
            ->recordActions([
                DeleteAction::make('Remove Suggestion')
                    ->label('Remove Suggestion')
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
        return view('livewire.dashboard-component.game-suggestion-dashboard');
    }
}
