@extends('layouts.app')

@section('title','Pengguna')

@section('content')
<div class="d-flex justify-content-between mb-3">
  <h3>Pengguna</h3>
  <a href="{{ route('users.create') }}" class="btn btn-primary">+ Baru</a>
</div>

<table class="table table-striped dataTable">
  <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>
          <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-secondary">Edit</a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
