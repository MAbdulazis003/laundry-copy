@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<div class="dashboard-hero mb-4">
  <div>
    <span class="dashboard-kicker">Ringkasan operasional</span>
    <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
    <p>Pantau arus transaksi, pendapatan, dan biaya laundry dalam satu tampilan.</p>
  </div>
  <div class="dashboard-date">
    <i class="bi bi-calendar3"></i>
    {{ now()->translatedFormat('l, d F Y') }}
  </div>
</div>

<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card dashboard-stat-card dashboard-stat-blue text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="card-text opacity-75">Riwayat Transaksi</p>
            <h3 class="card-title mb-0">{{ $transactionsSummary ?? 0 }}</h3>
            <a href="{{ route('transactions.index') }}" class="dashboard-stat-link">Lihat transaksi <i class="bi bi-arrow-up-right"></i></a>
          </div>
          <i class="bi bi-receipt" style="font-size: 2rem; opacity: 0.5;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card dashboard-stat-card dashboard-stat-green text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="card-text opacity-75">Pendapatan {{ $summaryLabel ?? 'Periode' }}</p>
            <h3 class="card-title mb-0">Rp {{ number_format($incomeSummary ?? 0, 0, ',', '.') }}</h3>
          </div>
          <i class="bi bi-cash-coin" style="font-size: 2rem; opacity: 0.5;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card dashboard-stat-card dashboard-stat-red text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="card-text opacity-75">Pengeluaran {{ $summaryLabel ?? 'Periode' }}</p>
            <h3 class="card-title mb-0">Rp {{ number_format($expenseSummary ?? 0, 0, ',', '.') }}</h3>
          </div>
          <i class="bi bi-plus-square" style="font-size: 2rem; opacity: 0.5;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="card dashboard-stat-card dashboard-stat-orange text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="card-text opacity-75">Aksi Cepat</p>
            <div class="d-flex flex-wrap gap-2 mt-2">
              <a href="{{ route('transactions.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Transaksi
              </a>
              <a href="{{ route('expenses.create') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-wallet2"></i> Pengeluaran
              </a>
            </div>
          </div>
          <i class="bi bi-wallet2" style="font-size: 2rem; opacity: 0.5;"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="dashboard-chart-card card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <h5 class="card-title mb-0"><i class="bi bi-graph-up"></i> Grafik Pendapatan dan Pengeluaran</h5>
    <div class="d-flex gap-2">
      <select id="dashboardPeriod" class="form-select form-select-sm" aria-label="Periode grafik">
        <option value="day" @selected($period === 'day')>Hari</option>
        <option value="week" @selected($period === 'week')>Minggu</option>
        <option value="month" @selected($period === 'month')>Bulan</option>
        <option value="year" @selected($period === 'year')>Tahun</option>
      </select>
      <select id="dashboardChartType" class="form-select form-select-sm" aria-label="Jenis grafik">
        <option value="line" @selected($chartType === 'line')>Garis</option>
        <option value="bar" @selected($chartType === 'bar')>Batang</option>
        <option value="doughnut" @selected($chartType === 'doughnut')>Donat</option>
      </select>
    </div>
  </div>
  <div class="card-body">
    <canvas id="incomeChart" height="80"
            data-labels='@json($chartLabels ?? [])'
          data-values='@json($chartData ?? [])'
          data-expenses='@json($expenseData ?? [])'></canvas>
  </div>
</div>

<style>
  .dashboard-hero { display: flex; justify-content: space-between; align-items: end; gap: 1rem; padding: 1.5rem 1.75rem; border-radius: .85rem; background: linear-gradient(120deg, #172b4d 0%, #245b78 58%, #2aa889 100%); color: #fff; box-shadow: 0 12px 28px rgba(23,43,77,.16); }
  .dashboard-hero h1 { color: #fff; margin: .3rem 0 .35rem; font-size: 1.75rem; }
  .dashboard-hero p { margin: 0; color: rgba(255,255,255,.78); }
  .dashboard-kicker { color: #a7f3d0; text-transform: uppercase; letter-spacing: .08em; font-size: .72rem; font-weight: 700; }
  .dashboard-date { display: flex; align-items: center; gap: .5rem; white-space: nowrap; color: rgba(255,255,255,.82); font-size: .9rem; }
  .dashboard-stat-card { min-height: 156px; overflow: hidden; position: relative; border: 0; }
  .dashboard-stat-card .card-body { position: relative; z-index: 1; }
  .dashboard-stat-card::after { content: ''; position: absolute; width: 110px; height: 110px; right: -28px; bottom: -40px; border: 1px solid rgba(255,255,255,.22); border-radius: 50%; box-shadow: 0 0 0 18px rgba(255,255,255,.05), 0 0 0 36px rgba(255,255,255,.04); }
  .dashboard-stat-blue { background: linear-gradient(135deg, #1769aa, #328cc1); }
  .dashboard-stat-green { background: linear-gradient(135deg, #168a71, #2dbb92); }
  .dashboard-stat-red { background: linear-gradient(135deg, #b83b52, #db5b43); }
  .dashboard-stat-orange { background: linear-gradient(135deg, #d47721, #e69a36); }
  .dashboard-stat-link { display: inline-block; margin-top: .6rem; color: rgba(255,255,255,.84); font-size: .78rem; text-decoration: none; }
  .dashboard-stat-link:hover { color: #fff; }
  .dashboard-chart-card { margin-top: .25rem; }
  .dashboard-chart-card .card-header { padding: 1rem 1.25rem; }
  @media (max-width: 575.98px) { .dashboard-hero { align-items: start; flex-direction: column; padding: 1.25rem; } .dashboard-date { font-size: .8rem; } }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const canvas = document.getElementById('incomeChart');
  const ctx = canvas.getContext('2d');
  const chartLabels = JSON.parse(canvas.dataset.labels);
  const chartData = JSON.parse(canvas.dataset.values);
  const expenseData = JSON.parse(canvas.dataset.expenses);
  const chartType = document.getElementById('dashboardChartType');
  const period = document.getElementById('dashboardPeriod');

  const chartConfig = (type) => ({
    type,
    data: {
      labels: chartLabels,
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: chartData,
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13, 110, 253, 0.1)',
        borderWidth: 3,
        tension: 0.4,
        fill: type === 'line',
        pointBackgroundColor: '#0d6efd',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: type === 'line' ? 4 : 0,
        pointHoverRadius: 7,
      }, {
        label: 'Pengeluaran (Rp)',
        data: expenseData,
        borderColor: '#dc3545',
        backgroundColor: 'rgba(220, 53, 69, 0.12)',
        borderWidth: 3,
        tension: 0.4,
        fill: type === 'line',
        pointBackgroundColor: '#dc3545',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: type === 'line' ? 4 : 0,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: type !== 'doughnut',
          position: 'top',
        }
      },
      scales: {
        y: {
          display: type !== 'doughnut',
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });
  let incomeChart = new Chart(ctx, chartConfig(chartType.value));

  chartType.addEventListener('change', () => {
    incomeChart.destroy();
    incomeChart = new Chart(ctx, chartConfig(chartType.value));
  });

  period.addEventListener('change', () => {
    const url = new URL(window.location.href);
    url.searchParams.set('period', period.value);
    url.searchParams.set('chart_type', chartType.value);
    window.location.href = url.toString();
  });
</script>
@endpush
@endsection
