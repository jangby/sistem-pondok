<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuruLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Ini akan memberitahu Laravel untuk menggunakan file layout
        // 'layouts.guru' sebagai layout master
        return view('layouts.guru');
    }
}