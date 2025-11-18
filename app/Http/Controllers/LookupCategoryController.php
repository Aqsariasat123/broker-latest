<?php
// app/Http/Controllers/LookupCategoryController.php

namespace App\Http\Controllers;

use App\Models\LookupCategory;
use App\Models\LookupValue;
use Illuminate\Http\Request;

class LookupCategoryController extends Controller
{
    public function index()
    {
        $categories = LookupCategory::with('values')->get();
        return view('lookups.index', compact('categories'));
    }

    public function create()
    {
        return view('lookups.create-category');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean'
        ]);

        LookupCategory::create($request->all());

        return redirect()->route('lookups.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(LookupCategory $lookupCategory)
    {
        return view('lookups.edit-category', compact('lookupCategory'));
    }

    public function update(Request $request, LookupCategory $lookupCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean'
        ]);

        $lookupCategory->update($request->all());

        return redirect()->route('lookups.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(LookupCategory $lookupCategory)
    {
        $lookupCategory->delete();

        return redirect()->route('lookups.index')
            ->with('success', 'Category deleted successfully.');
    }
}