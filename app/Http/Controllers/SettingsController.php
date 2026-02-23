<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LookupCategory;
use App\Models\LookupValue;

class SettingsController extends Controller
{
    // Settings password - can be changed here
    private const SETTINGS_PASSWORD = 'Simon2024!';

    public function index(Request $request)
    {
        // Check if already authenticated for settings
        if (!session('settings_authenticated')) {
            return view('settings.password');
        }

        $categories = LookupCategory::with(['values' => function($q) {
            $q->orderBy('seq');
        }])->orderBy('name')->get();

        return view('settings.index', compact('categories'));
    }

    public function authenticate(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password === self::SETTINGS_PASSWORD) {
            session(['settings_authenticated' => true]);
            return redirect()->route('settings.index');
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    public function logout()
    {
        session()->forget('settings_authenticated');
        return redirect()->route('settings.index');
    }

    public function updateValue(Request $request, LookupValue $lookupValue)
    {
        if (!session('settings_authenticated')) {
            return response()->json(['error' => 'Not authenticated'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $lookupValue->update([
            'name' => $request->name,
            'active' => $request->has('active') ? $request->active : $lookupValue->active,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Value updated successfully.']);
        }

        return back()->with('success', 'Value updated successfully.');
    }

    public function storeValue(Request $request)
    {
        if (!session('settings_authenticated')) {
            return response()->json(['error' => 'Not authenticated'], 403);
        }

        $request->validate([
            'lookup_category_id' => 'required|exists:lookup_categories,id',
            'name' => 'required|string|max:255',
        ]);

        $maxSeq = LookupValue::where('lookup_category_id', $request->lookup_category_id)->max('seq') ?? 0;

        LookupValue::create([
            'lookup_category_id' => $request->lookup_category_id,
            'name' => $request->name,
            'active' => 1,
            'seq' => $maxSeq + 1,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Value added successfully.']);
        }

        return back()->with('success', 'Value added successfully.');
    }
}
