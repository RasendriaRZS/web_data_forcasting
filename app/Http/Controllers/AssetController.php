<?php

namespace App\Http\Controllers;


use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\AssetMaster;

class AssetController extends Controller
{
    public function index(Request $request)
    {

        $assets = Asset::with('transactions')->latest()->get();

        // Ambil status dari query string
        $status = $request->get('status');

        $search = $request->get('search');

        $query = Asset::query();

        // Jika status dipilih, filter data berdasarkan status
        if ($status) {
            $assets = Asset::where('status', $status)->get();
        } else {
            // Ambil semua data jika tidak ada filter
            $assets = Asset::all();
        }


        if ($status) {
            $query->where('status', $status);
        }


        // serach bar 
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%");
            });
        }
        
            
        $assets = $query->orderBy('updated_at', 'desc')->get();
        

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
        $request->validate([
            'serial_number' => 'required|unique:assets',
            'name' => 'required',
            'model' => 'required',
            'status' => 'required',
            'purchase_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
    
        // Simpan ke tabel assets (data aktif)
        $asset = Asset::create($request->all());
    
        // Simpan/Update ke tabel asset_masters (data master, tidak pernah dihapus)
        AssetMaster::firstOrCreate(
            ['serial_number' => $request->serial_number],
            [
                'name' => $request->name,
                'project_name' => $request->project_name,
                'model' => $request->model,
                'status' => $request->status,
                'asset_recieved' => $request->purchase_date,
                'asset_shipped' => $request->delivery_date,
                'location' => $request->location ?? null,
                'notes' => $request->notes,
                'id_insert' => auth()->id() ?? null,
                'date_insert' => now(),
                'is_delete' => 0
            ]
        );
    
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
            'status' => 'required', 
            'project_name' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Update data termasuk status
        $asset->update($request->all());

        Transaction::create([
            'serial_number' => $asset->serial_number,
            'transaction_date' => now()->toDateString(),
            'project_name' => $request->project_name ?? 'In Warehouse',
        ]);

        AssetMaster::where('serial_number', $asset->serial_number)->update([
            'name' => $request->name,
            'project_name' => $request->project_name,
            'model' => $request->model,
            'status' => $request->status,
            'asset_recieved' => $request->purchase_date,
            'asset_shipped' => $request->delivery_date,
            'location' => $request->location,
            'notes' => $request->notes,
            'id_update' => auth()->id() ?? null,
            'date_update' => now(),
        ]);

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
