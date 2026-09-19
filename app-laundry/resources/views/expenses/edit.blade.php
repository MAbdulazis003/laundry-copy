@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="page-header">
  <h1><i class="bi bi-pencil"></i> Edit Pengeluaran</h1>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('expenses.update', $expense) }}">
      @csrf @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Kategori</label>
          <input type="text" name="category" class="form-control" value="{{ old('category', $expense->category) }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Nominal</label>
          <input type="number" name="amount" class="form-control" min="0.01" step="0.01" value="{{ old('amount', $expense->amount) }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Keterangan</label>
          <input type="text" name="description" class="form-control" value="{{ old('description', $expense->description) }}">
        </div>
      </div>
      <button class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Perubahan</button>
      <a href="{{ route('expenses.index') }}" class="btn btn-light">Batal</a>
    </form>
  </div>
</div>
@endsection