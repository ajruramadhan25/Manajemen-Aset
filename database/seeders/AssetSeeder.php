<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $statuses = ['available', 'deployed', 'maintenance', 'broken'];

        foreach (range(1, 20) as $index) {
            Asset::create([
                'name' => 'Asset ' . $index,
                'asset_code' => 'AST-' . strtoupper(Str::random(5)),
                'category_id' => $categories->random()->id,
                'status' => $statuses[array_rand($statuses)],
                'purchase_date' => now()->subDays(rand(1, 365)),
                'price' => rand(1000000, 50000000),
                'image' => null, // Or provide a path to a default image if available
            ]);
        }
    }
}
