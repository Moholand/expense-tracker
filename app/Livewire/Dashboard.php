<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    const PAGINATE = 10;

    public string $search = '';
    public string $categoryFilter = '';
    public string $startDate = '';
    public string $endDate = '';

    public bool $showModal = false;
    public ?int $editingExpenseId = null;
    public string $date = '';
    public int $category_id = 0;
    public int $amount = 0;
    public string $description = '';

    private array $colors = [
        1 => ['bg' => 'oklch(0.954 0.038 75.164)',  'color' => 'oklch(.553 .195 38.402)'],
        2 => ['bg' => 'oklch(0.932 0.032 255.585)', 'color' => 'oklch(.488 .243 264.376)'],
        3 => ['bg' => 'oklch(0.946 0.033 307.174)', 'color' => 'oklch(.496 .265 301.924)'],
        4 => ['bg' => 'oklch(0.948 0.028 342.258)', 'color' => 'oklch(.525 .223 3.958)'],
        5 => ['bg' => 'oklch(0.973 0.071 103.193)', 'color' => 'oklch(.554 .135 66.442)'],
        6 => ['bg' => 'oklch(0.962 0.044 156.743)', 'color' => 'oklch(.527 .154 150.069)'],
    ];

    public function render()
    {
        $userId = auth()->id();

        return view('livewire.dashboard', [
            'total'      => $this->getTotalUserExpenses($userId),
            'expenses'   => $this->getFilteredExpenses($userId),
            'categories' => $this->getAllCategories(),
        ])->layout('layouts.app', ['header' => 'Dashboard']);
    }

    public function getCategoryStyles(int $categoryId): array
    {
        $colors = $this->colors;

        // Handle cyclic mapping using modulo operator
        $index = (($categoryId - 1) % count($colors)) + 1;

        return $colors[$index];
    }

    private function getFilteredExpenses(int $userId): LengthAwarePaginator
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
            ->paginate(self::PAGINATE);
    }

    private function getTotalUserExpenses(int $userId): int
    {
        return Expense::where('user_id', $userId)->sum('amount');
    }

    public function rules(): array
    {
        return [
            'date'        => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'date'        => 'date',
            'category_id' => 'category',
            'amount'      => 'amount',
            'description' => 'description',
        ];
    }

    public function editExpense(int $id): void
    {
        $expense = Expense::where('user_id', auth()->id())->findOrFail($id);

        $this->editingExpenseId = $expense->id;
        $this->date = $expense->date->format('Y-m-d');
        $this->category_id = $expense->category_id;
        $this->amount = $expense->amount;
        $this->description = $expense->description ?? '';
        $this->showModal = true;
    }

    public function updateExpense(): void
    {
        $validated = $this->validate();

        Expense::query()
            ->where('user_id', auth()->id())
            ->findOrFail($this->editingExpenseId)
            ->update($validated);

        $this->closeModal();
        session()->flash('success', 'Expense updated successfully.');
    }

    public function deleteExpense(int $id): void
    {
        Expense::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('success', 'Expense deleted successfully.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingExpenseId = null;
        $this->date = '';
        $this->category_id = 0;
        $this->amount = 0;
        $this->description = '';
        $this->resetValidation();
    }

    private function getAllCategories(): Collection
    {
        return Category::all();
    }
}
