<?php

namespace App\Modules\AppV2\Views\Components;

use Illuminate\View\Component;

class CheckboxGroup extends Component
{
    public $items;
    public $model;
    public $colorScheme;

    public function __construct($items = [], $model = '', $colorScheme = 1)
    {
        $this->items = $items;
        $this->model = $model;
        $this->colorScheme = $colorScheme;
    }

    public function render()
    {
        return view('AppV2::components.checkbox-group');
    }
} 