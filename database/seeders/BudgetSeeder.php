<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Club;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubs = Club::all();

        foreach ($clubs as $club) {
            // Create budget
            $budget = Budget::create([
                'club_id' => $club->id,
                'amount' => 100000, // 100,000 BDT
                'fiscal_year' => now()->year,
                'description' => 'Annual budget for ' . $club->name
            ]);

            // Add some sample transactions
            Transaction::create([
                'club_id' => $club->id,
                'budget_id' => $budget->id,
                'amount' => 5000,
                'type' => 'expense',
                'description' => 'Event expenses',
                'date' => now()->subDays(5)
            ]);

            Transaction::create([
                'club_id' => $club->id,
                'budget_id' => $budget->id,
                'amount' => 2000,
                'type' => 'expense',
                'description' => 'Office supplies',
                'date' => now()->subDays(2)
            ]);
        }
    }
}
