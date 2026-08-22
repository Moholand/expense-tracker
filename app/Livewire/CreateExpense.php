<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

class CreateExpense extends Component
{
    public string $date;
    public int $category_id;
    public int $amount;
    public string $description;

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $categories = $this->getCategories();

        return view('livewire.create-expense', compact('categories'));
    }

    public function save()
    {
        $this->validate();

        $this->createExpense();

        return redirect()->route('dashboard')->with('success', 'Expense successfully created!');
    }

    protected function rules(): array
    {
        return [
            'date'        => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
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
        return Category::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }
}
