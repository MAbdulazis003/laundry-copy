@extends('layouts.app')

@section('title','Operasional')

@section('content')
<div class="page-header mb-4">
  <h1><i class="bi bi-gear"></i> Operasional - Antrian</h1>
  <p>Kelola proses operasional laundry newci</p>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0"><i class="bi bi-list-check"></i> Daftar Transaksi</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable">
        <thead class="table-light">
          <tr>
            <th>id</th>
            <th>Pelanggan</th>
            <th>Layanan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transactions as $t)
            <tr>
              <td><strong>#{{ $t->id }}</strong></td>
              <td>{{ $t->customer->name ?? '-' }}</td>
              <td>
                @php
                  $serviceNames = $t->items->map(function ($item) {
                      return $item->service->name ?? $item->description;
                  })->filter()->unique()->join(', ');
                @endphp
                <small>{{ $serviceNames ?: '-' }}</small>
              </td>
              <td>
                @if($t->status === 'received')
                  <span class="badge bg-warning">Diterima</span>
                @elseif($t->status === 'ready')
                  <span class="badge bg-success">Siap Diambil</span>
                @else
                  <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span>
                @endif
              </td>
              <td>
                <form method="POST" action="{{ route('operations.updateStage', $t) }}" class="d-flex gap-2">@csrf
                  <select name="stage" class="form-select form-select-sm" style="max-width:150px;">
                    <option value="">Pilih stage...</option>
                    <option value="washing">Washing</option>
                    <option value="drying">Drying</option>
                    <option value="ironing">Ironing</option>
                    <option value="finished">Finished</option>
                  </select>
                  <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-arrow-right"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
