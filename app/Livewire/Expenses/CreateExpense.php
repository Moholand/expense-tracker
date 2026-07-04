<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CreateExpense extends Component
{
    public string $date;
    public int $category_id;
    public int $amount;
    public string $description;

    protected function rules(): array
    {
        return [
            'date'        => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $this->createExpense();

        return redirect()->route('dashboard')->with('success', 'Expense successfully created!');
    }

    public function render()
    {
        $data = ['categories' => $this->getCategories()];

        return view('livewire.expenses.create-expense', $data)->layout('layouts.app');
    }

    private function createExpense(): void
    {
        Expense::create([
            'user_id'     => auth()->id(),
            'date'        => $this->date,
            'category_id' => $this->category_id,
            'amount'      => $this->amount,
            'description' => $this->description,
        ]);
    }

    private function getCategories(): Collection
    {
        return Category::orderBy('name')->get();
    }
}
