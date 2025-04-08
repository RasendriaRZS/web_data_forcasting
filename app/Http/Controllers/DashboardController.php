<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah asset berdasarkan model
        $modelCounts = Asset::selectRaw('model, count(*) as count')
                          ->groupBy('model')
                          ->pluck('count', 'model');

        // Hitung jumlah asset berdasarkan status
        $statusCounts = Asset::selectRaw('status, count(*) as count')
                           ->groupBy('status')
                           ->pluck('count', 'status');

        // Mengambil model yang berstatus "Maintenance"
        $maintenanceModels = Asset::where('status', 'Maintenance')->orderBy('created_at', 'desc')->get();

        return view('index', compact('modelCounts', 'statusCounts', 'maintenanceModels'));    
    }
}
