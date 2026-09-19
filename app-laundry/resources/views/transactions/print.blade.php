@extends('layouts.app')

@section('title','Cetak Nota')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
  <div>
    <h4 class="mb-1">Faktur Transaksi</h4>
  </div>
  <button onclick="window.print()" class="btn btn-primary">
    <i class="bi bi-printer"></i> Cetak
  </button>
</div>

<div class="card p-4">
  <div class="row mb-4">
    <div class="col-md-6">
      <h6>Data Pelanggan</h6>
      <p class="mb-1"><strong>{{ $transaction->customer->name ?? '-' }}</strong></p>
      <p class="mb-1">ID Transaksi: {{ $transaction->id }}</p>
      <p class="mb-0">Status: {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}</p>
    </div>
    <div class="col-md-6">
      <h6>Waktu</h6>
      <p class="mb-1">Selesai: {{ optional($transaction->ready_at)->format('Y-m-d H:i:s') }}</p>
      <p class="mb-0">Diambil: {{ optional($transaction->picked_up_at)->format('Y-m-d H:i:s') }}</p>
    </div>
  </div>

  <div class="table-responsive mb-4">
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Layanan</th>
          <th class="text-end">Qty</th>
          <th class="text-end">Berat</th>
          <th class="text-end">Harga</th>
          <th class="text-end">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transaction->items as $item)
          <tr>
            <td>{{ $item->service->name ?? $item->description }}</td>
            <td class="text-end">{{ $item->quantity }}</td>
            <td class="text-end">{{ $item->weight ?? '-' }}</td>
            <td class="text-end">Rp {{ number_format($item->unit_price,0,',','.') }}</td>
            <td class="text-end">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end">Total</th>
          <th class="text-end">Rp {{ number_format($transaction->total_price,0,',','.') }}</th>
        </tr>
      </tfoot>
    </table>
  </div>

  <p class="text-muted">Terima kasih telah menggunakan layanan kami.</p>
</div>
@endsection
