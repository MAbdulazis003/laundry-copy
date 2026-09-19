@extends('layouts.app')

@section('title','Detail Transaksi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1><i class="bi bi-receipt"></i> Detail Transaksi #{{ $transaction->id }}</h1>
    <p class="mb-0">Lihat dan kelola detail transaksi</p>
  </div>
  <a href="{{ route('transactions.print', $transaction) }}" class="btn btn-primary">
    <i class="bi bi-printer"></i> Cetak Faktur
  </a>
</div>

<div class="row mb-4">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="text-muted mb-3"><i class="bi bi-person"></i> Pelanggan</h6>
        <h5 class="mb-1">{{ $transaction->customer->name ?? '-' }}</h5>
        <p class="text-muted mb-0">ID: {{ $transaction->customer->id ?? '-' }}</p>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="text-muted mb-3"><i class="bi bi-check-circle"></i> Status</h6>
        @if($transaction->status === 'received')
          <span class="badge bg-warning p-2">Diterima</span>
        @elseif($transaction->status === 'ready')
          <span class="badge bg-success p-2">Siap Diambil</span>
        @elseif($transaction->status === 'picked_up')
          <span class="badge bg-info p-2">Selesai</span>
        @else
          <span class="badge bg-secondary p-2">{{ ucfirst(str_replace('_', ' ', $transaction->status)) }}</span>
        @endif
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="text-muted mb-3"><i class="bi bi-calendar3"></i> Waktu</h6>
        @if($transaction->ready_at)
          <p class="mb-1"><small class="text-muted">Selesai</small><br><strong>{{ $transaction->ready_at->format('d M Y H:i') }}</strong></p>
        @endif
        @if($transaction->picked_up_at)
          <p class="mb-0"><small class="text-muted">Diambil</small><br><strong>{{ $transaction->picked_up_at->format('d M Y H:i') }}</strong></p>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0"><i class="bi bi-list"></i> Rincian Layanan</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
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
          @foreach($transaction->items as $it)
            <tr>
              <td><strong>{{ $it->service->name ?? $it->description }}</strong></td>
              <td class="text-end">{{ $it->quantity }}</td>
              <td class="text-end">{{ $it->weight ?? '-' }} kg</td>
              <td class="text-end">Rp {{ number_format($it->unit_price,0,',','.') }}</td>
              <td class="text-end"><strong>Rp {{ number_format($it->subtotal,0,',','.') }}</strong></td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="table-active">
            <th colspan="4" class="text-end">Total:</th>
            <th class="text-end"><h5 class="mb-0">Rp {{ number_format($transaction->total_price,0,',','.') }}</h5></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
