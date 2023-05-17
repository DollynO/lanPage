<?php

namespace App\Http\Livewire\table;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTable extends Table
{

    public $userTable;

    /**
     * queries the table.
     */
    public function query(): Builder
    {
        return User::query();
    }

    /**
     * creates the columns of the table.
     * @return array
     */
    public function columns(): array
    {
        return[
            Column::make('name','Name')
            ->defaultSortColumn(),
        ];
    }

    public function searchField(): string
    {
        return 'name';
    }

    public function getTableName(): string
    {
        return 'userTable';
    }

    public function new(): mixed
    {
        if (User::query()->where('name','New User')->count()){
            return null;
        }

        $user = new User();
        $user->name = "New User";
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();
        return null;
    }

    public function detailComponent(): ?string
    {
        return 'table.detail.user-detail';
    }
}
