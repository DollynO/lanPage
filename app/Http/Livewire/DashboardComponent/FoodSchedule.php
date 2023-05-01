<?php

namespace App\Http\Livewire\DashboardComponent;

use App\Models\Meal;
use App\Models\Party;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FoodSchedule extends Component
{
    public $party;
    public $partyId;
    public $foodSchedule = [];
    public $selectedId = -1;

    public $showOverlay = false;

    public string $editDishName;
    public string $editDishDescription;
    public $editRecipe;
    public string $selectedDateTime;

    public array $editRules = [
    ];

    public function mount($party)
    {
        $this->partyId = $party->id;
    }

    public function collectData()
    {
        $this->party = Party::find($this->partyId);
        $days = (strtotime($this->party->end_date) - strtotime($this->party->start_date)) / (60 * 60 * 24) + 1;
        $this->foodSchedule = [];
        for ($i = 0; $i < $days; $i++) {
            $this->foodSchedule[] = [
                'id' => 2 * $i,
                'date' => date('Y-m-d', strtotime($this->party->start_date . ' + ' . $i . ' days')),
                'isLunch' => true,
                'meal' => $this->getMealToDate(date('Y-m-d', strtotime($this->party->start_date . ' + ' . $i . ' days')), true)
            ];
            $this->foodSchedule[] = [
                'id' => 1 + 2 * $i,
                'date' => date('Y-m-d', strtotime($this->party->start_date . ' + ' . $i . ' days')),
                'isLunch' => false,
                'meal' => $this->getMealToDate(date('Y-m-d', strtotime($this->party->start_date . ' + ' . $i . ' days')), false)
            ];
        }
    }

    public function render()
    {
        $this->collectData();
        return view('livewire.dashboard-component.food-schedule');
    }

    public function select($id)
    {
        if ($id === $this->selectedId) {
            $this->selectedId = -1;
            $this->selectedDateTime = '';
        } else {
            $this->selectedId = $id;
            $this->selectedDateTime = $this->foodSchedule[$id]['date'] . ' ' . ($this->foodSchedule[$id]['isLunch'] ? __('Lunch') : __('Dinner'));
        }

    }

    public function getMealToDate(string $date, bool $isLunch): Meal|null
    {
        foreach ($this->party->meals as $meal) {
            if (date('Y-m-d', strtotime($meal->date)) === $date && $meal->isLunch === $isLunch) {
                return $meal;
            }
        }

        return null;
    }


    public function assignUserToMeal()
    {
        $meal = $this->foodSchedule[$this->selectedId];
        if (!array_key_exists('meal', $meal)) {
            return;
        }

        if (!$this->SelectedRowHasValidMeal() || $this->IsUserAlreadyAssigned()) {
            return;
        }

        $selectedMeal = Meal::query()->whereKey($meal['meal']['id'])->first();
        $selectedMeal->chefs()->attach(Auth::id());
        $selectedMeal->save();
    }


    public function removeUserFromMeal()
    {
        $meal = $this->foodSchedule[$this->selectedId];

        if (!$this->SelectedRowHasValidMeal() || !$this->IsUserAlreadyAssigned()) {
            return;
        }

        $selectedMeal = Meal::query()->whereKey($meal['meal']['id'])->first();
        $selectedMeal->chefs()->detach(Auth::id());
        $selectedMeal->save();
    }

    public function addMeal()
    {

        $this->validate([
        'editDishName'  => [
            'required',
            Rule::unique(Recipe::class, 'name')->ignore($this->editRecipe,'id'),
        ]]);

        if ($this->editRecipe) {
            $recipe = Recipe::query()->whereKey($this->editRecipe)->first();
        } else {
            $recipe = new Recipe();
        }
        $recipe->name = $this->editDishName;
        $recipe->description = $this->editDishDescription;
        $recipe->save();

        $selectedMealId = $this->foodSchedule[$this->selectedId]['meal']['id'] ?? null;
        $selectedMeal = Meal::query()->whereKey($selectedMealId)->firstOrNew();
        $selectedMeal->recipe_id = $recipe->id;
        $selectedMeal->party_id = $this->party['id'];
        $selectedMeal->date = $this->foodSchedule[$this->selectedId]['date'];
        $selectedMeal->isLunch = $this->foodSchedule[$this->selectedId]['isLunch'];
        $selectedMeal->save();
        $selectedMeal->chefs()->sync(Auth::id());
        $selectedMeal->save();
        $this->showOverlay = false;
    }

    public function fillFromSelection()
    {
        if ($this->editRecipe) {
            $selectedRecipe = Recipe::query()->whereKey($this->editRecipe)->first();
            $this->editDishName = $selectedRecipe->name ?? '';
            $this->editDishDescription = $selectedRecipe->description ?? '';
        }
    }

    public function editMeal()
    {
        $this->showOverlay = true;
        $this->editRecipe = $this->foodSchedule[$this->selectedId]['meal']['recipe_id'] ?? null;
        $this->fillFromSelection();
    }

    public function removeMeal()
    {
        $selectedMeal = Meal::query()->whereKey($this->foodSchedule[$this->selectedId]['meal']['id'])->first();
        $selectedMeal->chefs()->detach();
        $selectedMeal->delete();
    }

    /**
     * Resets the edit field for food.
     * @return void
     */
    public function resetEditFields()
    {
        $this->editRecipe = null;
        $this->editDishDescription = '';
        $this->editDishName = '';
    }

    public function isUserAlreadyAssigned(): bool
    {
        if ($this->selectedId < 0) {
            return false;
        }

        foreach ($this->foodSchedule[$this->selectedId]['meal']['chefs'] as $value) {
            if ($value['id'] === Auth::id()) {
                return true;
            }
        }

        return false;
    }

    public function selectedRowHasValidMeal(): bool
    {
        if ($this->selectedId < 0 || !array_key_exists('meal', $this->foodSchedule[$this->selectedId]) || !$this->foodSchedule[$this->selectedId]['meal']) {
            return false;
        }

        return true;
    }
}
