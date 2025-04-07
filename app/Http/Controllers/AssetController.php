<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|unique:assets',
            'name' => 'required',
            'model' => 'required',
            'purchase_date' => 'required|date',
        ]);

        Asset::create($request->all());

        return redirect()->route('assets.index')
            ->with('success', 'Asset created successfully.');
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'serial_number' => 'required|unique:assets,serial_number,'.$asset->id,
            'name' => 'required',
            'model' => 'required',
            'purchase_date' => 'required|date',
        ]);

        $asset->update($request->all());

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully');
    }
}
