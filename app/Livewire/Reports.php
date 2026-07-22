<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Reports extends Component
{
    public string $startDate = '';
    public string $endDate = '';
    public array $selectedCategories = [];

    public function boot(): void
    {
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function render()
    {
        $userId = auth()->id();

        return view('livewire.reports', [
            'categories'        => $this->getCategories(),
            'totalExpenses'     => $this->getTotalExpenses($userId),
            'transactionCount'  => $this->getTransactionCount($userId),
            'averageWeekly'     => $this->getAverageWeekly($userId),
        ])->layout('layouts.app');
    }

    public function generateReport(): void
    {
        // Trigger re-render with current filters
    }

    public function toggleCategory(int $categoryId): void
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_values(array_diff($this->selectedCategories, [$categoryId]));
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }

    private function getBaseQuery(int $userId)
    {
        return Expense::query()
            ->where('user_id', $userId)
            ->whereDate('date', '>=', $this->startDate)
            ->whereDate('date', '<=', $this->endDate)
            ->when(!empty($this->selectedCategories), fn (Builder $query) =>
                $query->whereIn('category_id', $this->selectedCategories)
            );
    }

    private function getTotalExpenses(int $userId): int
    {
        return $this->getBaseQuery($userId)->sum('amount');
    }

    private function getTransactionCount(int $userId): int
    {
        return $this->getBaseQuery($userId)->count();
    }

    private function getAverageWeekly(int $userId): float
    {
        $total = $this->getTotalExpenses($userId);
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);
        $weeks = max(1, $start->diffInWeeks($end) ?: 1);

        return round($total / $weeks);
    }

    private function getCategories(): Collection
    {
        return Category::all();
    }
}
