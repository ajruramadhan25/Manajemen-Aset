<?php
$brands = [
    ['name' => 'ASUS', 'description' => 'Komputer dan elektronik'],
    ['name' => 'Dell', 'description' => 'Laptop dan komputer desktop'],
    ['name' => 'HP', 'description' => 'Laptop dan printer'],
    ['name' => 'Sony', 'description' => 'Elektronik dan entertainment'],
    ['name' => 'Yamaha', 'description' => 'Kendaraan roda dua dan musik'],
    ['name' => 'Honda', 'description' => 'Kendaraan roda dua'],
    ['name' => 'Toyota', 'description' => 'Kendaraan roda empat'],
];

echo "Creating brands...\n";
foreach ($brands as $brand) {
    $b = \App\Models\Brand::where('name', $brand['name'])->first();
    if (!$b) {
        \App\Models\Brand::create([
            'name' => $brand['name'],
            'slug' => \Illuminate\Support\Str::slug($brand['name']),
            'description' => $brand['description'],
        ]);
        echo "✓ Created: {$brand['name']}\n";
    } else {
        echo "- Skipped (exists): {$brand['name']}\n";
    }
}

echo "\nTotal brands: " . \App\Models\Brand::count() . "\n";
