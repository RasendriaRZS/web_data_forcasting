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
        
        // ambil data dari assets
        $query = DB::table('assets')
            ->select(
                DB::raw('YEAR(purchase_date) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year')
            ->orderBy('year');

        // jika ada tahun awal dan tahun awal gak kosong maka ambil dari tahun awal
        if ($request->has('start_year') && $request->start_year) {
            $query->where(DB::raw('YEAR(purchase_date)'), '>=', $request->start_year);
        }

        if ($request->has('end_year') && $request->end_year) {
            $query->where(DB::raw('YEAR(purchase_date)'), '<=', $request->end_year);
        }

        $data = $query->get()
            ->map(function ($item) {
                return [
                    'year' => (int)$item->year,
                    'value' => $item->count
                ];
            });

        // ngitung future projections dari data yang udah diambil
        $probabilities = collect();
        if ($data->count() >= 2) {
            $lastTwoYears = $data->sortByDesc('year')->take(2);
            $growthRate = ($lastTwoYears->first()['value'] - $lastTwoYears->last()['value']) / $lastTwoYears->last()['value'];
            
            $lastYear = $data->max('year');
            for ($i = 1; $i <= 4; $i++) {
                $year = $lastYear + $i;
                $value = $data->last()['value'] * (1 + $growthRate);
                $probabilities->push([
                    'year' => $year,
                    'value' => round($value, 2)
                ]);
            }
        }

        // dropdown filter untuk tahun
        $years = $data->pluck('year')
            ->merge($probabilities->pluck('year'))
            ->unique()
            ->sort();

        // ambil data dari maintenance models
        $maintenanceModels = Asset::where('status', 'Maintenance')->get();

        return view('analytics', compact('data', 'probabilities', 'years', 'maintenanceModels'));
    }
}
