@extends('layouts.app')

@section('title','Daftar Transaksi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1><i class="bi bi-receipt"></i> Daftar Transaksi</h1>
    <p>Kelola semua transaksi laundry Anda</p>
  </div>
  <a href="{{ route('transactions.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Transaksi Baru
  </a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal Selesai</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transactions as $t)
            <tr>
              <td><strong>#{{ $t->id }}</strong></td>
              <td>{{ $t->customer->name ?? '-' }}</td>
              <td><strong>Rp {{ number_format($t->total_price,0,',','.') }}</strong></td>
              <td>
                @if($t->status === 'received')
                  <span class="badge bg-warning">Diterima</span>
                @elseif($t->status === 'ready')
                  <span class="badge bg-success">Siap Diambil</span>
                @else
                  <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span>
                @endif
              </td>
              <td>
                @if($t->ready_at)
                  {{ $t->ready_at->format('d M Y H:i') }}
                @else
                  <small class="text-muted">-</small>
                @endif
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{ route('transactions.show', $t) }}" class="btn btn-outline-primary" title="Lihat Detail">
                    <i class="bi bi-eye"></i>
                  </a>
                  @if($t->status === 'ready')
                    <form action="{{ route('transactions.update', $t) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="picked_up">
                      <button type="submit" class="btn btn-outline-success" title="Tandai Selesai">
                        <i class="bi bi-check-circle"></i>
                      </button>
                    </form>
                  @elseif($t->status === 'received')
                    <form action="{{ route('transactions.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan transaksi ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" title="Batalkan">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
  </tbody>
</table>
@endsection
