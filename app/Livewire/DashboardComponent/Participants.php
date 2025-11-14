<?php

namespace App\Livewire\DashboardComponent;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Participants extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    protected $listeners = [
        'example' => '$refresh',
    ];

    public $party;
    public bool $takesPart = false;

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): array => $this->buildArray())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('start')
                    ->date(),
                TextColumn::make('end')
                    ->date(),
            ])
            ->recordActions([
//                DeleteAction::make('delete')
//                    ->requiresConfirmation()
//                    ->action(fn ( ) => 1)
//                Action::make('delete')
//                    ->color('danger')
//                    ->icon(Heroicon::Trash)
//                    ->modalIcon(Heroicon::OutlinedTrash)
//                    ->modalHeading('Delete Product')
//                    ->requiresConfirmation()
//                    ->action(function ($livewire) {
//                        $party = $this->party;
//                        $party->participants()->detach(Auth::id());
//                        $party->save();
//                        $livewire->takesPart = false;
//                        Notification::make()
//                            ->title(    'Leaved successful.')
//                            ->success()
//                            ->send();
//                    })

            ])
            ->headerActions([
            ])
        ;
    }

    public function render(): View
    {
        return view('livewire.dashboard-component.participants');
    }

    public function buildArray()
    {
        $tableData = [];

        if ($this->party->participants) {
            foreach ($this->party->participants as $val) {
                if ($val->id === Auth::id()) {
                    $this->takesPart = true;
                }

                $data = $val->toArray();
                $tableData[] = [
                    'name' => $val->name,
                    'start' => $val->pivot->start_day,
                    'end' => $val->pivot->end_day,
                ];
            }
        }



        return $tableData;
    }

    public function takePart($assign)
    {
        $party = $this->party;
        $assign
            ? $party->participants()->attach(Auth::id(), ['start_day' => $party->start_date, 'end_day' => $party->end_date])
            : $party->participants()->detach(Auth::id());
        $party->save();
        if ($assign) {
            $this->takesPart = true;
            Notification::make()
                ->title('Successful assigned.')
                ->success()
                ->send();
        }
        else
        {
            $this->takesPart = false;
            Notification::make()
                ->title(    'Leaved successful.')
                ->success()
                ->send();


        }
    }
}
