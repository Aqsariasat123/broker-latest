<?php
// app/Http/Controllers/LookupValueController.php

namespace App\Http\Controllers;

use App\Models\LookupCategory;
use App\Models\LookupValue;
use Illuminate\Http\Request;

class LookupValueController extends Controller
{
    public function create(LookupCategory $lookupCategory)
    {
        return view('lookups.create-value', compact('lookupCategory'));
    }

    public function store(Request $request, LookupCategory $lookupCategory)
    {
        $request->validate([
            'seq' => 'required|integer',
            'name' => 'required|string|max:255',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'code' => 'nullable|string'
        ]);

        $lookupCategory->values()->create($request->all());

        return redirect()->route('lookups.index')
            ->with('success', 'Lookup value created successfully.');
    }

    public function edit(LookupValue $lookupValue)
    {
        return view('lookups.edit-value', compact('lookupValue'));
    }

    public function update(Request $request, LookupValue $lookupValue)
    {
        $request->validate([
            'seq' => 'required|integer',
            'name' => 'required|string|max:255',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'code' => 'nullable|string'
        ]);

        $lookupValue->update($request->all());

        return redirect()->route('lookups.index')
            ->with('success', 'Lookup value updated successfully.');
    }

    public function destroy(LookupValue $lookupValue)
    {
        $lookupValue->delete();

        return redirect()->route('lookups.index')
            ->with('success', 'Lookup value deleted successfully.');
    }
}