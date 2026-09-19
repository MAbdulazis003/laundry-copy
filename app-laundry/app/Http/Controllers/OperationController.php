<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\OperationsLog;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function index()
    {
        $transactions = Transaction::whereIn('status', ['received','washing','drying','ironing'])
            ->with(['customer', 'items.service'])
            ->get();
        return view('operations.index', compact('transactions'));
    }

    public function updateStage(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'stage' => 'required|in:received,washing,drying,ironing,finished',
        ]);

        OperationsLog::create([
            'transaction_id' => $transaction->id,
            'operator_id' => $request->user()->id,
            'stage' => $data['stage'],
            'performed_at' => now(),
        ]);

        // Update transaction status berdasarkan stage yang dipilih
        $statusMap = [
            'received' => 'received',
            'washing' => 'washing',
            'drying' => 'drying',
            'ironing' => 'ironing',
            'finished' => 'ready',
        ];

        $updateData = ['status' => $statusMap[$data['stage']] ?? 'processing'];
        if ($data['stage'] === 'finished') {
            $updateData['ready_at'] = now();
        }

        $transaction->update($updateData);

        return redirect()->route('operations.index');
    }
}
