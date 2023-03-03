<?php

namespace App\Http\Livewire;
class Column
{
    public string $component = 'columns.column';
    public array $livewireParams;

    public string $key;

    public string $label;

    public string $filterType;
    public bool $isLivewire = false;

    public function __construct($key, $label)
    {
        $this->key = $key;
        $this->label = $label;
    }


    public static function make($key, $label)
    {
        return new static($key, $label);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

    public function livewire($params = [])
    {
        $this->isLivewire = true;
        $this->livewireParams = $params;

        return $this;
    }

    public function filter($filterType, )
    {

    }
}

