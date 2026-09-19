<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cetak Nota — {{ $transaction->id }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
    }

    /* ── Toolbar (hanya layar) ── */
    .toolbar {
      background: #fff;
      border-bottom: 1px solid #e9ecef;
      padding: .75rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .toolbar .brand {
      font-weight: 700;
      font-size: 1.1rem;
      color: #1a2e4a;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: .4rem;
    }

    .toolbar .brand i { color: #0d6efd; font-size: 1.3rem; }

    /* ── Faktur container ── */
    .faktur-wrap {
      max-width: 800px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    .faktur {
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 2px 12px rgba(0,0,0,.08);
      padding: 2rem 2.5rem;
    }

    /* Header faktur */
    .faktur-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding-bottom: 1.25rem;
      border-bottom: 2px solid #e9ecef;
      margin-bottom: 1.5rem;
    }

    .faktur-brand { font-size: 1.5rem; font-weight: 700; color: #1a2e4a; }
    .faktur-brand small { display: block; font-size: .8rem; font-weight: 400; color: #6c757d; margin-top: .1rem; }

    .faktur-badge {
      background: #e8f4fd;
      color: #0d6efd;
      border-radius: .5rem;
      padding: .35rem .8rem;
      font-size: .8rem;
      font-weight: 600;
      text-align: right;
    }

    .faktur-badge .id {
      font-size: 1.1rem;
      font-weight: 700;
      color: #1a2e4a;
      display: block;
    }

    /* Info rows */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem 2rem;
      margin-bottom: 1.5rem;
    }

    .info-label {
      font-size: .74rem;
      font-weight: 600;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: .04em;
      margin-bottom: .2rem;
    }

    .info-value {
      font-size: .9rem;
      color: #1a2e4a;
      font-weight: 500;
    }

    /* Table */
    .table { border-collapse: separate; border-spacing: 0; font-size: .88rem; }
    .table thead th {
      background: #f8f9fa;
      color: #6c757d;
      font-size: .75rem;
      text-transform: uppercase;
      letter-spacing: .05em;
      border-bottom: 2px solid #dee2e6;
      padding: .6rem .75rem;
    }
    .table tbody td { padding: .65rem .75rem; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .table tfoot th {
      border-top: 2px solid #dee2e6;
      padding: .7rem .75rem;
      font-size: .9rem;
    }

    .total-amount { font-size: 1.1rem; color: #0d6efd; }

    /* Status badge */
    .status-pill {
      display: inline-block;
      padding: .25rem .65rem;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 600;
    }

    /* Footer faktur */
    .faktur-footer {
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px dashed #dee2e6;
      font-size: .8rem;
      color: #9ca3af;
      text-align: center;
    }

    /* ══════════════════════
       PRINT STYLES
       ══════════════════════ */
    @media print {
      @page { margin: 1.5cm; size: A5 portrait; }

      body { background: #fff !important; }

      /* Sembunyikan toolbar & tombol aksi */
      .toolbar,
      .action-buttons { display: none !important; }

      /* Faktur penuh layar */
      .faktur-wrap { max-width: 100%; margin: 0; padding: 0; }

      .faktur {
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
      }

      .table tbody td, .table tfoot th { font-size: .8rem; }
    }
  </style>
</head>
<body>

  <!-- Toolbar layar saja -->
  <div class="toolbar d-print-none">
    <a href="{{ route('transactions.index') }}" class="brand">
      <i class="bi bi-shop"></i> Laundry newci
    </a>
    <div class="action-buttons d-flex gap-2">
      <!-- Cetak -->
      <button onclick="window.print()"
              class="btn btn-sm btn-primary d-flex align-items-center gap-1">
        <i class="bi bi-printer"></i> Cetak
      </button>
      <!-- Kembali -->
      <a href="{{ route('reports.index') }}"
         class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Faktur -->
  <div class="faktur-wrap">
    <div class="faktur">

      <!-- Header -->
      <div class="faktur-header">
        <div>
          <div class="faktur-brand">
            🧺 Laundry NEWCI
            <small>Nota / Faktur Transaksi</small>
          </div>
        </div>
        <div class="faktur-badge">
          <span>No. Transaksi</span>
          <span class="id">#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
          <span>{{ optional($transaction->created_at)->format('d/m/Y') }}</span>
        </div>
      </div>

      <!-- Info Grid -->
      <div class="info-grid">
        <div>
          <div class="info-label">Pelanggan</div>
          <div class="info-value">{{ $transaction->customer->name ?? '-' }}</div>
        </div>
        <div>
          <div class="info-label">Status</div>
          <div class="info-value">
            @php
              $statusMap = [
                'pending'    => ['bg-warning text-dark', 'Menunggu'],
                'processing' => ['bg-info text-dark',    'Diproses'],
                'ready'      => ['bg-primary text-white','Siap Diambil'],
                'picked_up'  => ['bg-success text-white','Selesai'],
                'cancelled'  => ['bg-danger text-white', 'Dibatalkan'],
              ];
              $s = $statusMap[$transaction->status] ?? ['bg-secondary text-white', ucfirst($transaction->status)];
            @endphp
            <span class="status-pill {{ $s[0] }}">{{ $s[1] }}</span>
          </div>
        </div>
        <div>
          <div class="info-label">Tanggal Selesai</div>
          <div class="info-value">{{ optional($transaction->ready_at)->format('d M Y, H:i') ?? '-' }}</div>
        </div>
        @if($transaction->picked_up_at)
        <div>
          <div class="info-label">Tanggal Diambil</div>
          <div class="info-value">{{ optional($transaction->picked_up_at)->format('d M Y, H:i') }}</div>
        </div>
        @endif
      </div>

      <!-- Tabel Item -->
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Layanan</th>
              <th class="text-center" style="width:60px">Qty</th>
              <th class="text-center" style="width:80px">Berat</th>
              <th class="text-end" style="width:110px">Harga Satuan</th>
              <th class="text-end" style="width:110px">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transaction->items as $item)
              <tr>
                <td>{{ $item->service->name ?? $item->description }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-center">{{ $item->weight ? $item->weight.' kg' : '-' }}</td>
                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-end">Total</th>
              <th class="text-end total-amount">
                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
              </th>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Footer faktur -->
      <div class="faktur-footer">
        Terima kasih telah menggunakan layanan kami 🙏<br>
        Dicetak pada {{ now()->format('d M Y, H:i') }}
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>