<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Category;

class CreateExpense extends Component
{
    public $date;
    public $category_id;
    public $amount;
    public $description;

    protected function rules()
    {
        return [
            'date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Expense::create([
            'date' => $this->date,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'description' => $this->description,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Expense successfully created.');
    }

    public function render()
    {
        return view('livewire.expenses.create-expense', [
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.app');;
    }
}

