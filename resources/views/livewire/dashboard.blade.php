<div class="dashboard">
    <h1 class="title">All Expenses</h1>
    <div class="description">Track and manage your spending</div>
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
    <div class="search">
        <input type="text" wire:model="search" placeholder="Search expenses..." />
        <select wire:model="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <input type="date" wire:model="startDate" />
        <input type="date" wire:model="endDate" />
    </div>
    <table class="expense-list">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                @php
                    $styles = $this->getCategoryStyles($expense->category_id);
                @endphp
                <tr>
                    <td>{{ $expense->date->format('M d, Y') }}</td>
                    <td class="category-badge">
                        <span style="background-color: {{ $styles['bg'] }}; color: {{ $styles['color'] }}">
                            {{ $expense->category->name }}
                        </span>
                    </td>
                    <td>{{ $expense->description }}</td>
                    <td>${{ $expense->amount }}</td>
                    <td>
                        <button wire:click="editExpense({{ $expense->id }})">Edit</button>
                        <button wire:click="deleteExpense({{ $expense->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
