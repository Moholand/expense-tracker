<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-1">Add New Expense</h1>
    <p class="text-gray-500 mb-6">Record a new expense transaction</p>

    <form wire:submit.prevent="save" class="bg-white rounded-lg shadow p-6 space-y-5">

        {{-- Date --}}
        <div>
            <label class="block text-sm font-medium mb-1">Date</label>
            <input type="date"
                   wire:model.defer="date"
                   class="w-full rounded border-gray-300">
            @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Category --}}
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <div class="flex gap-2">
                <select wire:model.defer="category_id"
                        class="w-full rounded border-gray-300">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('categories.create') }}"
                   class="px-4 py-2 border rounded text-sm">
                    + New
                </a>
            </div>
            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Amount --}}
        <div>
            <label class="block text-sm font-medium mb-1">Amount</label>
            <input type="number"
                   step="0.01"
                   wire:model.defer="amount"
                   class="w-full rounded border-gray-300"
                   placeholder="0.00">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Description <span class="text-gray-400">(optional)</span>
            </label>
            <textarea wire:model.defer="description"
                      rows="4"
                      class="w-full rounded border-gray-300"
                      placeholder="Add notes or details..."></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded">
                Save Expense
            </button>

            <a href="{{ route('dashboard') }}"
               class="px-6 py-2 bg-gray-100 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
