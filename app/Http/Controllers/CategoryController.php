<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Category::with('children')->withCount('assets');

        if (request('search')) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhereHas('children', function ($child) use ($searchTerm) {
                      $child->where('name', 'like', $searchTerm);
                  });
            });
        }

        // Show only parent categories
        $categories = $query->whereNull('parent_category_id')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_category_id')->get();
        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_category_id')->where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->assets()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh aset.');
        }

        if ($category->children()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena memiliki subcategory.');
        }

        $category->forceDelete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

}
