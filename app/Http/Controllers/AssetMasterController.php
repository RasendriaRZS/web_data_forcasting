<?php

namespace App\Http\Controllers;

use App\Models\AssetMaster;
use Illuminate\Http\Request;
use App\Models\Asset;

class AssetMasterController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetMaster::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        $assets = $query->where('is_delete', 0)->orderBy('updated_at', 'desc')->paginate(15);

        // Untuk notifikasi maintenance
        $maintenanceModels = Asset::where('status', 'Maintenance')->get();

        // Gunakan view dengan folder index
         return view('Asset_Master', compact('assets', 'maintenanceModels'));


    }

    public function show(AssetMaster $asset_master)
    {
        // Pastikan data ditemukan, jika tidak akan otomatis 404 karena route model binding
         return view('Asset_Master', compact('assets', 'maintenanceModels'));

    }

    public function asset()
{
    return $this->hasOne(Asset::class, 'serial_number', 'serial_number');
}
}
