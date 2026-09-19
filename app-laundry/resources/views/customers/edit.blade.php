@extends('layouts.app')

@section('title','Edit Pelanggan')

@section('content')
<h3>Edit Pelanggan</h3>

<form method="POST" action="{{ route('customers.update', $customer) }}">@csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" value="{{ $customer->name }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ $customer->email }}" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
  </div>
  <button class="btn btn-primary">Update</button>
</form>
@endsection
