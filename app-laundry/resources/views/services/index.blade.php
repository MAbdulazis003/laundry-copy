@extends('layouts.app')

@section('title','Layanan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1><i class="bi bi-tags"></i> Daftar Layanan</h1>
    <p>Kelola layanan laundry newci</p>
  </div>
  <a href="{{ route('services.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Layanan Baru
  </a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable">
        <thead class="table-light">
          <tr>
            <th>id</th>
            <th>Nama Layanan</th>
            <th>Jenis Harga</th>
            <th>Harga</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($services as $s)
            <tr>
              <td><strong>#{{ $s->id }}</strong></td>
              <td><strong>{{ $s->name }}</strong></td>
              <td>
                @switch($s->pricing_type)
                  @case('per_item')
                    <span class="badge bg-info">Per Satuan</span>
                    @break
                  @case('per_kg')
                    <span class="badge bg-primary">Per Kg</span>
                    @break
                  @default
                    <span class="badge bg-secondary">-</span>
                @endswitch
              </td>
              <td><strong>Rp {{ number_format($s->price,0,',','.') }}</strong></td>
              <td class="text-center">
                <a href="{{ route('services.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i> Edit
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
