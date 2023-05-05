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

    public $edit;
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


    /**
     * removes the current user from the meal.
     * @return void
     */
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

    /**
     * Adds a meal to the selected row.
     * @return void
     */
    public function addMeal()
    {

        $this->validate([
            'edit.name' => [
                'required',
                'string',
                'max:200',
                Rule::unique(Recipe::class, 'name')->ignore($this->editRecipe, 'id'),
            ],
            'editRecipe' => [
                'nullable',
                'integer',
                Rule::exists(Recipe::class, 'id'),
                Rule::unique(Meal::class, 'recipe_id')
                    ->where('party_id', $this->party['id']),
            ],
        ]);

        $recipe = Recipe::query()->whereKey($this->editRecipe)->firstOrNew();
        $recipe->name = $this->edit['name'];
        $recipe->description = $this->edit['description'] ?? '';
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

    /**
     * Fills the edit field from the selected recipe.
     * @return void
     */
    public function fillFromSelection()
    {
        if ($this->editRecipe) {
            $selectedRecipe = Recipe::query()->whereKey($this->editRecipe)->first();
            $this->edit['name'] = $selectedRecipe->name ?? '';
            $this->edit['description'] = $selectedRecipe->description ?? '';
        }
    }


    /**
     * Opens the edit overlay to edit the current selected meal.
     * @return void
     */
    public function editMeal()
    {
        $this->showOverlay = true;
        $this->editRecipe = $this->foodSchedule[$this->selectedId]['meal']['recipe_id'] ?? null;
        $this->fillFromSelection();
    }

    /**
     * Removes the meal of the selected row.
     * Does not delete the recipe.
     * @return void
     */
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
        $this->edit = [];
    }

    /**
     * Validates if a user is already assigned as a chef to the selected meal.
     * @return bool
     */
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

    /**
     * Checks if the selected row has a meal.
     * @return bool
     */
    public function selectedRowHasValidMeal(): bool
    {
        if ($this->selectedId < 0 || !array_key_exists('meal', $this->foodSchedule[$this->selectedId]) || !$this->foodSchedule[$this->selectedId]['meal']) {
            return false;
        }

        return true;
    }
}
