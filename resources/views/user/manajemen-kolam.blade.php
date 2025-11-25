<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Manajemen Kolam – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased text-zinc-800 bg-white" data-requires-auth="true">
<div class="grid grid-cols-12 gap-4 min-h-screen bg-zinc-50">
	<!-- Header Mobile -->
	<div class="col-span-12 md:hidden p-4 border-b border-zinc-200 bg-white flex items-center justify-between">
		<h1 class="font-semibold">E‑SHRIMP</h1>
		<button id="openSidebarBtn" class="px-3 py-2 rounded-lg border border-zinc-300 text-sm">Menu</button>
	</div>

	<!-- Sidebar -->
	<aside id="sidebar"
		class="col-span-12 md:col-span-2 border-r border-zinc-200 bg-zinc-50 p-4 md:block hidden fixed inset-y-0 left-0 w-72 z-40 md:static md:w-auto">
		<div class="flex items-center gap-2 mb-6">
			<div class="h-6 w-6 rounded bg-zinc-900"></div>
			<div class="font-semibold">E‑SHRIMP</div>
		</div>
		<nav class="space-y-1 text-sm">
			<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
				</svg>
				Dashboard
			</a>
			<a href="{{ route('user.manajemen-kapal') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
				</svg>
				Manajemen Kapal
			</a>
			<a href="{{ route('user.manajemen-kolam') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
				</svg>
				Manajemen Kolam
			</a>
			<a href="{{ route('user.histori-data') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
				</svg>
				Histori Data
			</a>
			<a href="{{ route('user.artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
				</svg>
				Daily Article
			</a>
			<a href="{{ route('user.forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
				</svg>
				Community
			</a>
			<a href="{{ route('user.prediksi-pertumbuhan') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
				</svg>
				Prediksi Pertumbuhan
			</a>
			<a href="{{ route('user.price-tracker') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
				</svg>
				Price Tracker
			</a>
		</nav>
		<div class="mt-8">
			<div class="text-xs uppercase text-zinc-500 tracking-wide mb-1">Other</div>
			<button onclick="openProfilModal()" class="w-full text-left block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
				</svg>
				Profil
			</button>
			<a href="/" data-logout class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
				</svg>
				Logout
			</a>
		</div>
	</aside>
	<!-- Backdrop for mobile drawer -->
	<div id="backdrop" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>

	<main class="col-span-12 md:col-span-10 p-4 sm:p-6 md:ml-0">
		<!-- Header -->
		<div class="mb-6 flex items-center justify-between">
			<div>
				<h1 class="text-2xl font-bold text-zinc-900">Manajemen Kolam</h1>
				<p class="text-sm text-zinc-600 mt-1">Lihat status monitoring untuk setiap kolam</p>
			</div>
			<button onclick="openTambahKolamModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors flex items-center gap-2">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
				</svg>
				Tambah Kolam
			</button>
		</div>

		<!-- Kolam List -->
		@if($kolams->count() > 0)
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				@foreach($kolams as $kolam)
					@php
						$stats = $kolamStats[$kolam->kolam_id] ?? [];
						$monitoredDays = $stats['monitored_days'] ?? [];
						$history = $kolamMonitoringHistory[$kolam->kolam_id] ?? collect();
					@endphp
					<div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
						<div class="flex items-start justify-between mb-4">
							<div>
								<h3 class="text-lg font-semibold text-zinc-900">{{ $kolam->kolam_id }}</h3>
								<p class="text-xs text-zinc-500 mt-1">ID Kolam</p>
							</div>
							<div class="flex items-center gap-2">
								@if(($stats['active_sessions'] ?? 0) > 0)
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
										▶ Aktif
									</span>
								@else
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
										Idle
									</span>
								@endif
								<button onclick="hapusKolam('{{ $kolam->kolam_id }}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Kolam">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
									</svg>
								</button>
							</div>
						</div>

						<div class="space-y-3">
							<div class="grid grid-cols-2 gap-4 pt-3 border-t border-zinc-200">
								<div>
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Total Session</label>
									<p class="text-lg font-semibold text-zinc-900 mt-1">{{ $stats['total_sessions'] ?? 0 }}</p>
								</div>
								<div>
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Monitoring Aktif</label>
									<p class="text-lg font-semibold text-emerald-600 mt-1">{{ $stats['active_sessions'] ?? 0 }}</p>
								</div>
							</div>

							<div class="pt-3 border-t border-zinc-200">
								<label class="text-xs text-zinc-500 uppercase tracking-wide mb-2 block">Hari yang Sudah Dimonitoring</label>
								@if(count($monitoredDays) > 0)
									<div class="flex flex-wrap gap-2">
										@foreach($monitoredDays as $day)
											<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
												Hari ke-{{ $day }}
											</span>
										@endforeach
									</div>
								@else
									<p class="text-xs text-zinc-400 italic">Belum ada monitoring</p>
								@endif
							</div>

							@if($history->count() > 0)
								<div class="pt-3 border-t border-zinc-200">
									<label class="text-xs text-zinc-500 uppercase tracking-wide mb-2 block">Riwayat Monitoring</label>
									<div class="space-y-2 max-h-64 overflow-y-auto">
										@foreach($history as $session)
											<div class="bg-zinc-50 rounded-lg p-3 text-xs border border-zinc-200">
												<div class="flex items-center justify-between mb-2">
													<span class="font-semibold text-sm">Hari ke-{{ $session->umur_budidaya }}</span>
													@if($session->is_active)
														@if($session->is_paused)
															<span class="text-yellow-600 text-xs">⏸ Paused</span>
														@else
															<span class="text-green-600 text-xs">▶ Aktif</span>
														@endif
													@else
														<span class="text-gray-600 text-xs">✓ Selesai</span>
													@endif
												</div>
												<div class="text-zinc-500 mb-2">
													<div>Kapal: {{ $session->nama_kapal }}</div>
													<div>{{ $session->mulai_monitoring ? \Carbon\Carbon::parse($session->mulai_monitoring)->format('d M Y H:i') : '-' }}</div>
												</div>
												@if($session->is_active == false && ($session->avg_ph !== null || $session->avg_suhu !== null || $session->avg_oksigen !== null || $session->avg_salinitas !== null))
													<div class="pt-2 border-t border-zinc-200">
														<div class="text-xs font-medium text-zinc-700 mb-1">Rata-rata Parameter:</div>
														<div class="grid grid-cols-2 gap-1 text-xs">
															<div class="text-zinc-600">
																<span class="text-zinc-500">pH:</span> 
																<span class="font-medium">{{ $session->avg_ph ? number_format($session->avg_ph, 2) : '-' }}</span>
															</div>
															<div class="text-zinc-600">
																<span class="text-zinc-500">Suhu:</span> 
																<span class="font-medium">{{ $session->avg_suhu ? number_format($session->avg_suhu, 2) : '-' }}°C</span>
															</div>
															<div class="text-zinc-600">
																<span class="text-zinc-500">Oksigen:</span> 
																<span class="font-medium">{{ $session->avg_oksigen ? number_format($session->avg_oksigen, 2) : '-' }} mg/L</span>
															</div>
															<div class="text-zinc-600">
																<span class="text-zinc-500">Salinitas:</span> 
																<span class="font-medium">{{ $session->avg_salinitas ? number_format($session->avg_salinitas, 2) : '-' }}‰</span>
															</div>
														</div>
													</div>
												@endif
											</div>
										@endforeach
									</div>
								</div>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		@else
			<div class="bg-white rounded-xl border border-zinc-200 p-12 text-center">
				<svg class="w-16 h-16 text-zinc-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
				</svg>
				<h3 class="text-lg font-semibold text-zinc-900 mb-2">Belum Ada Kolam</h3>
				<p class="text-sm text-zinc-600 mb-4">Tambahkan kolam baru untuk memulai monitoring</p>
				<button onclick="openTambahKolamModal()" class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors inline-flex items-center gap-2">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
					</svg>
					Tambah Kolam Pertama
				</button>
			</div>
		@endif

		<!-- Modal Tambah Kolam -->
		<div id="modalTambahKolam" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
			<div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
				<div class="flex items-center justify-between mb-4">
					<h2 class="text-xl font-semibold text-zinc-800">Tambah Kolam Baru</h2>
					<button onclick="closeTambahKolamModal()" class="text-zinc-400 hover:text-zinc-600">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
					</button>
				</div>
				<form id="formTambahKolam" onsubmit="tambahKolam(event)">
					<div class="mb-4">
						<label for="kolam_id" class="block text-sm font-medium text-zinc-700 mb-2">ID Kolam</label>
						<input type="text" id="kolam_id" name="kolam_id" required class="w-full px-4 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: KOLAM-001">
						<p class="text-xs text-zinc-500 mt-1">Masukkan ID kolam yang unik</p>
					</div>
					<div class="flex gap-3">
						<button type="button" onclick="closeTambahKolamModal()" class="flex-1 px-4 py-2 border border-zinc-300 text-zinc-700 rounded-lg font-medium hover:bg-zinc-50 transition-colors">
							Batal
						</button>
						<button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
							Simpan
						</button>
					</div>
				</form>
			</div>
		</div>
	</main>
</div>

<script>
	// Sidebar mobile toggle
	const sidebar = document.getElementById('sidebar');
	const openBtn = document.getElementById('openSidebarBtn');
	const backdrop = document.getElementById('backdrop');

	function openSidebar() {
		sidebar.classList.remove('hidden');
		backdrop.classList.remove('hidden');
	}

	function closeSidebar() {
		sidebar.classList.add('hidden');
		backdrop.classList.add('hidden');
	}

	if (openBtn) {
		openBtn.addEventListener('click', openSidebar);
	}

	if (backdrop) {
		backdrop.addEventListener('click', closeSidebar);
	}

	// Close sidebar when clicking outside on mobile
	document.addEventListener('click', function(event) {
		if (window.innerWidth < 768) {
			if (!sidebar.contains(event.target) && !openBtn.contains(event.target)) {
				closeSidebar();
			}
		}
	});

	// Modal Tambah Kolam
	function openTambahKolamModal() {
		document.getElementById('modalTambahKolam').classList.remove('hidden');
	}

	function closeTambahKolamModal() {
		document.getElementById('modalTambahKolam').classList.add('hidden');
		document.getElementById('formTambahKolam').reset();
	}

	// Tambah Kolam
	async function tambahKolam(event) {
		event.preventDefault();
		const form = event.target;
		const formData = new FormData(form);
		const submitBtn = form.querySelector('button[type="submit"]');
		const originalText = submitBtn.textContent;
		
		submitBtn.disabled = true;
		submitBtn.textContent = 'Menyimpan...';

		try {
			const response = await fetch('{{ route("user.tambah-kolam") }}', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					'Accept': 'application/json',
				},
				body: formData,
			});

			const result = await response.json();

			if (result.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Kolam berhasil ditambahkan',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					closeTambahKolamModal();
					window.location.reload();
				});
			} else {
				let errorMessage = result.message || 'Gagal menambahkan kolam';
				if (result.errors) {
					const errorList = Object.values(result.errors).flat().join(', ');
					errorMessage += ': ' + errorList;
				}
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: errorMessage
				});
			}
		} catch (error) {
			console.error('Error:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat menambahkan kolam: ' + error.message
			});
		} finally {
			submitBtn.disabled = false;
			submitBtn.textContent = originalText;
		}
	}

	// Hapus Kolam
	async function hapusKolam(kolamId) {
		const result = await Swal.fire({
			title: 'Konfirmasi',
			html: `Apakah Anda yakin ingin menghapus kolam <strong>${kolamId}</strong>?<br><br>Semua data monitoring, sensor data, dan notifikasi terkait kolam ini akan dihapus.`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc2626',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal'
		});
		if (!result.isConfirmed) {
			return;
		}

		try {
			const response = await fetch(`/manajemen-kolam/${kolamId}`, {
				method: 'DELETE',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					'Accept': 'application/json',
				},
			});

			const result = await response.json();

			if (result.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Kolam berhasil dihapus',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal menghapus kolam: ' + (result.message || 'Unknown error')
				});
			}
		} catch (error) {
			console.error('Error:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat menghapus kolam: ' + error.message
			});
		}
	}
	</script>
	@include('components.profil-modal')
</body>
</html>

