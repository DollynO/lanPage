<?php

namespace App\Http\Livewire\table\detail;

use App\Models\User;

class UserDetail extends Detail
{
    public $user;
    public $changePassword = false;

    protected $rules =[
        'user.id' => 'sometimes|required|exists:users,id',
        'user.name' => 'required|string',
    ];

    public function mount($object){
        $this->user = $object;
    }

    public function render()
    {
        return view('livewire.table.detail.user-detail');
    }

    public function delete()
    {
        $user = User::query()->whereKey($this->user['id'])->first();
        $user->delete();
    }

    public function save()
    {
        $validatedData = $this->validate();

        $user = User::query()->whereKey($this->user['id'])->first();
        $user->name = $validatedData['user']['name'];
        $user->save();
        $this->user = $user->toArray();
        $this->inEditState = false;
    }
}
