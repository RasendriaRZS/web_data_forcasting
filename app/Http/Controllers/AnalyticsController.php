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

        // Calculate ARIMA predictions
        $probabilities = $this->calculateARIMAPredictions($data);

        // dropdown filter untuk tahun
        $years = $data->pluck('year')
            ->merge($probabilities->pluck('year'))
            ->unique()
            ->sort();

        // ambil data dari maintenance models
        $maintenanceModels = Asset::where('status', 'Maintenance')->get();

        return view('analytics', compact('data', 'probabilities', 'years', 'maintenanceModels'));
    }

    private function calculateARIMAPredictions($data)
    {
        $predictions = collect();
        
        if ($data->count() < 2) {
            return $predictions;
        }

        // Convert data to array of values
        $values = $data->pluck('value')->toArray();
        
        // Calculate differences (d=1)
        $differences = [];
        for ($i = 1; $i < count($values); $i++) {
            $differences[] = $values[$i] - $values[$i - 1];
        }

        // ARIMA parameters
        $ar_coefficient = 0.6;  // AR coefficient (a)
        $ma_coefficient = 0.4;  // MA coefficient (b)
        $previous_error = 0.5;  // Previous error (eₜ₋₁)

        // Calculate predictions for next 4 periods
        $lastYear = $data->last()['year'];
        $lastValue = $data->last()['value'];
        $lastDifference = end($differences);

        for ($i = 1; $i <= 4; $i++) {
            // Calculate difference prediction
            $differencePrediction = ($ar_coefficient * $lastDifference) + ($ma_coefficient * $previous_error);
            
            // Convert back to original scale
            $predictedValue = $lastValue + $differencePrediction;
            
            // Update for next iteration
            $lastDifference = $differencePrediction;
            $lastValue = $predictedValue;
            
            $predictions->push([
                'year' => $lastYear + $i,
                'value' => round($predictedValue, 2)
            ]);
        }

        return $predictions;
    }
}