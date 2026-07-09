<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Categories extends Component
{
    public function render()
    {
        return view('livewire.categories', [
            'categories' => $this->getCategories(),
        ])->layout('layouts.app');
    }

    private function getCategories(): Collection
    {
        return Category::withCount('expenses')
            ->withSum('expenses', 'amount')
            ->get();
    }
}
