@extends('layouts.app')

@section('title','Tambah Pengguna')

@section('content')
<h3>Tambah Pengguna</h3>

<form method="POST" action="{{ route('users.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select">
      <option value="kasir">Kasir</option>
      <option value="operator">Operator</option>
      <option value="admin">Admin</option>
    </select>
  </div>
  <button class="btn btn-primary">Simpan</button>
</form>
@endsection
