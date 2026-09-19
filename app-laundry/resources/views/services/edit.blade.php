@extends('layouts.app')

@section('title','Edit Layanan')

@section('content')
<h3>Edit Layanan</h3>

<form method="POST" action="{{ route('services.update', $service) }}">@csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" value="{{ $service->name }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Pricing Type</label>
    <select name="pricing_type" class="form-select">
      <option value="per_kg" @if($service->pricing_type=='per_kg') selected @endif>Per Kg</option>
      <option value="per_item" @if($service->pricing_type=='per_item') selected @endif>Per Item</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" step="0.01" name="price" value="{{ $service->price }}" class="form-control" required>
  </div>
  <button class="btn btn-primary">Update</button>
</form>
@endsection
