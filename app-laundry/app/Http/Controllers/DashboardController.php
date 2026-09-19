<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'week');
        if (!in_array($period, ['day', 'week', 'month', 'year'], true)) {
            $period = 'week';
        }
        $chartType = $request->query('chart_type', 'line');
        if (!in_array($chartType, ['line', 'bar', 'doughnut'], true)) {
            $chartType = 'line';
        }

        [$start, $end, $step, $labelFormat, $summaryLabel] = match ($period) {
            'day' => [now()->startOfDay(), now()->endOfDay(), 'hour', 'H:00', 'Hari Ini'],
            'month' => [now()->startOfMonth(), now()->endOfMonth(), 'day', 'd M', 'Bulan Ini'],
            'year' => [now()->startOfYear(), now()->endOfYear(), 'month', 'M', 'Tahun Ini'],
            default => [now()->subDays(6)->startOfDay(), now()->endOfDay(), 'day', 'd M', '7 Hari Terakhir'],
        };

        $transactionsSummary = Transaction::whereBetween('created_at', [$start, $end])->count();
        $incomeSummary = Transaction::whereBetween('created_at', [$start, $end])->sum('total_price');
        $expenseSummary = Expense::whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->sum('amount');

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

            $chartLabels[] = $bucketStart->format($labelFormat);
            $chartData[] = (float) Transaction::whereBetween('created_at', [$bucketStart, $bucketEnd])->sum('total_price');
            $expenseData[] = $step === 'hour' && $bucketStart->hour !== 0
                ? 0
                : (float) Expense::whereBetween('expense_date', [
                    $bucketStart->toDateString(),
                    $bucketEnd->toDateString(),
                ])->sum('amount');
            $cursor = $bucketEnd->copy()->addSecond();
        }

        return view('dashboard', compact(
            'transactionsSummary', 'incomeSummary', 'expenseSummary', 'summaryLabel',
            'period', 'chartType', 'chartLabels', 'chartData', 'expenseData'
        ));
    }
}
