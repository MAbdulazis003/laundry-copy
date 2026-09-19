<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest('expense_date')->latest()->paginate(20);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $data['created_by'] = Auth::id();
        Expense::create($data);

        return redirect()->route('expenses.index')->with('status', 'Pengeluaran berhasil dicatat.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('status', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('status', 'Pengeluaran berhasil dihapus.');
    }
}