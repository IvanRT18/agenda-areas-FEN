<?php

namespace App\Livewire;

use Livewire\Component;

class AreaSelector extends Component
{
    public $selectedArea;

    public function render()
    {
        return view('livewire.area-selector');
    }

    public function selectArea($area)
    {
        $this->selectedArea = $area;
    }
}
