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
    public int $categoryId = 0;
    public int $amount = 0;
    public string $description = '';

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
        $category = Category::find($categoryId);
        $color = $category->color ?? 'gray';
        $styles = config('categories.color_styles');

        return $styles[$color] ?? $styles['gray'];
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
        $this->categoryId = $expense->category_id;
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
        $this->categoryId = 0;
        $this->amount = 0;
        $this->description = '';
        $this->resetValidation();
    }

    private function getAllCategories(): Collection
    {
        return Category::all();
    }
}
