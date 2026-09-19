@extends('layouts.app')

@section('title','Tambah Layanan')

@section('content')
<h3>Tambah Layanan</h3>

<form method="POST" action="{{ route('services.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Pricing Type</label>
    <select name="pricing_type" class="form-select">
      <option value="per_kg">Per Kg</option>
      <option value="per_item">Per Item</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" class="form-control" required>
  </div>
  <button class="btn btn-primary">Simpan</button>
</form>
@endsection
