@extends('layouts.app')

@section('title','Edit Pengguna')

@section('content')
<h3>Edit Pengguna</h3>

<form method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select">
      <option value="kasir" @if($user->role=='kasir') selected @endif>Kasir</option>
      <option value="operator" @if($user->role=='operator') selected @endif>Operator</option>
      <option value="admin" @if($user->role=='admin') selected @endif>Admin</option>
    </select>
  </div>
  <button class="btn btn-primary">Update</button>
</form>
@endsection
