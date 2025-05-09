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

        // Tambahkan ini untuk mengirim data maintenance ke view
        $maintenanceModels = Asset::where('status', 'Maintenance')->get();

        return view('Asset_Master', compact('assets', 'maintenanceModels'));
    }
}
