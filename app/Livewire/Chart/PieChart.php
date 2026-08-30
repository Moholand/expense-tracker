<?php

namespace App\Livewire\Chart;

use Livewire\Component;
use Illuminate\View\View;

class PieChart extends Component
{
    public array $categoryBreakdown = [];

    public function render(): View
    {
        return view('livewire.charts.pie-chart');
    }
}
