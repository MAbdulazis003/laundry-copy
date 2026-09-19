@extends('layouts.app')

@section('title','Laporan')

@section('content')
<div class="page-header mb-4">
  <h1><i class="bi bi-graph-up"></i> Laporan</h1>
  <p>Analisis data dan pendapatan laundry newci</p>
</div>

<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('reports.generate') }}">@csrf
      <div class="row">
        <div class="col-lg-4 mb-3">
          <label class="form-label">Tanggal Mulai</label>
          <input type="date" name="date_from" class="form-control"
                 value="{{ old('date_from', $data['date_from'] ?? date('Y-m-d')) }}" required>
        </div>
        <div class="col-lg-4 mb-3">
          <label class="form-label">Tanggal Akhir</label>
          <input type="date" name="date_to" class="form-control"
                 value="{{ old('date_to', $data['date_to'] ?? date('Y-m-d')) }}" required>
        </div>
        <div class="col-lg-2 mb-3">
          <label class="form-label">Periode Grafik</label>
          <select name="period" class="form-select">
            <option value="day" @selected(($period ?? 'week') === 'day')>Hari</option>
            <option value="week" @selected(($period ?? 'week') === 'week')>Minggu</option>
            <option value="month" @selected(($period ?? 'week') === 'month')>Bulan</option>
            <option value="year" @selected(($period ?? 'week') === 'year')>Tahun</option>
          </select>
        </div>
        <div class="col-lg-2 mb-3">
          <label class="form-label">Jenis Grafik</label>
          <select name="chart_type" id="reportChartType" class="form-select">
            <option value="line" @selected(old('chart_type', 'line') === 'line')>Garis</option>
            <option value="bar" @selected(old('chart_type') === 'bar')>Batang</option>
            <option value="doughnut" @selected(old('chart_type') === 'doughnut')>Donat</option>
          </select>
        </div>
        <div class="col-lg-2 mb-3">
          <label class="form-label">&nbsp;</label>
          <div>
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-search"></i> Generate Laporan
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@if(isset($transactions))
  <div class="row mb-4">
    <div class="col-lg-4">
      <div class="card text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text opacity-75">Jumlah Transaksi</p>
              <h3 class="card-title mb-0">{{ $count }}</h3>
            </div>
            <i class="bi bi-receipt" style="font-size: 2rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text opacity-75">Total Pendapatan</p>
              <h3 class="card-title mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h3>
            </div>
            <i class="bi bi-cash-coin" style="font-size: 2rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card text-white" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="card-text opacity-75">Total Pengeluaran</p>
              <h3 class="card-title mb-0">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</h3>
            </div>
            <i class="bi bi-wallet2" style="font-size: 2rem; opacity: 0.5;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="bi bi-bar-chart-line"></i> Grafik Pendapatan dan Pengeluaran</h5>
      <button type="button" class="btn btn-outline-primary btn-sm" id="analysisButton">
        <i class="bi bi-lightbulb"></i> Analisis Grafik
      </button>
    </div>
    <div class="card-body">
      <canvas id="reportChart" height="90"
              data-labels='@json($chartLabels ?? [])'
              data-values='@json($chartData ?? [])'
              data-expenses='@json($expenseData ?? [])'
              data-total="{{ $total }}"
              data-count="{{ $count }}"
              data-type="{{ old('chart_type', 'line') }}"></canvas>
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="bi bi-table"></i> Daftar Transaksi</h5>
      {{-- Tombol Export Excel seluruh data --}}
      <a href="{{ route('reports.export-excel', ['date_from' => $data['date_from'], 'date_to' => $data['date_to']]) }}"
         class="btn btn-success btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover dataTable">
          <thead class="table-light">
            <tr>
              <th>id</th>
              <th>Pelanggan</th>
              <th>Status</th>
              <th>Total</th>
              <th>Tanggal Selesai</th>
              <th>Tanggal Ambil</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transactions as $transaction)
              <tr>
                <td><strong>#{{ $transaction->id }}</strong></td>
                <td>{{ $transaction->customer->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $transaction->status)) }}</td>
                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                <td>{{ optional($transaction->ready_at)->format('Y-m-d H:i:s') }}</td>
                <td>{{ optional($transaction->picked_up_at)->format('Y-m-d H:i:s') }}</td>
                <td class="text-center">
                  <a href="{{ route('transactions.print', $transaction) }}"
                     class="btn btn-sm btn-outline-secondary" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Faktur
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endif

@if(isset($transactions))
<div class="modal fade" id="analysisModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-lightbulb"></i> Analisis Grafik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="analysisContent"></div>
    </div>
  </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($transactions))
<script>
  const reportCanvas = document.getElementById('reportChart');
  const reportType = reportCanvas.dataset.type;
  const reportLabels = JSON.parse(reportCanvas.dataset.labels);
  const reportValues = JSON.parse(reportCanvas.dataset.values);
  const reportExpenses = JSON.parse(reportCanvas.dataset.expenses);
  const reportChartConfig = (type) => ({
    type,
    data: {
      labels: reportLabels,
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: reportValues,
        borderColor: '#0d6efd',
        backgroundColor: type === 'doughnut'
          ? ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14']
          : 'rgba(13, 110, 253, .16)',
        borderWidth: 2,
        tension: .35,
        fill: type === 'line'
      }, {
        label: 'Pengeluaran (Rp)',
        data: reportExpenses,
        borderColor: '#dc3545',
        backgroundColor: 'rgba(220, 53, 69, .16)',
        borderWidth: 2,
        tension: .35,
        fill: type === 'line'
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: true, position: 'top' } },
      scales: { y: { display: type !== 'doughnut', beginAtZero: true } }
    }
  });

  let reportChart = new Chart(reportCanvas.getContext('2d'), reportChartConfig(reportType));
  const reportChartType = document.getElementById('reportChartType');

  reportChartType.addEventListener('change', () => {
    reportChart.destroy();
    reportChart = new Chart(reportCanvas.getContext('2d'), reportChartConfig(reportChartType.value));
  });

  document.getElementById('analysisButton').addEventListener('click', () => {
    const total = Number(reportCanvas.dataset.total);
    const count = Number(reportCanvas.dataset.count);
    const average = count ? total / count : 0;
    const peak = reportValues.length ? Math.max(...reportValues) : 0;
    document.getElementById('analysisContent').innerHTML = `
      <p>Periode laporan memiliki <strong>${count.toLocaleString('id-ID')}</strong> transaksi.</p>
      <p>Total pendapatan: <strong>Rp ${total.toLocaleString('id-ID')}</strong>.</p>
      <p>Rata-rata pendapatan per transaksi: <strong>Rp ${average.toLocaleString('id-ID', { maximumFractionDigits: 0 })}</strong>.</p>
      <p>Pendapatan tertinggi dalam satu periode grafik: <strong>Rp ${peak.toLocaleString('id-ID')}</strong>.</p>
      <p class="text-muted mb-0">nanti bisa di tambah lagi.</p>`;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('analysisModal')).show();
  });
</script>
@endif
@endpush
@endsection