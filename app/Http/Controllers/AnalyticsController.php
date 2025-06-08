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
    // Ambil data dari assets
    $query = DB::table('assets')
        ->select(
            DB::raw('YEAR(purchase_date) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('year')
        ->orderBy('year');

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

    // Split data into train and test (e.g., 80% train, 20% test)
    $totalCount = $data->count();
    $trainCount = (int)($totalCount * 0.8);
    $trainData = $data->slice(0, $trainCount)->values();
    $testData = $data->slice($trainCount)->values();

    // Calculate predictions on test period using train data
    $predictions = $this->calculateARIMAPredictionsForTest($trainData, $testData->count());

    // Extract actual values from test data
    $actualValues = $testData->pluck('value')->toArray();
    $predictedValues = $predictions->pluck('value')->toArray();

    // Calculate error metrics
    $mae = $this->calculateMAE($actualValues, $predictedValues);
    $rmse = $this->calculateRMSE($actualValues, $predictedValues);
    $mape = $this->calculateMAPE($actualValues, $predictedValues);

    // Jika salah satu null, berarti data tidak cukup
    if (is_null($mae) || is_null($rmse) || is_null($mape)) {
        $mae = $rmse = $mape = null;
    }


    // Prepare years for dropdown (gabungkan tahun data dan prediksi)
    $years = $data->pluck('year')
        ->merge($predictions->pluck('year'))
        ->unique()
        ->sort();

    $maintenanceModels = Asset::where('status', 'Maintenance')->get();

    return view('analytics', compact('data', 'predictions', 'years', 'maintenanceModels', 'mae', 'rmse', 'mape'));
}

private function calculateARIMAPredictionsForTest($trainData, $testLength)
{
    $predictions = collect();

    if ($trainData->count() < 2) {
        return $predictions;
    }

    $values = $trainData->pluck('value')->toArray();

    // Calculate differences (d=1)
    $differences = [];
    for ($i = 1; $i < count($values); $i++) {
        $differences[] = $values[$i] - $values[$i - 1];
    }

    // ARIMA parameters (tetap atau bisa dioptimasi)
    $ar_coefficient = 0.6;
    $ma_coefficient = 0.4;
    $previous_error = 0.5;

    $lastYear = $trainData->last()['year'];
    $lastValue = $trainData->last()['value'];
    $lastDifference = end($differences);

    for ($i = 1; $i <= $testLength; $i++) {
        $differencePrediction = ($ar_coefficient * $lastDifference) + ($ma_coefficient * $previous_error);
        $predictedValue = $lastValue + $differencePrediction;

        $lastDifference = $differencePrediction;
        $lastValue = $predictedValue;

        $predictions->push([
            'year' => $lastYear + $i,
            'value' => round($predictedValue, 2)
        ]);
    }

    return $predictions;
}

private function calculateMAE($actual, $predicted)
{
    $n = count($actual);

    // Cek data valid dan cukup
    if ($n === 0 || $n !== count($predicted)) {
        return null; // Kembalikan null jika data tidak valid
    }

    $sumError = 0;
    for ($i = 0; $i < $n; $i++) {
        if (!isset($actual[$i]) || !isset($predicted[$i])) {
            continue;
        }
        $sumError += abs($actual[$i] - $predicted[$i]);
    }

    return $sumError / $n;
}


private function calculateRMSE($actual, $predicted)
{
    $n = count($actual);

    // Validasi data: pastikan tidak kosong dan panjang sama
    if ($n === 0 || $n !== count($predicted)) {
        return null; // atau nilai fallback lain sesuai kebutuhan
    }

    $sumError = 0;
    for ($i = 0; $i < $n; $i++) {
        if (!isset($actual[$i]) || !isset($predicted[$i])) {
            continue;
        }
        $sumError += pow($actual[$i] - $predicted[$i], 2);
    }

    return sqrt($sumError / $n);
}

private function calculateMAPE($actual, $predicted)
{
    $n = count($actual);

    // Validasi data: pastikan tidak kosong dan panjang sama
    if ($n === 0 || $n !== count($predicted)) {
        return null;
    }

    $sumError = 0;
    $countValid = 0; // untuk menghitung jumlah data aktual != 0

    for ($i = 0; $i < $n; $i++) {
        if (!isset($actual[$i]) || !isset($predicted[$i])) {
            continue;
        }
        if ($actual[$i] != 0) {
            $sumError += abs(($actual[$i] - $predicted[$i]) / $actual[$i]);
            $countValid++;
        }
    }

    if ($countValid === 0) {
        return null; // hindari pembagian dengan nol
    }

    return ($sumError / $countValid) * 100;
}
}