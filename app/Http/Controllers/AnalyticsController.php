<?php

namespace App\Http\Controllers;


use App\Models\Asset;
use Illuminate\Http\Request;

// buat ambit data dari table
use Illuminate\Support\Facades\DB;
 
use App\Models\History; // Import model History


class AnalyticsController extends Controller
{
    public function analytics(Request $request)
    {
        // $query = DB::table('histories');

        // // Filter berdasarkan tahun awal dan akhir
        // if ($request->has('start_year') && $request->start_year) {
        //     $query->where('year', '>=', $request->start_year);
        // }

        // if ($request->has('end_year') && $request->end_year) {
        //     $query->where('year', '<=', $request->end_year);
        // }

        // // Data historis
        // $data = $query->select('year', 'value')->get();

        // // Data probabilitas (ini contoh data dummy aja ya untuk tahun ke depan)
        // $probabilities = collect([
        //     ['year' => 2024, 'value' => 4500],
        //     ['year' => 2025, 'value' => 5000],
        //     ['year' => 2026, 'value' => 5200],
        //     ['year' => 2027, 'value' => 5500],
        // ]);

        // // menggabungkan tahun untuk dropdown filter
        // $years = DB::table('histories')
        //     ->select('year')
        //     ->distinct()
        //     ->orderBy('year')
        //     ->pluck('year')
        //     ->merge($probabilities->pluck('year'));

        // return view('analytics', compact('data', 'probabilities', 'years'));


        // Ambil data historis dengan filter
        $query = DB::table('histories');

        if ($request->has('start_year') && $request->start_year) {
            $query->where('year', '>=', $request->start_year);
        }

        if ($request->has('end_year') && $request->end_year) {
            $query->where('year', '<=', $request->end_year);
        }

        $data = $query->select('year', 'value')->get();

        // Data probabilitas untuk tahun 2024-2027 (contoh dummy)
        $probabilities = collect([
            ['year' => 2024, 'value' => 4500],
            ['year' => 2025, 'value' => 5000],
            ['year' => 2026, 'value' => 5200],
            ['year' => 2027, 'value' => 5500],
        ]);

        // biar probabilities juga bisa di filter 
        $probabilities = $probabilities->filter(function ($item) use ($request) {
            return (!$request->start_year || $item['year'] >= $request->start_year) &&
                   (!$request->end_year || $item['year'] <= $request->end_year);
        });
        

        // Gabungkan tahun historis + probabilitas untuk dropdown filter
        $years = DB::table('histories')
            ->select('year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->merge($probabilities->pluck('year'))
            ->sort();

         // Mengambil model yang berstatus "Maintenance"
         $maintenanceModels = Asset::where('status', 'Maintenance')->get();

        
        return view('analytics', compact('data', 'probabilities', 'years', 'maintenanceModels'));

    }
}
