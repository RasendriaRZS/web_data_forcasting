<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        // Ambil status dari query string
        $status = $request->get('status');

        // Jika status dipilih, filter data berdasarkan status
        if ($status) {
            $assets = Asset::where('status', $status)->get();
        } else {
            // Ambil semua data jika tidak ada filter
            $assets = Asset::all();
        }

         // Mengambil model yang berstatus "Maintenance"
         $maintenanceModels = Asset::where('status', 'Maintenance')->get();


        return view('assets.index', compact('assets', 'maintenanceModels'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        // Validasi input termasuk status
        $request->validate([
            'serial_number' => 'required|unique:assets',
            'name' => 'required',
            'model' => 'required',
            'status' => 'required', // Validasi status
            'purchase_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Simpan data ke database termasuk status
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
        // Validasi input termasuk status
        $request->validate([
            'serial_number' => 'required|unique:assets,serial_number,' . $asset->id,
            'name' => 'required',
            'model' => 'required',
            'status' => 'required', // Validasi status
            'purchase_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Update data termasuk status
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
