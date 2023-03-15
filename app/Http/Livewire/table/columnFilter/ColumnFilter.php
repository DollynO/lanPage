<?php

namespace App\Http\Livewire\table\columnFilter;

use App\Http\Livewire\table\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

abstract class ColumnFilter
{
    // The label of the component.
    public string $label;

    // The name of the model key.
    public string $key;

    // The config array.
    public array $config;

    // filter callback
    public $filterCallback;

    public function __construct($key, $label)
    {
        $this->key = $key;
        $this->label = $label;
    }


    /**
     * @return static
     */
    public static function make(string $key, string $name): ColumnFilter
    {
        return new static($key, $name);
    }

    /**
     * checks if the config key exists.
     * @param string $configKey
     * @return bool
     */
    public function hasConfig(string $configKey) : bool
    {
        return array_key_exists($configKey, $this->config);
    }

    /**
     * sets the config of the filter.
     * @param array $config
     * @return ColumnFilter
     */
    public function setConfig(array $config) : ColumnFilter
    {
        $this->config = $config;
        return $this;
    }


    /**
     * gets the content for the config.
     * @param string $configKey
     * @return mixed
     */
    public function getConfig(string $configKey) : mixed
    {
        return $this->config[$configKey];
    }

    public function setFilterCallback($filterCallback)
    {
        $this->filterCallback = $filterCallback;
        return $this;
    }

    /**
     * renders the component.
     * @return mixed
     */
    public abstract function render(Table $table);

    abstract public static function defaultCallback();
}
