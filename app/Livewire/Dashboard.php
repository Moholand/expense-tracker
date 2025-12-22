<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Livewire\Component;

class Dashboard extends Component
{
    public $search = '';
    public $categoryFilter = '';
    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $expenses = Expense::query()
            ->when($this->search, fn($query) => $query->where('description', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->startDate, fn($query) => $query->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn($query) => $query->whereDate('date', '<=', $this->endDate))
            ->get();

        $total = Expense::query()
            ->where('user_id', auth()->user()->id)
            ->sum('amount');

        return view('livewire.dashboard', [
            'expenses' => $expenses,
            'categories' => Category::all(),
            'total' => $total
        ])
        ->layout('layouts.app', ['header' => 'Dashboard']);
    }
}
