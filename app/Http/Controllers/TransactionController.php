<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Asset;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Tampilkan semua transaksi
    public function index()
    {
        $transactions = Transaction::orderBy('transaction_date', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    // Tampilkan detail transaksi (opsional)
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|exists:assets,serial_number',
            'transaction_date' => 'required|date',
            'project_name' => 'required|string|max:255',
        ]);

        Transaction::create([
            'serial_number' => $request->serial_number,
            'transaction_date' => $request->transaction_date,
            'project_name' => $request->project_name,
        ]);

        return redirect()->back()->with('success', 'Transaction created successfully.');
    }

    // Hapus transaksi
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
