<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    public string $startDate = '';
    public string $endDate = '';
    public array $selectedCategories = [];
    public string $chartType = 'pie';
    public string $timePeriod = 'weekly';

    public function boot(): void
    {
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $userId = auth()->id();

        return view('livewire.reports', [
            'categories'        => $this->getCategories(),
            'totalExpenses'     => $this->getTotalExpenses($userId),
            'transactionCount'  => $this->getTransactionCount($userId),
            'averageWeekly'     => $this->getAverageWeekly($userId),
            'categoryBreakdown' => $this->getCategoryBreakdown(),
            'chartType'         => $this->chartType,
            'weeklySpending'    => $this->getWeeklySpending(),
            'monthlySpending'   => $this->getMonthlySpending(),
            'timePeriod'        => $this->timePeriod,
        ]);
    }

    public function toggleCategory(int $categoryId): void
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_values(array_diff($this->selectedCategories, [$categoryId]));
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }

    public function switchChartType(string $type): void
    {
        $this->chartType = $type === 'bar' ? 'bar' : 'pie';
    }

    public function switchTimePeriod(string $period): void
    {
        $this->timePeriod = $period === 'monthly' ? 'monthly' : 'weekly';
    }

    private function getBaseQuery(int $userId): Builder
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

    private function getWeeklySpending(): array
    {
        $userId = auth()->id();
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        $expenses = $this->getBaseQuery($userId)->get();

        $weeks = [];
        $current = $start->copy()->startOfWeek();

        while ($current->lte($end)) {
            $weekEnd = $current->copy()->endOfWeek();
            $weekLabel = $current->format('M j') . '-' . $weekEnd->format('j');

            $weekExpenses = $expenses->filter(fn ($e) =>
                Carbon::parse($e->date)->between($current, $weekEnd)
            );

            $weeks[] = [
                'week' => $weekLabel,
                'transactions' => $weekExpenses->count(),
                'total' => (float) $weekExpenses->sum('amount'),
            ];

            $current->addWeek();
        }

        return $weeks;
    }

    private function getMonthlySpending(): array
    {
        $userId = auth()->id();
        $start = Carbon::parse($this->startDate)->startOfMonth();
        $end = Carbon::parse($this->endDate)->endOfMonth();

        $expenses = $this->getBaseQuery($userId)->get();

        $months = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $monthEnd = $current->copy()->endOfMonth();
            $monthLabel = $current->format('F Y');

            $monthExpenses = $expenses->filter(fn ($e) =>
                Carbon::parse($e->date)->between($current, $monthEnd)
            );

            $months[] = [
                'month' => $monthLabel,
                'transactions' => $monthExpenses->count(),
                'total' => (float) $monthExpenses->sum('amount'),
            ];

            $current->addMonth();
        }

        return $months;
    }

    private function getCategoryBreakdown(): array
    {
        $userId = auth()->id();

        $breakdown = $this->getBaseQuery($userId)
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select('categories.name', 'categories.color')
            ->selectRaw('SUM(amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        $grandTotal = (float) $breakdown->sum('total');

        $colors  = config('categories.colors');

        return $breakdown->map(fn ($row) => [
            'name'       => $row->name,
            'amount'     => (float) $row->total,
            'percentage' => $grandTotal > 0 ? round(($row->total / $grandTotal) * 100, 1) : 0,
            'color'      => $row->color,
            'hex'        => isset($colors[$row->color]) ? $colors[$row->color]['color'] : $colors['gray']['color'],
        ])->toArray();
    }
}
