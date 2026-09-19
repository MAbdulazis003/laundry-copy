@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1><i class="bi bi-wallet2"></i> Pengeluaran</h1>
    <p>Catat dan kelola biaya operasional laundry</p>
  </div>
  <a href="{{ route('expenses.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Catat Pengeluaran
  </a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Nominal</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($expenses as $expense)
            <tr>
              <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
              <td><span class="badge bg-secondary">{{ $expense->category }}</span></td>
              <td>{{ $expense->description ?: '-' }}</td>
              <td><strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong></td>
              <td class="text-center">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="d-inline" onsubmit="return confirm('Hapus pengeluaran ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data pengeluaran.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $expenses->links() }}
  </div>
</div>
@endsection