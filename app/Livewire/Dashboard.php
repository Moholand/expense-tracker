<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public string $search = '';
    public string $categoryFilter = '';
    public string $startDate = '';
    public string $endDate = '';

    public function render()
    {
        $userId = auth()->id();

        return view('livewire.dashboard', [
            'total'      => $this->getTotalUserExpenses($userId),
            'expenses'   => $this->getFilteredExpenses($userId),
            'categories' => $this->getAllCategories(),
        ])->layout('layouts.app', ['header' => 'Dashboard']);
    }

    private function getFilteredExpenses($userId): Collection
    {
        return Expense::query()
            ->where('user_id', $userId)
            ->when($this->search, fn ($query) =>
                $query->where('description', 'like', '%' . $this->search . '%')
            )
            ->when($this->categoryFilter, fn ($query) =>
                $query->where('category_id', $this->categoryFilter)
            )
            ->when($this->startDate, fn ($query) =>
                $query->whereDate('date', '>=', $this->startDate)
            )
            ->when($this->endDate, fn ($query) =>
                $query->whereDate('date', '<=', $this->endDate)
            )
            ->latest()
            ->get();
    }

    private function getTotalUserExpenses($userId): int
    {
        return Expense::where('user_id', $userId)->sum('amount');
    }

    private function getAllCategories(): Collection
    {
        return Category::all();
    }
}
