<div class="max-w-3xl create-expense">
    <h1 class="text-2xl font-semibold mb-1">Add New Expense</h1>
    <p class="text-gray-500 mb-6">Record a new expense transaction</p>

    <form wire:submit.prevent="save" class="bg-white rounded-lg shadow p-6 space-y-5">

        {{-- Date --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar" aria-hidden="true">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
                Date
            </label>
            <input type="date" wire:model="date" class="w-full rounded border-gray-300">
            @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Category --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag" aria-hidden="true">
                    <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path>
                    <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                </svg>
                Category
            </label>
            <div class="flex gap-2">
                <select wire:model="category_id" class="w-full rounded border-gray-300">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('categories') }}"
                   class="inline-flex items-center gap-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus" aria-hidden="true">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    New
                </a>
            </div>
            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Amount --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign" aria-hidden="true">
                    <line x1="12" x2="12" y1="2" y2="22"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Amount
            </label>
            <input type="number" wire:model="amount" class="w-full rounded border-gray-300" placeholder="0">
            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="flex items-center gap-2 text-sm font-medium mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text" aria-hidden="true">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                    <path d="M10 9H8"></path>
                    <path d="M16 13H8"></path>
                    <path d="M16 17H8"></path>
                </svg>
                Description <span class="text-gray-400 font-normal">(optional)</span>
            </label>
            <textarea wire:model="description" rows="4" class="w-full rounded border-gray-300" placeholder="Add notes or details...">
            </textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 text-white rounded-lg font-medium" style="background-color: oklch(0.511 0.262 276.966);">
                Save Expense
            </button>

            <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
</div>
