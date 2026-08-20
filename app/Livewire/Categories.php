<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Categories extends Component
{
    public bool $showModal = false;
    public ?int $editingCategoryId = null;
    public string $name = '';
    public string $description = '';
    public string $color = 'gray';
    public array $colors;
    public array $colorOptions;

    public function boot(): void
    {
        $this->colors = array_keys(config('categories.colors'));
        $this->colorOptions = config('categories.colors');
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $categories = $this->loadCategories(
            $this->getCategories()
        );

        return view('livewire.categories', compact('categories'));
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:30',
            'description' => 'required|string',
            'color'       => 'required|string|in:' . implode(',', $this->colors),
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name'        => 'category name',
            'description' => 'description',
            'color'       => 'color',
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingCategoryId = null;
        $this->name = '';
        $this->description = '';
        $this->color = 'gray';
        $this->resetValidation();
    }

    public function createCategory(): void
    {
        $validated = $this->validate();

        Category::create($validated);

        $this->closeModal();
        session()->flash('success', 'Category created successfully.');
    }

    public function editCategory(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->color = $category->color;
        $this->showModal = true;
    }

    public function updateCategory(): void
    {
        Category::findOrFail($this->editingCategoryId)->update($this->validate());

        $this->closeModal();
        session()->flash('success', 'Category updated successfully.');
    }

    public function deleteCategory(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Category deleted successfully.');
    }

    private function getCategories(): Collection
    {
        return Category::query()
            ->withCount('expenses')
            ->withSum('expenses', 'amount')
            ->get();
    }

    private function loadCategories(Collection $categories): Collection
    {
        foreach ($categories as $category) {
            $category->setAttribute('styles', $category->getStyles());
        }
        return $categories;
    }
}
