@extends('layouts.app')

@section('title','Tambah Pelanggan')

@section('content')
<h3>Tambah Pelanggan</h3>

<form method="POST" action="{{ route('customers.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control"></textarea>
  </div>
  <button class="btn btn-primary">Simpan</button>
</form>
@endsection
