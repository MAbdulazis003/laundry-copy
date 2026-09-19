@extends('layouts.app')

@section('title','Pengaturan')

@section('content')
<div class="page-header">
	<span class="text-uppercase text-primary fw-bold small">Konfigurasi aplikasi</span>
	<h1><i class="bi bi-sliders"></i> Pengaturan</h1>
	<p>Atur identitas usaha dan kebiasaan operasional laundry.</p>
</div>

<form method="POST" action="{{ route('settings.store') }}">
	@csrf
	<div class="row g-4">
		<div class="col-lg-7">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="mb-1"><i class="bi bi-shop"></i> Identitas Usaha</h5>
					<small class="text-muted">Informasi ini dapat digunakan pada halaman aplikasi dan dokumen.</small>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label class="form-label">Nama usaha</label>
						<input type="text" name="business_name" class="form-control" value="{{ old('business_name', $settings->business_name) }}" required>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Nomor telepon</label>
							<input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Email usaha</label>
							<input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
						</div>
					</div>
					<div class="mb-0">
						<label class="form-label">Alamat</label>
						<textarea name="address" class="form-control" rows="3">{{ old('address', $settings->address) }}</textarea>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-5">
			<div class="card mb-4">
				<div class="card-header">
					<h5 class="mb-1"><i class="bi bi-clock"></i> Operasional</h5>
					<small class="text-muted">Aturan waktu default untuk transaksi baru.</small>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-6 mb-3">
							<label class="form-label">Buka</label>
							  <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', $settings->opening_time ? substr($settings->opening_time, 0, 5) : '') }}">
						</div>
						<div class="col-6 mb-3">
							<label class="form-label">Tutup</label>
							  <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', $settings->closing_time ? substr($settings->closing_time, 0, 5) : '') }}">
						</div>
					</div>
					<label class="form-label">Estimasi selesai default</label>
					<div class="input-group">
						<input type="number" name="default_due_days" class="form-control" min="0" max="30" value="{{ old('default_due_days', $settings->default_due_days) }}" required>
						<span class="input-group-text">hari</span>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<h5 class="mb-1"><i class="bi bi-bell"></i> Notifikasi</h5>
					<small class="text-muted">Aktifkan pengingat penting untuk admin.</small>
				</div>
				<div class="card-body">
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="transaction_notification" value="1" @checked(old('transaction_notification', $settings->transaction_notification))>
						<label class="form-check-label">Notifikasi transaksi baru</label>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="low_stock_notification" value="1" @checked(old('low_stock_notification', $settings->low_stock_notification))>
						<label class="form-check-label">Peringatan stok rendah</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="d-flex justify-content-end mt-4">
		<button class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan Pengaturan</button>
	</div>
</form>
@endsection
