@extends('layouts.app')

@section('title','Pelanggan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1><i class="bi bi-people"></i> Daftar Pelanggan</h1>
    <p>Kelola data pelanggan Anda</p>
  </div>
  <a href="{{ route('customers.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Pelanggan Baru
  </a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable">
        <thead class="table-light">
          <tr>
            <th>ID Pelanggan</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Email</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($customers as $c)
            <tr>
              <td><strong>{{ $c->id }}</strong></td>
              <td>{{ $c->name }}</td>
              <td>
                @if($c->phone)
                  <a href="tel:{{ $c->phone }}">{{ $c->phone }}</a>
                @else
                  <small class="text-muted">-</small>
                @endif
              </td>
              <td>
                @if($c->email)
                  <a href="mailto:{{ $c->email }}">{{ $c->email }}</a>
                @else
                  <small class="text-muted">-</small>
                @endif
              </td>
              <td class="text-center">
                <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-outline-primary">
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
