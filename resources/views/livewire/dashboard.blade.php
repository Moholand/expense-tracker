<div class="dashboard">
    <h1 class="title">All Expenses</h1>
    <div class="description">Track and manage your spending</div>

    @if (session()->has('success'))
        <div class="alert-success" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            {{ session('success') }}
        </div>
    @endif
    <div class="statistics">
        <div class="total box">
            <div class="total-expenses">
                <p class="mb-2">Total Expenses</p>
                <p class="sum">{{ number_format($total) }} Toman</p>
            </div>
            <div class="dollar-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign w-6 h-6 text-indigo-600" aria-hidden="true">
                    <line x1="12" x2="12" y1="2" y2="22"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
        <div class="transactions box">
            <div class="total-transactions">
                <p class="mb-2">Transactions</p>
                <p class="sum">{{ $expenses->count() }}</p>
            </div>
            <div class="dollar-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt w-6 h-6 text-emerald-600" aria-hidden="true">
                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"></path>
                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                    <path d="M12 17.5v-11"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="filter-card">
        <div class="filter-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            <span>Filters</span>
        </div>
        <div class="filter-inputs">
            <div class="filter-input-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="filter-input-icon">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" wire:model.live.debounce.1s="search" placeholder="Search description..." class="filter-input" />
            </div>
            <select wire:model.live="categoryFilter" class="filter-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="startDate" class="filter-date" placeholder="mm/dd/yyyy" />
            <input type="date" wire:model.live="endDate" class="filter-date" placeholder="mm/dd/yyyy" />
        </div>
    </div>
    <table class="expense-list" wire:loading.class="table-loading">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount (Toman)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                @php
                    $styles = $this->getCategoryStyles($expense->category_id);
                @endphp
                <tr wire:key="expense-{{ $expense->id }}">
                    <td>{{ $expense->date->format('M d, Y') }}</td>
                    <td class="category-badge">
                        <span style="background-color: {{ $styles['bg'] }}; color: {{ $styles['color'] }}">
                            {{ $expense->category->name }}
                        </span>
                    </td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ number_format($expense->amount) }}</td>
                    <td>
                        <button class="edit-expense" wire:click="editExpense({{ $expense->id }})" title="edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil w-4 h-4" aria-hidden="true">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                <path d="m15 5 4 4"></path>
                            </svg>
                        </button>
                        <button class="delete-expense" wire:click="deleteExpense({{ $expense->id }})" wire:confirm="Are you sure you want to delete this expense?" title="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 w-4 h-4" aria-hidden="true">
                                <path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                <path d="M3 6h18"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan='5'>
                    <div class='table-footer'>
                        <div class='footer-left'>
                            Showing
                            {{ $expenses->firstItem() }}
                            to
                            {{ $expenses->lastItem() }}
                            of
                            {{ $expenses->total() }}
                            expenses
                        </div>

                        <div class="footer-right">
                            <button 
                                wire:click="previousPage"
                                wire:loading.attr="disabled"
                                wire:target="previousPage,nextPage"
                                @disabled($expenses->onFirstPage())
                            >
                                ‹
                            </button>

                            <span wire:loading.remove wire:target="previousPage,nextPage">
                                Page {{ $expenses->currentPage() }} of {{ $expenses->lastPage() }}
                            </span>

                            <span wire:loading wire:target="previousPage,nextPage">
                                Loading...
                            </span>

                            <button 
                                wire:click="nextPage"
                                wire:loading.attr="disabled"
                                wire:target="previousPage,nextPage"
                                @disabled(!$expenses->hasMorePages())
                            >
                                ›
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

    @if($showModal)
        <div class="modal-overlay" wire:click="closeModal">
            <div class="modal-content" wire:click.stop>
                <div class="modal-header">
                    <h2>Edit Expense</h2>
                    <button class="modal-close" wire:click="closeModal">&times;</button>
                </div>
                <form wire:submit.prevent="updateExpense">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-date">Date</label>
                            <input type="date" id="edit-date" wire:model="date" class="form-input">
                            @error('date') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit-category">Category</label>
                            <select id="edit-category" wire:model="categoryId" class="form-input">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit-amount">Amount (Toman)</label>
                            <input type="number" id="edit-amount" wire:model="amount" class="form-input" placeholder="0">
                            @error('amount') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit-description">Description</label>
                            <textarea id="edit-description" wire:model="description" rows="3" class="form-input" placeholder="Add notes or details..."></textarea>
                            @error('description') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn-submit">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
