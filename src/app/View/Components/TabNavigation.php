<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TabNavigation extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('components.tab-navigation');
    }
} 