@extends('layouts.app')

@section('title','Buat Transaksi')

@section('content')
<h3>Buat Transaksi</h3>

<form method="POST" action="{{ route('transactions.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Pilih Pelanggan</label>
    <select name="customer_id" class="form-select select2">
      @foreach($customers as $c)
        <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Pilih Layanan</label>
    <select name="service_id" class="form-select" id="service_id" required>
      <option value="">-- Pilih Layanan --</option>
      @foreach($services as $s)
        <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-pricing-type="{{ $s->pricing_type }}">{{ $s->name }} (Rp {{ number_format($s->price,0,',','.') }} / {{ $s->pricing_type == 'per_kg' ? 'kg' : 'item' }})</option>
      @endforeach
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label" id="amount_label">Jumlah</label>
    <input type="number" name="amount" class="form-control" id="amount" value="1" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Total Harga</label>
    <input type="number" name="total_price" class="form-control" id="total_price" readonly required>
  </div>

  <button class="btn btn-primary">Simpan</button>
</form>

@push('scripts')
<script>
  $(document).ready(function () {
    $('.select2').select2({
      theme: 'bootstrap-5',
      width: '100%',
      dropdownAutoWidth: true,
      placeholder: 'Pilih pelanggan',
    });

    function updatePrice() {
      var selected = $('#service_id option:selected');
      var price = parseFloat(selected.data('price')) || 0;
      var amount = parseFloat($('#amount').val()) || 0;
      $('#total_price').val((price * amount).toFixed(0));
    }

    function updateLabel() {
      var selected = $('#service_id option:selected');
      var type = selected.data('pricing-type');
      var label = type === 'per_item' ? 'Jumlah Item' : 'Total Berat (kg)';
      $('#amount_label').text(label);
      $('#amount').attr('placeholder', type === 'per_item' ? 'Masukkan jumlah item' : 'Masukkan berat dalam kg');
    }

    $('#service_id').on('change', function () {
      updateLabel();
      updatePrice();
    });

    $('#amount').on('input', updatePrice);
    updateLabel();
    updatePrice();
  });
</script>
@endpush

@endsection
