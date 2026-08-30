<?php

namespace App\Livewire\Chart;

use Livewire\Component;
use Illuminate\View\View;

class LineChart extends Component
{
    public array $spendingData = [];
    public string $timePeriod = 'weekly';

    public function render(): View
    {
        return view('livewire.charts.line-chart');
    }
}
