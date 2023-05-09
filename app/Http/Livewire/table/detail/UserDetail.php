<?php

namespace App\Http\Livewire\table\detail;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserDetail extends Detail
{

    public $user;

    protected $rules = [
        'user.id' => 'sometimes|required|exists:users,id',
        'user.name' => 'required|string',
    ];

    public function mount($object)
    {
        $this->user = $object;
    }

    public function render()
    {
        return view('livewire.table.detail.user-detail');
    }

    public function delete()
    {
        $user = User::query()
            ->whereKey($this->user['id'])
            ->first();

        if ($user->parties()->count()) {
            $this->notification([
                'title' => 'Can not delete!',
                'description' => 'User is assigned to at least one party.',
                'icon' => 'error',
                'timeout' => 5000,
            ]);
            return;
        }

        if ($user->meals()->count()){
            $this->notification([
                'title' => 'Can not delete!',
                'description' => 'User is assigned to at least one meal.',
                'icon' => 'error',
                'timeout' => 5000,
            ]);
            return;
        }

        $user->delete();

        $this->notification([
            'title' => 'User deleted.',
            'description' => 'User was successful deleted.',
            'icon' => 'success',
            'timeout' => 5000,
        ]);
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

    public function resetPassword()
    {
        $user = User::query()->whereKey($this->user['id'])->first();
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();
        $this->dialog()->info('New password',$password);
    }
}
