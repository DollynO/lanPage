<?php

namespace App\Http\Livewire\table;

class CustomButton
{

    /**
     * The display text of the button.
     * @string
     */
    public string $name;


    /**
     * The function to execute as string.
     * @string
     */
    public string $function;

    /**
     * The enable condition as alpine condition string.
     * @var
     */
    public $enableCondition;

    /**
     * Constructor of this class.
     * @param $name
     * @param $function
     */
    public function __construct($name, $function)
    {
        $this->name = $name;
        $this->function = $function;
    }

    /**
     * Static method to create an instance of a custom button.
     * @param $name
     * @param $function
     * @return static
     */
    public static function make($name, $function): static
    {
        return new static($name, $function);
    }

    /**
     * Sets the enable condition.
     * @param $condition
     * @return $this
     */
    public function enableCondition($condition) : CustomButton
    {
        $this->enableCondition = $condition;
        return $this;
    }
}
