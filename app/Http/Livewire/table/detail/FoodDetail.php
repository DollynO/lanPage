<?php

namespace App\Http\Livewire\table\detail;

use App\Models\Recipe;

class FoodDetail extends Detail
{

    public $recipe;

    protected $rules = [
        'recipe.id' => 'sometimes|required|exists:recipes,id',
        'recipe.name' => 'required|string',
        'recipe.description' => 'required|string',
    ];

    public function mount($object)
    {
        $this->recipe = $object;
    }

    public function render()
    {
        return view('livewire.table.detail.food-detail');
    }

    public function delete()
    {
        $recipe = Recipe::query()
            ->whereKey($this->recipe['id'])
            ->first();

        if ($recipe->meals()->count()){
            $this->notification([
                'title' => 'Can not delete!',
                'description' => 'Recipe is assigned to at least one meal.',
                'icon' => 'error',
                'timeout' => 5000,
            ]);
            return;
        }

        $recipe->delete();

        $this->notification([
            'title' => 'Recipe deleted.',
            'description' => 'Recipe was successful deleted.',
            'icon' => 'success',
            'timeout' => 5000,
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate();

        $recipe = Recipe::query()->whereKey($this->recipe['id'])->first();
        $recipe->name = $validatedData['recipe']['name'];
        $recipe->description = $validatedData['recipe']['description'];
        $recipe->save();
        $this->recipe = $recipe->toArray();
        $this->inEditState = false;
    }
}
