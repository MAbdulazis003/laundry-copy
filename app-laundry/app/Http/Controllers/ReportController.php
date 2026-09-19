<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'period' => 'nullable|in:day,week,month,year',
            'chart_type' => 'nullable|in:line,bar,doughnut',
        ]);

        $transactions = Transaction::with('customer', 'items.service')
            ->whereDate('picked_up_at', '>=', $data['date_from'])
            ->whereDate('picked_up_at', '<=', $data['date_to'])
            ->orderByDesc('picked_up_at')
            ->get();
        $expenses = Expense::whereBetween('expense_date', [$data['date_from'], $data['date_to']])
            ->orderByDesc('expense_date')
            ->get();

        $total = collect($transactions)->sum('total_price');
        $expenseTotal = $expenses->sum('amount');
        $count = count($transactions);
        $statusSummary = collect($transactions)->groupBy('status')->map->count();

        // Service statistics
        $serviceStats = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $serviceName = $item->service->name ?? 'Unknown';
                if (!isset($serviceStats[$serviceName])) {
                    $serviceStats[$serviceName] = ['count' => 0, 'revenue' => 0];
                }
                $serviceStats[$serviceName]['count'] += $item->quantity;
                $serviceStats[$serviceName]['revenue'] += $item->subtotal;
            }
        }

        $serviceLabels = array_keys($serviceStats);
        $serviceRevenue = array_column($serviceStats, 'revenue');

        $period = $request->input('period', 'week');
        if (!in_array($period, ['day', 'week', 'month', 'year'], true)) {
            $period = 'week';
        }

        $start = Carbon::parse($data['date_from'])->startOfDay();
        $end = Carbon::parse($data['date_to'])->endOfDay();
        $step = $period === 'day' ? 'hour' : ($period === 'year' ? 'month' : 'day');
        $chartLabels = [];
        $chartData = [];
        $expenseData = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $bucketStart = $cursor->copy();
            $bucketEnd = match ($step) {
                'hour' => $bucketStart->copy()->endOfHour(),
                'month' => $bucketStart->copy()->endOfMonth(),
                default => $bucketStart->copy()->endOfDay(),
            };
            $bucketEnd = $bucketEnd->greaterThan($end) ? $end->copy() : $bucketEnd;

            $chartLabels[] = $step === 'hour'
                ? $bucketStart->format('d M H:00')
                : ($step === 'month' ? $bucketStart->format('M Y') : $bucketStart->format('d M Y'));
            $chartData[] = (float) $transactions
                ->filter(fn ($transaction) => $transaction->picked_up_at
                    && $transaction->picked_up_at->betweenIncluded($bucketStart, $bucketEnd))
                ->sum('total_price');
            $expenseData[] = $step === 'hour' && $bucketStart->hour !== 0
                ? 0
                : (float) $expenses
                    ->filter(fn ($expense) => $expense->expense_date->betweenIncluded(
                        $bucketStart->copy()->startOfDay(),
                        $bucketEnd->copy()->endOfDay()
                    ))
                    ->sum('amount');
            $cursor = $bucketEnd->copy()->addSecond();
        }

        return view('reports.index', compact(
            'transactions', 'total', 'count', 'statusSummary', 'data',
            'serviceLabels', 'serviceRevenue', 'expenseTotal', 'period', 'chartLabels', 'chartData', 'expenseData'
        ));
    }

    public function exportExcel(Request $request)
    {
        $dateFrom = $request->query('date_from', date('Y-m-d'));
        $dateTo   = $request->query('date_to',   date('Y-m-d'));
    
        $filename = 'laporan-transaksi-' . $dateFrom . '-sd-' . $dateTo . '.xlsx';
    
        return Excel::download(new ReportExport($dateFrom, $dateTo), $filename);
    }
}
