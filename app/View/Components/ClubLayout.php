<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ClubLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.club');
    }
}
