<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Manajemen Kapal – E‑shrimp</title>
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
			<a href="{{ route('user.manajemen-kapal') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium flex items-center gap-2">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
				</svg>
				Manajemen Kapal
			</a>
			<a href="{{ route('user.manajemen-kolam') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
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
		<div class="mb-6">
			<h1 class="text-2xl font-bold text-zinc-900">Manajemen Kapal</h1>
			<p class="text-sm text-zinc-600 mt-1">Kelola kapal monitoring Anda</p>
		</div>

		<!-- Add Kapal Button -->
		<div class="mb-6">
			<button onclick="openTambahKapalModal()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors flex items-center gap-2">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
				</svg>
				Tambah Kapal
			</button>
		</div>

		<!-- Kapal List -->
		@if($kapals->count() > 0)
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				@foreach($kapals as $kapal)
					@php
						$stats = $kapalStats[$kapal->robot_id] ?? ['active_sessions' => 0, 'total_sessions' => 0];
					@endphp
					<div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
						<div class="flex items-start justify-between mb-4">
							<div>
								<h3 class="text-lg font-semibold text-zinc-900">{{ $kapal->robot_id }}</h3>
								<p class="text-xs text-zinc-500 mt-1">ID Kapal</p>
							</div>
							<div class="flex gap-2">
								<button onclick="openEditKapalModal('{{ $kapal->robot_id }}', '{{ $kapal->lokasi ?? '' }}', '{{ $kapal->status }}')" class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1 rounded border border-blue-300 hover:bg-blue-50" title="Edit">
									✏️
								</button>
								<button onclick="hapusKapal('{{ $kapal->robot_id }}')" class="text-red-600 hover:text-red-800 text-sm px-2 py-1 rounded border border-red-300 hover:bg-red-50" title="Hapus">
									🗑️
								</button>
							</div>
						</div>

						<div class="space-y-3">
							<div>
								<label class="text-xs text-zinc-500 uppercase tracking-wide">Status</label>
								<div class="mt-1">
									@if($kapal->status === 'active')
										<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
											Aktif
										</span>
									@elseif($kapal->status === 'maintenance')
										<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
											Maintenance
										</span>
									@else
										<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
											Idle
										</span>
									@endif
								</div>
							</div>

							@if($kapal->lokasi)
								<div>
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Lokasi</label>
									<p class="text-sm text-zinc-900 mt-1">{{ $kapal->lokasi }}</p>
								</div>
							@endif

							<div class="grid grid-cols-2 gap-4 pt-3 border-t border-zinc-200">
								<div>
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Monitoring Aktif</label>
									<p class="text-lg font-semibold text-emerald-600 mt-1">{{ $stats['active_sessions'] }}</p>
								</div>
								<div>
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Total Session</label>
									<p class="text-lg font-semibold text-zinc-900 mt-1">{{ $stats['total_sessions'] }}</p>
								</div>
							</div>

							<!-- Active Monitoring Sessions -->
							@php
								$activeSessions = $kapalActiveSessions[$kapal->robot_id] ?? collect();
							@endphp
							@if($activeSessions->count() > 0)
								<div class="pt-3 border-t border-zinc-200 space-y-2">
									<label class="text-xs text-zinc-500 uppercase tracking-wide">Monitoring Aktif</label>
									@foreach($activeSessions as $session)
										@php
											// Calculate countdown timer: 24 hours - elapsed time
											$maxDuration = 86400; // 24 hours in seconds
											$startTime = $session->mulai_monitoring;
											
											// Calculate elapsed time: time since start
											$elapsedSeconds = $startTime ? now()->diffInSeconds($startTime) : 0;
											
											// Remaining = 24 hours - elapsed (countdown)
											$remainingSeconds = max(0, $maxDuration - $elapsedSeconds);
											
											$hours = floor($remainingSeconds / 3600);
											$minutes = floor(($remainingSeconds % 3600) / 60);
											$seconds = $remainingSeconds % 60;
											$durationText = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
										@endphp
										<div class="bg-zinc-50 rounded-lg p-3 border border-zinc-200">
											<div class="flex items-center justify-between mb-2">
												<div class="text-xs">
													<div class="font-semibold">{{ $session->kolam_id }} - Hari ke-{{ $session->umur_budidaya }}</div>
													<div class="text-zinc-500">Timer: <span id="duration-{{ $session->session_id }}-{{ $kapal->robot_id }}">{{ $durationText }}</span></div>
												</div>
												<span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">▶ Aktif</span>
											</div>
											<div class="flex gap-1">
												<button onclick="stopMonitoringSession('{{ $kapal->robot_id }}', {{ $session->session_id }})" class="flex-1 text-xs px-2 py-1 rounded border border-red-300 hover:bg-red-50 text-red-600">
													⏹ Berhenti
												</button>
											</div>
										</div>
									@endforeach
								</div>
							@endif

							<div class="pt-3 border-t border-zinc-200">
								@if($activeSessions->count() > 0)
									<button disabled class="w-full bg-zinc-300 text-zinc-500 text-center py-2 rounded-lg font-medium cursor-not-allowed block">
										Kapal Sedang Monitoring
									</button>
								@else
									<button onclick="openMulaiMonitoringModal('{{ $kapal->robot_id }}')" class="w-full bg-emerald-600 text-white text-center py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors block">
										Mulai Monitoring
									</button>
								@endif
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@else
			<div class="bg-white rounded-xl border border-zinc-200 p-12 text-center">
				<svg class="w-16 h-16 text-zinc-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
				</svg>
				<h3 class="text-lg font-semibold text-zinc-900 mb-2">Belum Ada Kapal</h3>
				<p class="text-sm text-zinc-600 mb-4">Tambahkan kapal pertama Anda untuk memulai monitoring</p>
				<button onclick="openTambahKapalModal()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
					Tambah Kapal
				</button>
			</div>
		@endif
	</main>
</div>

<!-- Modal Tambah Kapal -->
<div id="modalTambahKapal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
	<div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
		<div class="flex items-center justify-between mb-4">
			<h2 class="text-xl font-semibold text-zinc-800">Tambah Kapal</h2>
			<button onclick="closeTambahKapalModal()" class="text-zinc-400 hover:text-zinc-600">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>
		<form id="formTambahKapal" onsubmit="tambahKapal(event)">
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-medium mb-1">ID Kapal <span class="text-red-500">*</span></label>
					<input type="text" name="robot_id" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: KAPAL-001">
					<p class="text-xs text-zinc-500 mt-1">ID unik untuk kapal monitoring</p>
				</div>
				<div>
					<label class="block text-sm font-medium mb-1">Lokasi</label>
					<input type="text" name="lokasi" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: Tambak Utara">
				</div>
			</div>
			<div class="flex gap-2 justify-end mt-6">
				<button type="button" onclick="closeTambahKapalModal()" class="px-6 py-2 bg-zinc-200 text-zinc-700 rounded-lg font-medium hover:bg-zinc-300 transition-colors">
					Batal
				</button>
				<button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
					Tambah
				</button>
			</div>
		</form>
	</div>
</div>

<!-- Modal Edit Kapal -->
<div id="modalEditKapal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
	<div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
		<div class="flex items-center justify-between mb-4">
			<h2 class="text-xl font-semibold text-zinc-800">Edit Kapal</h2>
			<button onclick="closeEditKapalModal()" class="text-zinc-400 hover:text-zinc-600">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>
		<form id="formEditKapal" onsubmit="updateKapal(event)">
			<input type="hidden" name="robot_id" id="editRobotId">
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-medium mb-1">ID Kapal</label>
					<input type="text" id="editRobotIdDisplay" disabled class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm bg-zinc-50 text-zinc-600">
				</div>
				<div>
					<label class="block text-sm font-medium mb-1">Lokasi</label>
					<input type="text" name="lokasi" id="editLokasi" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: Tambak Utara">
				</div>
				<div>
					<label class="block text-sm font-medium mb-1">Status</label>
					<select name="status" id="editStatus" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
						<option value="idle">Idle</option>
						<option value="active">Active</option>
						<option value="maintenance">Maintenance</option>
					</select>
				</div>
			</div>
			<div class="flex gap-2 justify-end mt-6">
				<button type="button" onclick="closeEditKapalModal()" class="px-6 py-2 bg-zinc-200 text-zinc-700 rounded-lg font-medium hover:bg-zinc-300 transition-colors">
					Batal
				</button>
				<button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
					Simpan
				</button>
			</div>
		</form>
	</div>
</div>

<!-- Modal Mulai Monitoring -->
<div id="modalMulaiMonitoring" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
	<div class="bg-white rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
		<div class="flex items-center justify-between mb-4">
			<h2 class="font-semibold text-lg">Mulai Monitoring</h2>
			<button onclick="closeMulaiMonitoringModal()" class="text-zinc-500 hover:text-zinc-700 text-xl">×</button>
		</div>
		<form id="formMulaiMonitoring" action="{{ route('user.mulai-monitoring') }}" method="POST" class="space-y-4">
			@csrf
			<input type="hidden" name="nama_kapal" id="modalNamaKapal">
			<div>
				<label class="block text-sm font-medium mb-1">Kapal</label>
				<div class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm bg-zinc-50 text-zinc-600" id="modalKapalDisplay"></div>
			</div>
			<div>
				<label class="block text-sm font-medium mb-1">Kolam <span class="text-red-500">*</span></label>
				<select name="kolam_id" id="kolamInput" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
					<option value="">-- Pilih Kolam --</option>
					@if(isset($availableKolams) && $availableKolams->count() > 0)
						@foreach($availableKolams as $kolam)
							<option value="{{ $kolam->kolam_id }}">{{ $kolam->kolam_id }}</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-medium mb-2">
						Threshold Suhu (°C)
						<span class="text-xs text-zinc-500 font-normal">(Rekomendasi: 28-32°C)</span>
					</label>
					<div class="grid grid-cols-2 gap-3">
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Minimal</label>
							<input type="number" step="0.1" name="threshold_suhu_min" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="28.0">
						</div>
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Maksimal</label>
							<input type="number" step="0.1" name="threshold_suhu_max" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="32.0">
						</div>
					</div>
				</div>
				<div>
					<label class="block text-sm font-medium mb-2">
						Threshold pH
						<span class="text-xs text-zinc-500 font-normal">(Rekomendasi: 7.5-8.5)</span>
					</label>
					<div class="grid grid-cols-2 gap-3">
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Minimal</label>
							<input type="number" step="0.1" name="threshold_ph_min" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="7.5">
						</div>
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Maksimal</label>
							<input type="number" step="0.1" name="threshold_ph_max" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="8.5">
						</div>
					</div>
				</div>
				<div>
					<label class="block text-sm font-medium mb-2">
						Threshold Oksigen Terlarut (mg/L)
						<span class="text-xs text-zinc-500 font-normal">(Rekomendasi: 5-8 mg/L)</span>
					</label>
					<div class="grid grid-cols-2 gap-3">
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Minimal</label>
							<input type="number" step="0.1" name="threshold_oksigen_min" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="5.0">
						</div>
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Maksimal</label>
							<input type="number" step="0.1" name="threshold_oksigen_max" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="8.0">
						</div>
					</div>
				</div>
				<div>
					<label class="block text-sm font-medium mb-2">
						Threshold Salinitas (‰)
						<span class="text-xs text-zinc-500 font-normal">(Rekomendasi: 15-25‰)</span>
					</label>
					<div class="grid grid-cols-2 gap-3">
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Minimal</label>
							<input type="number" step="0.1" name="threshold_salinitas_min" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="15.0">
						</div>
						<div>
							<label class="block text-xs text-zinc-600 mb-1">Maksimal</label>
							<input type="number" step="0.1" name="threshold_salinitas_max" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" placeholder="25.0">
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="timer_monitoring" value="10">
			<div>
				<label class="block text-sm font-medium mb-1">Umur Budidaya <span class="text-red-500">*</span></label>
				<select name="umur_budidaya" id="umurBudidayaSelect" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
					<option value="">Pilih hari ke-</option>
					<option value="1">Hari ke-1</option>
					<option value="7">Hari ke-7</option>
					<option value="14">Hari ke-14</option>
					<option value="21">Hari ke-21</option>
					<option value="28">Hari ke-28</option>
					<option value="35">Hari ke-35</option>
					<option value="42">Hari ke-42</option>
					<option value="49">Hari ke-49</option>
					<option value="56">Hari ke-56</option>
					<option value="63">Hari ke-63</option>
					<option value="70">Hari ke-70</option>
					<option value="77">Hari ke-77</option>
					<option value="84">Hari ke-84</option>
					<option value="91">Hari ke-91</option>
					<option value="98">Hari ke-98</option>
				</select>
				<p id="umurBudidayaError" class="text-xs text-red-600 mt-1 hidden"></p>
			</div>
			<div class="flex gap-2 pt-2">
				<button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500 text-sm font-medium">Mulai Monitoring</button>
				<button type="button" onclick="closeMulaiMonitoringModal()" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
			</div>
		</form>
	</div>
</div>


<script>
	// Store monitored combinations globally
	@if(isset($kapalMonitoredCombinations))
		const allKapalMonitoredCombinations = @json($kapalMonitoredCombinations);
	@else
		const allKapalMonitoredCombinations = {};
	@endif

	let currentKapalIdForValidation = null;

	function openMulaiMonitoringModal(kapalId) {
		currentKapalIdForValidation = kapalId;
		document.getElementById('modalNamaKapal').value = kapalId;
		document.getElementById('modalKapalDisplay').textContent = kapalId;
		document.getElementById('modalMulaiMonitoring').classList.remove('hidden');
	}

	// Setup validation on modal open
	document.addEventListener('DOMContentLoaded', function() {
		const kolamInput = document.getElementById('kolamInput');
		const umurSelect = document.getElementById('umurBudidayaSelect');
		const umurError = document.getElementById('umurBudidayaError');
		
		if (kolamInput && umurSelect && umurError) {
			function validateCombination() {
				if (!currentKapalIdForValidation) return;
				
				const monitoredCombinations = allKapalMonitoredCombinations[currentKapalIdForValidation] || {};
				const selectedKolam = kolamInput.value.trim();
				const selectedUmur = umurSelect.value;
				
				if (!selectedKolam || !selectedUmur) {
					umurError.classList.add('hidden');
					umurSelect.setCustomValidity('');
					return;
				}
				
				const combinationKey = selectedKolam + '-' + selectedUmur;
				if (monitoredCombinations[combinationKey]) {
					umurError.classList.remove('hidden');
					umurError.textContent = `Kapal ${currentKapalIdForValidation} sudah pernah monitoring kolam ${selectedKolam} pada hari ke-${selectedUmur}. Pilih kolam lain atau umur budidaya yang berbeda.`;
					umurSelect.setCustomValidity('Kombinasi kolam dan umur budidaya ini sudah pernah digunakan');
				} else {
					umurError.classList.add('hidden');
					umurSelect.setCustomValidity('');
				}
			}
			
			kolamInput.addEventListener('change', validateCombination);
			umurSelect.addEventListener('change', validateCombination);
		}
	});

	function closeMulaiMonitoringModal() {
		document.getElementById('modalMulaiMonitoring').classList.add('hidden');
		document.getElementById('formMulaiMonitoring').reset();
	}

	// Helper: clear timer interval safely
	function clearTimerInterval(sessionId, kapalId) {
		const key = 'timerInterval_' + sessionId + '_' + kapalId;
		if (window[key]) {
			clearInterval(window[key]);
			delete window[key];
		}
	}

	async function stopMonitoringSession(kapalId, sessionId) {
		console.log('stopMonitoringSession called with:', kapalId, sessionId);
		const result = await Swal.fire({
			title: 'Konfirmasi',
			text: 'Apakah Anda yakin ingin menghentikan monitoring session ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#dc2626',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Hentikan',
			cancelButtonText: 'Batal'
		});
		if (!result.isConfirmed) return;
		
		const csrfToken = document.querySelector('meta[name="csrf-token"]');
		if (!csrfToken) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'CSRF token tidak ditemukan. Silakan refresh halaman.'
			});
			return;
		}
		
		try {
			const response = await fetch(`/monitoring-session/${sessionId}`, {
				method: 'DELETE',
				headers: {
					'X-CSRF-TOKEN': csrfToken.content,
					'Content-Type': 'application/json',
					'Accept': 'application/json',
				},
			});
			
			if (!response.ok) {
				const text = await response.text();
				console.error('Response error:', text);
				throw new Error(`HTTP error! status: ${response.status}`);
			}
			
			const result = await response.json();
			if (result.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Monitoring session berhasil dihapus',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal menghapus monitoring session: ' + (result.message || 'Unknown error')
				});
			}
		} catch (error) {
			console.error('Error deleting session:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat menghapus monitoring session: ' + error.message
			});
		}
	}

	// Setup form submission
	document.getElementById('formMulaiMonitoring')?.addEventListener('submit', async function(e) {
		e.preventDefault();
		const formData = new FormData(e.target);
		const submitBtn = e.target.querySelector('button[type="submit"]');
		const originalText = submitBtn.textContent;
		submitBtn.disabled = true;
		submitBtn.textContent = 'Memproses...';
		try {
			const response = await fetch(e.target.action, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					'Accept': 'application/json',
				},
				body: formData,
			});
			const result = await response.json();
			if (result.success) {
				closeMulaiMonitoringModal();
				window.location.reload();
			} else {
				let errorMessage = result.message || 'Gagal memulai monitoring';
				if (result.errors) {
					const errorMessages = Object.values(result.errors).flat();
					errorMessage = errorMessages.join('\n');
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
				text: 'Terjadi kesalahan saat memulai monitoring: ' + error.message
			});
		} finally {
			submitBtn.disabled = false;
			submitBtn.textContent = originalText;
		}
	});

	function openTambahKapalModal() {
		document.getElementById('modalTambahKapal').classList.remove('hidden');
	}

	function closeTambahKapalModal() {
		document.getElementById('modalTambahKapal').classList.add('hidden');
		document.getElementById('formTambahKapal').reset();
	}

	function openEditKapalModal(robotId, lokasi, status) {
		document.getElementById('editRobotId').value = robotId;
		document.getElementById('editRobotIdDisplay').value = robotId;
		document.getElementById('editLokasi').value = lokasi || '';
		document.getElementById('editStatus').value = status || 'idle';
		document.getElementById('modalEditKapal').classList.remove('hidden');
	}

	function closeEditKapalModal() {
		document.getElementById('modalEditKapal').classList.add('hidden');
		document.getElementById('formEditKapal').reset();
	}

	async function tambahKapal(event) {
		event.preventDefault();
		const formData = new FormData(event.target);
		
		try {
			const response = await fetch('{{ route("user.tambah-kapal") }}', {
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
					text: 'Kapal berhasil ditambahkan',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					closeTambahKapalModal();
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal menambahkan kapal: ' + (result.message || 'Unknown error')
				});
			}
		} catch (error) {
			console.error('Error:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat menambahkan kapal'
			});
		}
	}

	async function updateKapal(event) {
		event.preventDefault();
		const formData = new FormData(event.target);
		const robotId = formData.get('robot_id');
		
		try {
			const response = await fetch(`/manajemen-kapal/${robotId}`, {
				method: 'PUT',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
					'Accept': 'application/json',
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					lokasi: formData.get('lokasi'),
					status: formData.get('status'),
				}),
			});

			const result = await response.json();

			if (result.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Kapal berhasil diperbarui',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					closeEditKapalModal();
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal memperbarui kapal: ' + (result.message || 'Unknown error')
				});
			}
		} catch (error) {
			console.error('Error:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat memperbarui kapal'
			});
		}
	}

	async function hapusKapal(robotId) {
		const result = await Swal.fire({
			title: 'Konfirmasi',
			text: 'Yakin hapus kapal ini? Kapal yang memiliki monitoring session aktif tidak dapat dihapus.',
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
			const response = await fetch(`/manajemen-kapal/${robotId}`, {
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
					text: 'Kapal berhasil dihapus',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					window.location.reload();
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal menghapus kapal: ' + (result.message || 'Unknown error')
				});
			}
		} catch (error) {
			console.error('Error:', error);
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Terjadi kesalahan saat menghapus kapal'
			});
		}
	}

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

	// Update timers for active sessions
	function updateTimers() {
		@if(isset($kapals) && $kapals->count() > 0)
			@foreach($kapals as $kapal)
				@if(isset($kapalActiveSessions[$kapal->robot_id]) && $kapalActiveSessions[$kapal->robot_id]->count() > 0)
					@foreach($kapalActiveSessions[$kapal->robot_id] as $session)
						(function() {
							const sessionId = {{ $session->session_id }};
							const kapalId = '{{ $kapal->robot_id }}';
							const intervalKey = 'timerInterval_' + sessionId + '_' + kapalId;
							
							// Skip if interval already exists
							if (window[intervalKey]) {
								return;
							}
							
							const timerEl = document.getElementById('duration-' + sessionId + '-' + kapalId);
							if (!timerEl) {
								return;
							}
							
							const startTime = new Date('{{ $session->mulai_monitoring->toIso8601String() }}').getTime();
							const maxDuration = 86400; // 24 hours in seconds
							
							function updateTimer() {
								// Recalculate remaining time every second (more accurate)
								const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
								const remainingSeconds = Math.max(0, maxDuration - elapsedSeconds);
								
								if (remainingSeconds > 0) {
									const hours = Math.floor(remainingSeconds / 3600);
									const minutes = Math.floor((remainingSeconds % 3600) / 60);
									const seconds = remainingSeconds % 60;
									timerEl.textContent = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
								} else {
									// Timer reached 0
									timerEl.textContent = '00:00:00';
									if (window[intervalKey]) {
										clearInterval(window[intervalKey]);
										delete window[intervalKey];
									}
									// Only reload once, add a flag to prevent multiple reloads
									if (!window['reloading_' + sessionId + '_' + kapalId]) {
										window['reloading_' + sessionId + '_' + kapalId] = true;
										setTimeout(() => {
											window.location.reload();
										}, 100);
									}
								}
							}
							
							// Update immediately
							updateTimer();
							
							// Update every second
							const intervalId = setInterval(updateTimer, 1000);
							window[intervalKey] = intervalId;
						})();
					@endforeach
				@endif
			@endforeach
		@endif
	}
	
	// Initialize on page load (only once)
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', updateTimers);
	} else {
		// DOM already loaded
		updateTimers();
	}
	</script>
	@include('components.profil-modal')
</body>
</html>

