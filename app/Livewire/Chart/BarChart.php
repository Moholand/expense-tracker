<?php

namespace App\Livewire\Chart;

use Livewire\Component;
use Illuminate\View\View;

class BarChart extends Component
{
    public array $categoryBreakdown = [];

    public function render(): View
    {
        return view('livewire.charts.bar-chart');
    }
}
