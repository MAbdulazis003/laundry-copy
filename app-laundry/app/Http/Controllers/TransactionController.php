<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('customer')
            ->where('status', '<>', 'picked_up')
            ->latest()
            ->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0.01',
            'total_price' => 'required|numeric|min:0',
            'received_at' => 'nullable|date',
        ]);

        $service = Service::findOrFail($data['service_id']);
        $quantity = $service->pricing_type === 'per_item' ? intval($data['amount']) : 1;
        $weight = $service->pricing_type === 'per_kg' ? $data['amount'] : null;
        $totalPrice = $service->price * $data['amount'];
        $defaultDueDays = Setting::value('default_due_days') ?? 1;

        $transaction = Transaction::create([
            'customer_id' => $data['customer_id'],
            'cashier_id' => Auth::id(),
            'total_weight' => $weight,
            'total_price' => $totalPrice,
            'status' => 'received',
            'due_at' => now()->addDays($defaultDueDays),
            'notes' => null,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'service_id' => $service->id,
            'description' => $service->name,
            'quantity' => $quantity,
            'weight' => $weight,
            'unit_price' => $service->price,
            'subtotal' => $totalPrice,
        ]);

        return redirect()->route('transactions.index');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items.service', 'operations');
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('transactions.edit', compact('transaction', 'customers', 'services'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'total_weight' => 'sometimes|nullable|numeric',
            'total_price' => 'sometimes|required|numeric',
            'status' => 'sometimes|nullable|in:received,weighed,processing,washing,drying,ironing,ready,picked_up,cancelled',
        ]);

        if (empty($data)) {
            return redirect()->route('transactions.show', $transaction)->with('status', 'Tidak ada perubahan yang disimpan.');
        }

        if (isset($data['status'])) {
            if ($data['status'] === 'picked_up') {
                $data['picked_up_at'] = now();
            }
        }

        $transaction->update($data);
        return redirect()->route('transactions.show', $transaction)->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index');
    }

    public function print(Transaction $transaction)
    {
        $transaction->load('items.service');
        return view('print', compact('transaction'));
    }
}