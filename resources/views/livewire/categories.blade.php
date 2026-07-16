<div class="categories">
    <div class="categories-header">
        <div>
            <h1 class="title">Expense Categories</h1>
            <div class="description">Manage your expense categories</div>
        </div>
        <button class="add-category-btn" wire:click="openModal">+ Add New Category</button>
    </div>

    @if (session()->has('success'))
        <div class="alert-success" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            {{ session('success') }}
        </div>
    @endif

    <table class="expense-list">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Color</th>
                <th>Total Expenses (Toman)</th>
                <th>Transactions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                @php $styles = $this->getColorStyles($category->color); @endphp
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span class="color-badge" style="background-color: {{ $styles['bg'] }}; color: {{ $styles['text'] }};">
                            {{ $category->color }}
                        </span>
                    </td>
                    <td>{{ number_format($category->expenses_sum_amount ?? 0) }}</td>
                    <td>{{ $category->expenses_count }}</td>
                    <td>
                        <button class="edit-expense" title="edit" wire:click="editCategory({{ $category->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil w-4 h-4" aria-hidden="true">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button class="delete-expense" title="delete" wire:click="deleteCategory({{ $category->id }})" wire:confirm="Are you sure you want to delete this category?">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 w-4 h-4" aria-hidden="true">
                                <path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($showModal)
        <div class="modal-overlay" wire:click="closeModal">
            <div class="modal-content" wire:click.stop>
                <div class="modal-header">
                    <h2>{{ $editingCategoryId ? 'Edit Category' : 'Create Category' }}</h2>
                    <button class="modal-close" wire:click="closeModal">&times;</button>
                </div>
                <form wire:submit.prevent="{{ $editingCategoryId ? 'updateCategory' : 'createCategory' }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" id="name" wire:model="name" class="form-input" placeholder="e.g. Food & Dining">
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" id="description" wire:model="description" class="form-input" placeholder="e.g. Groceries, restaurants, etc.">
                            @error('description') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Color</label>
                            <div class="color-picker">
                                @foreach($colors as $colorOption)
                                    @php $styles = $this->getColorStyles($colorOption); @endphp
                                    <label class="color-option {{ $color === $colorOption ? 'selected' : '' }}">
                                        <input type="radio" wire:model="color" value="{{ $colorOption }}" class="sr-only">
                                        <span class="color-swatch" style="background-color: {{ $styles['bg'] }}; border-color: {{ $styles['text'] }};">
                                            <span class="color-dot" style="background-color: {{ $styles['text'] }};"></span>
                                        </span>
                                        <span class="color-label" style="color: {{ $styles['text'] }};">{{ $colorOption }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('color') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn-submit">{{ $editingCategoryId ? 'Update Category' : 'Create Category' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
