@extends('layouts.app')

@section('title','Edit Transaksi')

@section('content')
<h3>Edit Transaksi #{{ $transaction->id }}</h3>

<form method="POST" action="{{ route('transactions.update', $transaction) }}">@csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Total Berat (kg)</label>
    <input type="number" step="0.01" name="total_weight" value="{{ $transaction->total_weight }}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Total Harga</label>
    <input type="number" step="0.01" name="total_price" value="{{ $transaction->total_price }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Status</label>
    <input type="text" name="status" value="{{ $transaction->status }}" class="form-control">
  </div>
  <button class="btn btn-primary">Update</button>
</form>
@endsection
