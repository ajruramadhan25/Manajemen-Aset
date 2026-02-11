<?php
echo "=== All Categories ===\n";
$categories = \App\Models\Category::all()->sortBy('id');
foreach ($categories as $cat) {
    $parent = $cat->parent_category_id ? " (Parent: {$cat->parent_category_id})" : " (Parent)";
    echo "ID: {$cat->id} | {$cat->name}{$parent}\n";
}
