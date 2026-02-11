<?php
$asset = \App\Models\Asset::whereNotNull('image')->first();
if ($asset) {
    echo "Asset ID: {$asset->id}\n";
    echo "Image path in DB: {$asset->image}\n";
    echo "Full URL would be: " . asset('storage/' . $asset->image) . "\n";
    
    // Check if file exists
    $filepath = storage_path('app/public/' . $asset->image);
    echo "File path: $filepath\n";
    echo "File exists: " . (file_exists($filepath) ? 'YES' : 'NO') . "\n";
} else {
    echo "No asset with image found\n";
}
