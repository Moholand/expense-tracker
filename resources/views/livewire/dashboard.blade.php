<div class="dashboard">
    <h1 class="title">All Expenses</h1>
    <div class="description">Track and manage your spending</div>
    <div class="statistics">
        <div class="total box">div1</div>
        <div class="transactions box">div2</div>
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
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->date }}</td>
                    <td>{{ $expense->category->name }}</td>
                    <td>${{ $expense->amount }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>
                        <button wire:click="editExpense({{ $expense->id }})">Edit</button>
                        <button wire:click="deleteExpense({{ $expense->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
