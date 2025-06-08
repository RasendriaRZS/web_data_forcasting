<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\AssetMaster;
use App\Models\History;
use Illuminate\Database\QueryException;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $query = Asset::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        // $assets = $query->orderBy('updated_at', 'desc')->with('transactions')->get();
        $assets = $query->orderBy('updated_at', 'desc')->with('transactions')->paginate(15); 
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

        try {
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

            // Catat history create
            History::create([
                'year'        => now()->year,
                'value'       => $asset->value ?? 0, // ganti 0 sesuai kebutuhan jika tidak ada kolom value
                'asset_id'    => $asset->id,
                'action'      => 'insert',
                'description' => 'Asset created',
                'user_id'     => auth()->id(),
            ]);

            return redirect()->route('assets.index')
                ->with('success', 'Asset created successfully.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->withErrors(['serial_number' => 'Serial number already exists.']);
            }
            throw $e;
        }
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
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

        
        
        // Simpan serial number lama sebelum update
        $oldSerialNumber = $asset->serial_number;

         // Update asset master, termasuk serial_number jika berubah
        AssetMaster::where('serial_number', $oldSerialNumber)->update([
            'serial_number'   => $request->serial_number,
            'name'            => $request->name,
            'project_name'    => $request->project_name,
            'model'           => $request->model,
            'status'          => $request->status,
            'asset_recieved'  => $request->purchase_date,
            'asset_shipped'   => $request->delivery_date,
            'location'        => $request->location,
            'notes'           => $request->notes,
            'id_update'       => auth()->id() ?? null,
            'date_update'     => now(),
        ]);

        $asset->update($request->all());

        Transaction::create([
            'serial_number' => $asset->serial_number,
            'transaction_date' => now()->toDateString(),
            'project_name' => $request->project_name ?? 'In Warehouse',
        ]);

        // AssetMaster::where('serial_number', $asset->serial_number)->update([
        //     'name' => $request->name,
        //     'project_name' => $request->project_name,
        //     'model' => $request->model,
        //     'status' => $request->status,
        //     'asset_recieved' => $request->purchase_date,
        //     'asset_shipped' => $request->delivery_date,
        //     'location' => $request->location,
        //     'notes' => $request->notes,
        //     'id_update' => auth()->id() ?? null,
        //     'date_update' => now(),
        // ]);

        // Catat history update
        // History::create([
        //     'year'        => now()->year,
        //     'value'       => $asset->value ?? 0,
        //     'asset_id'    => $asset->id,
        //     'action'      => 'update',
        //     'description' => 'Status: ' . $asset->status . ' | Notes: ' . ($asset->notes ?? '-') . ' | Project: ' . ($asset->project_name ?? '-'),
        //     'user_id'     => auth()->id(),
        // ]);
                History::create([
                'year'        => now()->year,
                'value'       => $asset->value ?? 0,
                'asset_id'    => $asset->id,
                'action'      => 'update', // atau 'delete'
                'description' => 'Status: ' . $asset->status . ' | Notes: ' . ($asset->notes ?? '-') . ' | Project: ' . ($asset->project_name ?? '-'),
                'user_id'     => auth()->id(),
            ]);

        return redirect()->route('assets.index')
            ->with('success', 'Asset updated successfully');
    }

    public function destroy(Asset $asset)
    {
        $assetId = $asset->id; // simpan dulu id-nya sebelum delete
        $someValue = $asset->value ?? 0;

        $asset->delete();

        // Catat history delete
        // History::create([
        //     'year'        => now()->year,
        //     'value'       => $someValue,
        //     'asset_id'    => $assetId,
        //     'action'      => 'delete',
        //     'description' => 'Asset deleted',
        //     'user_id'     => auth()->id(),
        // ]);
            History::create([
                'year'        => now()->year,
                'value'       => $asset->value ?? 0,
                'asset_id'    => $asset->id,
                'action'      => 'delete', // atau 'delete'
                'description' => 'Status: ' . $asset->status . ' | Notes: ' . ($asset->notes ?? '-') . ' | Project: ' . ($asset->project_name ?? '-'),
                'user_id'     => auth()->id(),
            ]);

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully');
    }

    // movement asset 
    // public function detail(Asset $asset)
    // {
    //     $updateCount = History::where('asset_id', $asset->id)->where('action', 'update')->count();
    //     $deleteCount = History::where('asset_id', $asset->id)->where('action', 'delete')->count();
    //     $histories = History::where('asset_id', $asset->id)->orderBy('created_at', 'desc')->get();
    //     $dateInsert = $histories->where('action', 'insert')->first()->created_at ?? $asset->created_at;
    //     $dateDelete = $histories->where('action', 'delete')->first()->created_at ?? null;
    //     $locationStatus = $asset->location;

        


    //     return view('assets.detail', compact(
    //         'asset',
    //         'updateCount',
    //         'deleteCount',
    //         'histories',
    //         'dateInsert',
    //         'dateDelete',
    //         'locationStatus'
    //     ));
    // }

    // AssetController.php

public function detail($id)
{
    $asset = \App\Models\Asset::withTrashed()->find($id);

    if (!$asset) {
        // Asset tidak ditemukan atau sudah dihapus
        return view('assets.detail', [
            'asset' => null,
            'updateCount' => 0,
            'deleteCount' => 0,
            'histories' => [],
            'dateInsert' => null,
            'dateDelete' => null,
            'locationStatus' => null,
            'errorMessage' => 'Asset tidak ditemukan atau sudah dihapus.'
        ]);
    }

    $updateCount = \App\Models\History::where('asset_id', $asset->id)->where('action', 'update')->count();
    $deleteCount = \App\Models\History::where('asset_id', $asset->id)->where('action', 'delete')->count();
    $histories = \App\Models\History::where('asset_id', $asset->id)->orderBy('created_at', 'desc')->get();
    $dateInsert = $histories->where('action', 'insert')->first()->created_at ?? $asset->created_at;
    $dateDelete = $histories->where('action', 'delete')->first()->created_at ?? null;
    $locationStatus = $asset->location;

    return view('assets.detail', compact(
        'asset',
        'updateCount',
        'deleteCount',
        'histories',
        'dateInsert',
        'dateDelete',
        'locationStatus'
    ));
}


    public function show(Asset $asset)
    {
        $updateCount = History::where('asset_id', $asset->id)->where('action', 'update')->count();
        $deleteCount = History::where('asset_id', $asset->id)->where('action', 'delete')->count();
        $histories = History::where('asset_id', $asset->id)->orderBy('created_at', 'desc')->get();
        $dateInsert = $asset->created_at;
        $dateDelete = $histories->where('action', 'delete')->first()->created_at ?? null;
        $locationStatus = $asset->location;

        return view('assets.show', compact(
            'asset',
            'updateCount',
            'deleteCount',
            'histories',
            'dateInsert',
            'dateDelete',
            'locationStatus'
        ));
    }

    public function importForm()
{
    return view('assets.import');
}

public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $path = $request->file('csv_file')->getRealPath();
    $data = array_map('str_getcsv', file($path));
    $header = array_map('strtolower', $data[0]); // lowercase untuk konsistensi

    unset($data[0]); // hapus header

    foreach ($data as $row) {
        $rowData = array_combine($header, $row);

        // Validasi data tiap baris jika perlu
        $validator = Validator::make($rowData, [
            'serial_number' => 'required|unique:assets,serial_number',
            'name' => 'required',
            'model' => 'required',
            'status' => 'required',
            'purchase_date' => 'required|date',
        ]);

        if ($validator->fails()) {
        continue;
        }

        // Simpan asset
        $asset = Asset::create([
            'serial_number' => $rowData['serial_number'],
            'name' => $rowData['name'],
            'model' => $rowData['model'],
            'status' => $rowData['status'],
            'purchase_date' => $rowData['purchase_date'],
            'delivery_date' => $rowData['delivery_date'] ?? null,
            'project_name' => $rowData['project_name'] ?? null,
            'location' => $rowData['location'] ?? null,
            'notes' => $rowData['notes'] ?? null,
        ]);

        // Buat entri master dan history juga, seperti di `store()`
        AssetMaster::firstOrCreate(
            ['serial_number' => $rowData['serial_number']],
            [
                'name' => $rowData['name'],
                'project_name' => $rowData['project_name'] ?? null,
                'model' => $rowData['model'],
                'status' => $rowData['status'],
                'asset_recieved' => $rowData['purchase_date'],
                'asset_shipped' => $rowData['delivery_date'] ?? null,
                'location' => $rowData['location'] ?? null,
                'notes' => $rowData['notes'] ?? null,
                'id_insert' => auth()->id() ?? null,
                'date_insert' => now(),
                'is_delete' => 0
            ]
        );

        History::create([
            'year'        => now()->year,
            'value'       => $asset->value ?? 0,
            'asset_id'    => $asset->id,
            'action'      => 'insert',
            'description' => 'Imported via CSV',
            'user_id'     => auth()->id(),
        ]);
    }

    return redirect()->route('assets.index')->with('success', 'CSV import completed successfully!');
}

}
