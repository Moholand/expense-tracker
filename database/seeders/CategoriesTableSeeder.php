<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Food & Drinks', 'description' => 'Expenses related to groceries, restaurants, cafes, and drinks.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transportation', 'description' => 'Costs for commuting, fuel, parking, or public transit.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Utilities', 'description' => 'Monthly bills such as electricity, water, gas, and internet.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rent/Mortgage', 'description' => 'Housing expenses, including rent or mortgage payments.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entertainment', 'description' => 'Expenses for movies, concerts, subscriptions, or hobbies.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Health & Fitness', 'description' => 'Medical bills, gym memberships, or wellness activities.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shopping', 'description' => 'Purchases such as clothes, accessories, or electronics.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Education', 'description' => 'Tuition fees, books, courses, or study materials.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Travel', 'description' => 'Vacation expenses, flights, hotels, or sightseeing.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Insurance', 'description' => 'Payments for health, car, life, or home insurance.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Savings', 'description' => 'Contributions to savings accounts or investments.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Miscellaneous', 'description' => 'Other expenses that don’t fit into specific categories.', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
