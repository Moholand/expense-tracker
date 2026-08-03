<x-guest-layout>
    <div class="text-center py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Expense Tracker</h1>
        <p class="text-gray-500 mb-8">Track your spending with ease.</p>

        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}"
               class="w-full text-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                Log in
            </a>
            <a href="{{ route('register') }}"
               class="w-full text-center px-4 py-2 bg-white text-indigo-600 font-medium rounded-lg border border-indigo-600 hover:bg-indigo-50 transition">
                Register
            </a>
        </div>
    </div>
</x-guest-layout>
