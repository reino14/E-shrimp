<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Dashboard – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased text-zinc-800 bg-white" data-requires-auth="true">
	<div class="min-h-screen grid grid-cols-12">
		<!-- Mobile top bar -->
		<div class="col-span-12 md:hidden flex items-center justify-between px-4 py-4 border-b border-zinc-200">
			<button id="openSidebarBtn" class="px-3 py-2 rounded-lg border border-zinc-300 text-sm">Menu</button>
			<div class="flex items-center gap-2">
				<div class="h-6 w-6 rounded bg-zinc-900"></div>
				<div class="font-semibold">E‑SHRIMP</div>
			</div>
			<a href="/" data-logout class="px-3 py-2 rounded-lg border border-zinc-300 text-sm">Logout</a>
		</div>

		<!-- Sidebar -->
		<aside id="sidebar"
			class="col-span-12 md:col-span-2 border-r border-zinc-200 bg-zinc-50 p-4 md:block hidden fixed inset-y-0 left-0 w-72 z-40 md:static md:w-auto">
			<div class="flex items-center gap-2 mb-6">
				<div class="h-6 w-6 rounded bg-zinc-900"></div>
				<div class="font-semibold">E‑SHRIMP</div>
			</div>
		<nav class="space-y-1 text-sm">
			<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium flex items-center gap-2">
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
			@if(isset($showKapalSelection) && $showKapalSelection)
				<!-- Kapal Selection Screen -->
				<div class="max-w-2xl mx-auto mt-12">
					<div class="text-center mb-8">
						<h1 class="text-2xl sm:text-3xl font-semibold mb-2">Pilih Kapal</h1>
						<p class="text-zinc-600">Pilih kapal yang ingin Anda gunakan untuk monitoring</p>
					</div>
					
					<div class="bg-white rounded-2xl border border-zinc-200 p-6">
						<form id="kapalSelectionForm" method="GET" action="{{ route('dashboard') }}">
							<div class="mb-6">
								<label class="block text-sm font-medium mb-2">Pilih Kapal yang Sudah Ada</label>
								<select name="nama_kapal" id="kapalSelect" class="w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
									<option value="">-- Pilih Kapal --</option>
									@if(isset($availableKapals) && count($availableKapals) > 0)
										@foreach($availableKapals as $kapalId)
											<option value="{{ $kapalId }}">{{ $kapalId }}</option>
										@endforeach
									@endif
								</select>
							</div>
							
							<button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
								Pilih Kapal
							</button>
						</form>
						
						<script>
							// Handle form submission
							document.getElementById('kapalSelectionForm').addEventListener('submit', function(e) {
								const selectValue = document.getElementById('kapalSelect').value;
								
								if (!selectValue) {
									e.preventDefault();
									alert('Silakan pilih kapal yang sudah ada');
									return false;
								}
							});
						</script>
					</div>
				</div>
			@else
				<!-- Dashboard Content -->
				<div class="flex items-center justify-between mb-4">
					<div class="flex items-center gap-4">
						<h1 class="text-lg sm:text-xl font-semibold">Dashboard Monitoring</h1>
						@if(isset($selectedKapal))
							<span class="text-sm text-zinc-600 bg-zinc-100 px-3 py-1 rounded-full">Kapal: {{ $selectedKapal }}</span>
							<a href="{{ route('dashboard') }}?change_kapal=1" class="text-sm text-emerald-600 hover:text-emerald-700">Ganti Kapal</a>
						@endif
					</div>
					<div class="flex items-center gap-3">
						<div class="text-xs sm:text-sm text-zinc-600">
							Last sync: <span id="lastSync">just now</span>
						</div>
						<!-- Notifikasi Icon -->
						<button onclick="toggleNotificationPopup()" class="relative p-2 rounded-full hover:bg-zinc-100 transition-colors">
							<svg class="w-6 h-6 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
							</svg>
							@php $notifCount = isset($notifikasis) ? $notifikasis->count() : 0; @endphp
							<span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ $notifCount > 0 ? '' : 'hidden' }}">{{ $notifCount }}</span>
						</button>
					</div>
				</div>

			<!-- Notification Popup -->
			<div id="notificationPopup" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) toggleNotificationPopup()">
				<div class="bg-white rounded-2xl max-w-md w-full max-h-[80vh] flex flex-col shadow-xl">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between flex-shrink-0">
						<span>Riwayat Notifikasi</span>
						<div class="flex items-center gap-2">
							<button onclick="clearAllNotifications()" class="text-xs text-zinc-500 hover:text-red-600 transition-colors px-2 py-1 rounded hover:bg-zinc-50">
								Hapus Semua
							</button>
							<button onclick="toggleNotificationPopup()" class="text-zinc-400 hover:text-zinc-600 transition-colors">
								<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
								</svg>
							</button>
						</div>
					</div>
					<div class="flex-1 overflow-y-auto" style="min-height: 0; max-height: calc(80vh - 60px);">
						<ul id="notificationList" class="p-4 text-sm text-zinc-700 space-y-2">
							<!-- Notifications will be loaded here -->
						</ul>
					</div>
				</div>
			</div>

			<!-- Status Kapal -->
			@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
				<div class="mt-4 rounded-2xl border border-zinc-200 overflow-hidden">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between">
						<span>Status Kapal</span>
					</div>
					<div class="p-4">
						@foreach($monitoringSessions as $session)
							@php
								// Calculate duration: 24 hours from mulai_monitoring
								$startTime = $session->mulai_monitoring;
								$elapsedSeconds = $startTime ? now()->diffInSeconds($startTime) : 0;
								$maxDuration = 86400; // 24 hours
								$remainingSeconds = max(0, $maxDuration - $elapsedSeconds);
								
								$hours = floor($remainingSeconds / 3600);
								$minutes = floor(($remainingSeconds % 3600) / 60);
								$seconds = $remainingSeconds % 60;
								$durationText = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
								$isExpired = $remainingSeconds <= 0;
							@endphp
							<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 w-full">
								<div class="flex items-start justify-between mb-3">
									<div>
										<div class="font-semibold text-lg text-zinc-900">{{ $session->nama_kapal }}</div>
										<div class="text-xs text-zinc-600 mt-1">Kapal ID</div>
									</div>
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
										▶ Aktif
									</span>
								</div>
								<div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
									<div>
										<span class="text-zinc-600">Kolam:</span>
										<span class="font-semibold text-zinc-900 ml-2">{{ $session->kolam_id }}</span>
									</div>
									<div>
										<span class="text-zinc-600">Hari ke:</span>
										<span class="font-semibold text-zinc-900 ml-2">{{ $session->umur_budidaya }}</span>
									</div>
									<div>
										<span class="text-zinc-600">Timer:</span>
										<span class="font-semibold {{ $isExpired ? 'text-red-600' : 'text-emerald-600' }} ml-2" id="duration-{{ $session->session_id }}">
											{{ $durationText }}
											@if($isExpired)
												<span class="text-red-600">(Selesai)</span>
											@endif
										</span>
									</div>
									<div>
										<span class="text-zinc-600">Interval:</span>
										<span class="font-semibold text-zinc-900 ml-2">{{ $session->timer_monitoring }} detik</span>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@else
				<!-- Empty State: No Active Monitoring -->
				<div class="mt-4 rounded-2xl border border-zinc-200 bg-white p-12 text-center">
					<svg class="w-16 h-16 text-zinc-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
					<h3 class="text-lg font-semibold text-zinc-900 mb-2">Belum Ada Kapal yang Aktif</h3>
					<p class="text-sm text-zinc-500 mb-4">Silakan mulai monitoring di halaman Manajemen Kapal</p>
					<a href="{{ route('user.manajemen-kapal') }}" class="inline-block px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
						Mulai Monitoring
					</a>
				</div>
			@endif

			<!-- Sensor Data Cards -->
			@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
				<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
					<div class="rounded-xl bg-zinc-800 text-white p-4">
						<div class="text-sm text-zinc-300">pH</div>
						<div class="text-2xl font-semibold transition-all duration-300" id="phValue">-</div>
						<div class="mt-2 text-xs text-zinc-400">Status: <span id="phStatus">-</span></div>
					</div>
					<div class="rounded-xl bg-zinc-800 text-white p-4">
						<div class="text-sm text-zinc-300">Suhu</div>
						<div class="text-2xl font-semibold transition-all duration-300" id="tempValue">-</div>
						<div class="mt-2 text-xs text-zinc-400">Status: <span id="tempStatus">-</span></div>
					</div>
					<div class="rounded-xl bg-zinc-800 text-white p-4">
						<div class="text-sm text-zinc-300">Salinitas</div>
						<div class="text-2xl font-semibold transition-all duration-300" id="salinityValue">-</div>
						<div class="mt-2 text-xs text-zinc-400">Status: <span id="salinityStatus">-</span></div>
					</div>
					<div class="rounded-xl bg-zinc-800 text-white p-4">
						<div class="text-sm text-zinc-300">Oksigen Terlarut</div>
						<div class="text-2xl font-semibold transition-all duration-300" id="doValue">-</div>
						<div class="mt-2 text-xs text-zinc-400">Status: <span id="doStatus">-</span></div>
					</div>
				</div>
			@endif

			<!-- Grafik Real-time -->
			@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
				<div class="mt-6">
					<h2 class="text-lg font-semibold mb-4">Grafik Real-time Sensor</h2>
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
						<!-- pH Chart -->
						<div class="rounded-2xl border border-zinc-200 p-4 bg-white">
							<h3 class="font-semibold mb-4 text-sm">pH Trend</h3>
							<div class="h-64">
								<canvas id="phChart"></canvas>
							</div>
						</div>
						
						<!-- Suhu Chart -->
						<div class="rounded-2xl border border-zinc-200 p-4 bg-white">
							<h3 class="font-semibold mb-4 text-sm">Suhu Trend (°C)</h3>
							<div class="h-64">
								<canvas id="suhuChart"></canvas>
							</div>
						</div>
						
						<!-- Oksigen Chart -->
						<div class="rounded-2xl border border-zinc-200 p-4 bg-white">
							<h3 class="font-semibold mb-4 text-sm">Oksigen Terlarut (mg/L)</h3>
							<div class="h-64">
								<canvas id="oksigenChart"></canvas>
							</div>
						</div>
						
						<!-- Salinitas Chart -->
						<div class="rounded-2xl border border-zinc-200 p-4 bg-white">
							<h3 class="font-semibold mb-4 text-sm">Salinitas (‰)</h3>
							<div class="h-64">
								<canvas id="salinitasChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			@endif


			<!-- Modal Threshold -->
			<div id="modalThreshold" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-md w-full p-6">
					<h2 class="font-semibold mb-4">Atur Threshold</h2>
					<form action="{{ route('user.atur-threshold') }}" method="POST">
						@csrf
						<input type="hidden" name="kolam_id" value="{{ $kolam->kolam_id ?? 'KOLAM-001' }}">
						<div class="space-y-4">
							<div>
								<label class="block text-sm mb-1">Sensor Tipe</label>
								<select name="sensor_tipe" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
									<option value="ph">pH</option>
									<option value="suhu">Suhu</option>
									<option value="oksigen">Oksigen</option>
									<option value="salinitas">Salinitas</option>
								</select>
							</div>
							<div>
								<label class="block text-sm mb-1">Nilai Threshold</label>
								<input type="number" step="0.1" name="nilai" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
							</div>
							<div class="flex gap-2">
								<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Simpan</button>
								<button type="button" onclick="document.getElementById('modalThreshold').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
							</div>
							</div>
						</form>
						</div>
							</div>

			<!-- Modal Detail Monitoring Session -->
			<div id="modalDetailMonitoring" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
					<div class="flex items-center justify-between mb-4">
						<h2 class="font-semibold text-lg">Detail Monitoring Session</h2>
						<button onclick="closeDetailMonitoringModal()" class="text-zinc-500 hover:text-zinc-700 text-xl">×</button>
						</div>
					<div id="detailMonitoringContent" class="space-y-4">
						<!-- Content will be loaded by JavaScript -->
					</div>
				</div>
					</div>

			<!-- Modal Restart Monitoring -->
			<div id="modalRestartMonitoring" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
					<div class="flex items-center justify-center mb-4">
						<div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
							<svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
							</svg>
						</div>
					</div>
					<h2 class="text-xl font-semibold text-center mb-3 text-zinc-800">Restart Monitoring</h2>
					<p id="restartMessage" class="text-sm text-zinc-600 text-center mb-6"></p>
					<div class="space-y-3 mb-6">
						<label class="flex items-center p-3 border border-zinc-200 rounded-lg cursor-pointer hover:bg-zinc-50">
							<input type="radio" name="restart_type" value="same_day" class="mr-3" checked>
							<div>
								<div class="font-medium">Restart Hari yang Sama</div>
								<div class="text-xs text-zinc-500">Mengulang monitoring untuk hari yang sama dari awal</div>
							</div>
						</label>
						<label class="flex items-center p-3 border border-zinc-200 rounded-lg cursor-pointer hover:bg-zinc-50">
							<input type="radio" name="restart_type" value="from_day_one" class="mr-3">
							<div>
								<div class="font-medium">Restart dari Hari ke-1</div>
								<div class="text-xs text-zinc-500">Mengulang monitoring dari hari ke-1</div>
							</div>
						</label>
					</div>
					<div class="flex gap-2 justify-center">
						<button onclick="confirmRestart()" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
							Restart
						</button>
						<button onclick="closeRestartModal()" class="px-6 py-2 bg-zinc-200 text-zinc-700 rounded-lg font-medium hover:bg-zinc-300 transition-colors">
							Batal
						</button>
					</div>
				</div>
			</div>

			<!-- Modal Notifikasi Monitoring Aktif -->
			<div id="modalActiveMonitoring" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
					<div class="flex items-center justify-center mb-4">
						<div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
							<svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
							</svg>
						</div>
					</div>
					<h2 class="text-xl font-semibold text-center mb-3 text-zinc-800">Monitoring Sedang Berjalan</h2>
					<p id="activeMonitoringMessage" class="text-sm text-zinc-600 text-center mb-6"></p>
					<div class="flex justify-center">
						<button onclick="closeActiveMonitoringModal()" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
							Mengerti
						</button>
					</div>
				</div>
			</div>

			@endif
		</main>
	</div>
	@include('components.whatsapp-widget')
	<script>
		// Sync peternak to Laravel DB on page load
		(async function() {
			const userRole = sessionStorage.getItem('userRole');
			const userEmail = sessionStorage.getItem('userEmail');
			const userName = sessionStorage.getItem('userName');
			
			// If user is peternak, sync to Laravel DB
			if (userRole === 'peternak' && userEmail) {
				try {
					const response = await fetch('{{ route("api.sync-peternak") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}',
						},
						body: JSON.stringify({
							email: userEmail,
							nama: userName || 'Peternak ' + userEmail,
						}),
					});
					
					if (response.ok) {
						console.log('Peternak berhasil disinkronkan ke Laravel DB');
					}
				} catch (error) {
					console.error('Error syncing peternak:', error);
				}
			}
		})();

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
		openBtn && openBtn.addEventListener('click', openSidebar);
		backdrop && backdrop.addEventListener('click', closeSidebar);
		window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSidebar(); });

		// Dashboard Data Updater (Local Dummy Data)
		class DashboardDataUpdater {
			constructor() {
				// Get kolam_id from first active monitoring session
				@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
					this.kolamId = '{{ $monitoringSessions->first()->kolam_id }}';
					console.log('[DashboardUpdater] Constructor - kolamId set to:', this.kolamId);
				@else
					this.kolamId = null;
					console.log('[DashboardUpdater] Constructor - No kolamId (no active sessions)');
				@endif
				this.thresholds = {};
				this.thresholdAlertState = {};
				this.notifications = [];
				this.notificationLimit = 5;
				this.charts = {};
				this.chartData = {
					ph: { labels: [], values: [] },
					suhu: { labels: [], values: [] },
					oksigen: { labels: [], values: [] },
					salinitas: { labels: [], values: [] }
				};
				this.maxDataPoints = 20; // Keep last 20 data points
				console.log('[DashboardUpdater] Constructor - Calling init()');
				this.init();
			}

			async init() {
				// Only initialize if there's a kolamId (active monitoring session)
				if (!this.kolamId) {
					console.log('[DashboardUpdater] No kolam ID - skipping chart initialization');
					return;
				}
				
				console.log('[DashboardUpdater] Initializing with kolamId:', this.kolamId);
				
				// Wait for Chart.js to load
				await this.waitForChartJS();
				
				// Initialize charts first
				this.initCharts();
				console.log('[DashboardUpdater] Charts initialized:', Object.keys(this.charts));
				
				// Don't use dummy data - only use real data from monitoring sessions
				// this.initDummyData(); // Disabled - only use data from active monitoring sessions
				
				// Load thresholds from server
				await this.loadThresholds();
				
				// Load notifications from server
				await this.loadNotifications();
				
				// Load historical data for charts ONLY if there are active monitoring sessions
				@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
					await this.loadHistoricalData();
				@else
					// No active sessions - clear all chart data
					this.clearAllData();
				@endif
				
				this.updateLastSync();
				setInterval(() => this.updateLastSync(), 1000);
				
				console.log('[DashboardUpdater] Initialization complete');
			}

			waitForChartJS() {
				return new Promise((resolve) => {
					if (typeof Chart !== 'undefined') {
						console.log('[DashboardUpdater] Chart.js already loaded');
						resolve();
						return;
					}
					
					// Wait for Chart.js to load
					let attempts = 0;
					const checkInterval = setInterval(() => {
						attempts++;
						if (typeof Chart !== 'undefined') {
							clearInterval(checkInterval);
							console.log('[DashboardUpdater] Chart.js loaded after', attempts * 100, 'ms');
							resolve();
						} else if (attempts > 100) {
							// Timeout after 10 seconds - try alternative CDN
							clearInterval(checkInterval);
							console.warn('[DashboardUpdater] Chart.js not loaded from primary CDN, trying alternative...');
							const script = document.createElement('script');
							script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js';
							script.onload = () => {
								console.log('[DashboardUpdater] Chart.js loaded from alternative CDN');
								resolve();
							};
							script.onerror = () => {
								console.error('[DashboardUpdater] Failed to load Chart.js from all CDNs');
								resolve(); // Continue anyway
							};
							document.head.appendChild(script);
						}
					}, 100);
				});
			}

			initCharts() {
				// Check if Chart.js is loaded
				if (typeof Chart === 'undefined') {
					console.error('[DashboardUpdater] Chart.js is not loaded');
					return;
				}

				console.log('[DashboardUpdater] Initializing charts...');

				// Destroy existing charts if any
				if (this.charts) {
					Object.keys(this.charts).forEach(chartType => {
						if (this.charts[chartType] && typeof this.charts[chartType].destroy === 'function') {
							this.charts[chartType].destroy();
						}
					});
				}

				const chartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							enabled: true
						}
					},
					scales: {
						y: {
							beginAtZero: false,
							grid: {
								color: 'rgba(0, 0, 0, 0.05)'
							}
						},
						x: {
							grid: {
								display: false
							}
						}
					}
				};

				// pH Chart
				const phCtx = document.getElementById('phChart');
				if (phCtx) {
					console.log('[DashboardUpdater] Creating pH chart');
					this.charts.ph = new Chart(phCtx, {
						type: 'line',
						data: {
							labels: [],
							datasets: [{
								label: 'pH',
								data: [],
								borderColor: 'rgb(59, 130, 246)',
								backgroundColor: 'rgba(59, 130, 246, 0.1)',
								tension: 0.4,
								fill: true
							}]
						},
						options: {
							...chartOptions,
							plugins: {
								...chartOptions.plugins,
								title: {
									display: true,
									text: 'pH Trend'
								}
							}
						}
					});
				}

				// Suhu Chart
				const suhuCtx = document.getElementById('suhuChart');
				if (suhuCtx) {
					console.log('[DashboardUpdater] Creating Suhu chart');
					this.charts.suhu = new Chart(suhuCtx, {
						type: 'line',
						data: {
							labels: [],
							datasets: [{
								label: 'Suhu (°C)',
								data: [],
								borderColor: 'rgb(239, 68, 68)',
								backgroundColor: 'rgba(239, 68, 68, 0.1)',
								tension: 0.4,
								fill: true
							}]
						},
						options: {
							...chartOptions,
							plugins: {
								...chartOptions.plugins,
								title: {
									display: true,
									text: 'Suhu Trend'
								}
							}
						}
					});
				}

				// Oksigen Chart
				const oksigenCtx = document.getElementById('oksigenChart');
				if (oksigenCtx) {
					console.log('[DashboardUpdater] Creating Oksigen chart');
					this.charts.oksigen = new Chart(oksigenCtx, {
						type: 'line',
						data: {
							labels: [],
							datasets: [{
								label: 'Oksigen Terlarut',
								data: [],
								borderColor: 'rgb(34, 197, 94)',
								backgroundColor: 'rgba(34, 197, 94, 0.1)',
								tension: 0.4,
								fill: true
							}]
						},
						options: {
							...chartOptions,
							plugins: {
								...chartOptions.plugins,
								title: {
									display: true,
									text: 'Oksigen Terlarut Trend'
								}
							}
						}
					});
				}

				// Salinitas Chart
				const salinitasCtx = document.getElementById('salinitasChart');
				if (salinitasCtx) {
					console.log('[DashboardUpdater] Creating Salinitas chart');
					this.charts.salinitas = new Chart(salinitasCtx, {
						type: 'line',
						data: {
							labels: [],
							datasets: [{
								label: 'Salinitas (‰)',
								data: [],
								borderColor: 'rgb(168, 85, 247)',
								backgroundColor: 'rgba(168, 85, 247, 0.1)',
								tension: 0.4,
								fill: true
							}]
						},
						options: {
							...chartOptions,
							plugins: {
								...chartOptions.plugins,
								title: {
									display: true,
									text: 'Salinitas Trend'
								}
							}
						}
					});
				}
			}

			async loadHistoricalData() {
				// Only load if there are active monitoring sessions and kolamId is set
				if (!this.kolamId) {
					console.log('No kolam ID - skipping historical data load');
					return;
				}
				
				@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
					try {
						const response = await fetch(`/api/sensor-data?kolam_id=${this.kolamId}&limit=20`);
						const data = await response.json();
						if (data.success && data.sensorData && data.sensorData.length > 0) {
							data.sensorData.forEach(item => {
								this.addChartData('ph', item.ph, item.waktu);
								this.addChartData('suhu', item.suhu, item.waktu);
								this.addChartData('oksigen', item.oksigen, item.waktu);
								this.addChartData('salinitas', item.salinitas, item.waktu);
							});
						}
					} catch (error) {
						console.error('Error loading historical data:', error);
					}
				@else
					// No active sessions - don't load historical data
					console.log('No active monitoring sessions - skipping historical data load');
				@endif
			}

			addChartData(type, value, waktu) {
				if (!this.charts[type]) {
					console.warn('[DashboardUpdater] Chart not found for type:', type);
					return;
				}

				const timeLabel = new Date(waktu).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
				
				// Add new data
				this.chartData[type].labels.push(timeLabel);
				this.chartData[type].values.push(value);

				// Keep only last maxDataPoints
				if (this.chartData[type].labels.length > this.maxDataPoints) {
					this.chartData[type].labels.shift();
					this.chartData[type].values.shift();
				}

				// Update chart
				this.charts[type].data.labels = this.chartData[type].labels;
				this.charts[type].data.datasets[0].data = this.chartData[type].values;
				this.charts[type].update('none'); // 'none' for smooth animation
				
				console.log('[DashboardUpdater] Added chart data:', type, value, 'Total points:', this.chartData[type].values.length);
			}

			async loadThresholds() {
				if (!this.kolamId) {
					return; // No kolam ID, skip loading thresholds
				}
				
				try {
					const response = await fetch(`/api/thresholds?kolam_id=${this.kolamId}`);
					const data = await response.json();
					if (data.success && data.thresholds) {
						this.displayThresholds(data.thresholds);
					}
				} catch (error) {
					console.error('Error loading thresholds:', error);
				}
			}

			async loadNotifications() {
				try {
					const response = await fetch(`/api/notifications?kolam_id=${this.kolamId}`);
					const data = await response.json();
					if (data.success && data.notifications) {
						this.displayNotifications(data.notifications);
					}
				} catch (error) {
					console.error('Error loading notifications:', error);
				}
			}

			initDummyData() {
				this.baseValues = {
					ph: 7.2,
					temperature: 28.5,
					salinity: 15.2,
					do: 7.2
				};
				this.updateInterval = 3000;
				this.updateData();
				setInterval(() => this.updateData(), this.updateInterval);
			}

			// Generate random value within realistic range
			randomInRange(min, max, current, variance = 0.3) {
				const change = (Math.random() - 0.5) * variance;
				const newValue = current + change;
				return Math.max(min, Math.min(max, newValue));
			}

			getStatus(value, type) {
				switch(type) {
					case 'ph':
						if (value >= 7.0 && value <= 8.5) return 'Aman';
						if (value < 7.0) return 'Asam';
						return 'Basa';
					case 'temp':
						if (value >= 26 && value <= 32) return 'Aman';
						if (value < 26) return 'Dingin';
						return 'Panas';
					case 'salinity':
						if (value >= 10 && value <= 20) return 'Stabil';
						if (value < 10) return 'Rendah';
						return 'Tinggi';
					case 'do':
						if (value >= 5 && value <= 10) return 'Baik';
						if (value < 5) return 'Rendah';
						return 'Tinggi';
					default:
						return 'Normal';
				}
			}

			updateValue(elementId, newValue, suffix = '') {
				const element = document.getElementById(elementId);
				if (!element) {
					console.warn('[DashboardUpdater] Element not found:', elementId, '- Available elements:', document.querySelectorAll('[id*="Value"]').length);
					// Try to find element after a short delay (in case it's not loaded yet)
					setTimeout(() => {
						const retryElement = document.getElementById(elementId);
						if (retryElement && newValue !== null && newValue !== undefined) {
							const formattedValue = newValue.toFixed(1) + (suffix ? ' ' + suffix : '');
							retryElement.textContent = formattedValue;
							console.log('[DashboardUpdater] Retry successful - Updated value:', elementId, 'to', formattedValue);
						}
					}, 100);
					return;
				}
				
				if (newValue === null || newValue === undefined) {
					element.textContent = '-' + (suffix ? ' ' + suffix : '');
					return;
				}
				
				const formattedValue = newValue.toFixed(1) + (suffix ? ' ' + suffix : '');
				console.log('[DashboardUpdater] Updating value:', elementId, 'to', formattedValue, '(element found:', !!element, ')');
				
				element.style.transform = 'scale(1.1)';
				element.style.color = '#fbbf24';
				setTimeout(() => {
					element.textContent = formattedValue;
					element.style.transform = 'scale(1)';
					element.style.color = '';
				}, 150);
			}

			updateStatus(elementId, status) {
				const element = document.getElementById(elementId);
				if (element) {
					element.textContent = status;
				}
			}

			updateFromData(data) {
				// Update values from sensor data
				if (data.ph !== undefined) {
					this.updateValue('phValue', data.ph);
					this.updateStatus('phStatus', this.getStatus(data.ph, 'ph'));
					this.checkThreshold('ph', data.ph);
				}
				if (data.suhu !== undefined) {
					this.updateValue('tempValue', data.suhu, '°C');
					this.updateStatus('tempStatus', this.getStatus(data.suhu, 'temp'));
					this.checkThreshold('suhu', data.suhu);
				}
				if (data.salinitas !== undefined) {
					this.updateValue('salinityValue', data.salinitas, '‰');
					this.updateStatus('salinityStatus', this.getStatus(data.salinitas, 'salinity'));
					this.checkThreshold('salinitas', data.salinitas);
				}
				if (data.oksigen !== undefined) {
					this.updateValue('doValue', data.oksigen);
					this.updateStatus('doStatus', this.getStatus(data.oksigen, 'do'));
					this.checkThreshold('oksigen', data.oksigen);
				}
			}

			updateFromSimulatedData(data) {
				console.log('[DashboardUpdater] updateFromSimulatedData called with:', data);
				console.log('[DashboardUpdater] Charts available:', Object.keys(this.charts));
				console.log('[DashboardUpdater] Data keys:', Object.keys(data));
				
				// Update values from simulated data
				this.updateFromData(data);
				
				// Update charts with new data
				if (data.ph !== undefined) {
					console.log('[DashboardUpdater] Adding pH chart data:', data.ph);
					this.addChartData('ph', data.ph, data.waktu || new Date().toISOString());
				}
				if (data.suhu !== undefined) {
					console.log('[DashboardUpdater] Adding Suhu chart data:', data.suhu);
					this.addChartData('suhu', data.suhu, data.waktu || new Date().toISOString());
				}
				if (data.oksigen !== undefined) {
					console.log('[DashboardUpdater] Adding Oksigen chart data:', data.oksigen);
					this.addChartData('oksigen', data.oksigen, data.waktu || new Date().toISOString());
				}
				if (data.salinitas !== undefined) {
					console.log('[DashboardUpdater] Adding Salinitas chart data:', data.salinitas);
					this.addChartData('salinitas', data.salinitas, data.waktu || new Date().toISOString());
				}
			}

			async loadNotifications() {
				// Reload notifications from database
				if (!this.kolamId) {
					return; // No kolam ID, skip loading notifications
				}
				
				try {
					const response = await fetch(`/api/notifications?kolam_id=${this.kolamId}`);
					const data = await response.json();
					if (data.success && data.notifications) {
						this.displayNotifications(data.notifications);
					}
				} catch (error) {
					console.error('Error loading notifications:', error);
				}
			}

			clearAllData() {
				// Reset sensor card values to null
				this.updateValue('phValue', null);
				this.updateValue('tempValue', null);
				this.updateValue('salinityValue', null);
				this.updateValue('doValue', null);
				
				this.updateStatus('phStatus', '-');
				this.updateStatus('tempStatus', '-');
				this.updateStatus('salinityStatus', '-');
				this.updateStatus('doStatus', '-');

				// Clear all charts - completely reset
				if (this.charts) {
					Object.keys(this.charts).forEach(chartType => {
						const chart = this.charts[chartType];
						if (chart) {
							// Clear all data
							chart.data.labels = [];
							if (chart.data.datasets && chart.data.datasets.length > 0) {
								chart.data.datasets.forEach(dataset => {
									dataset.data = [];
								});
							}
							// Update chart to show empty state
							chart.update('none');
						}
					});
				}

				// Clear chart data
				if (this.chartData) {
					Object.keys(this.chartData).forEach(type => {
						this.chartData[type].labels = [];
						this.chartData[type].values = [];
					});
				}

				// Hide charts container and show empty message
				const chartsContainer = document.getElementById('chartsContainer');
				if (chartsContainer) {
					chartsContainer.innerHTML = `
						<div class="col-span-full h-48 sm:h-56 rounded-lg bg-zinc-50 flex items-center justify-center text-zinc-400 text-sm">
							Belum ada monitoring session aktif. Mulai monitoring untuk melihat grafik realtime.
						</div>
					`;
				}
			}

			displayThresholds(thresholds) {
				this.thresholds = thresholds || {};
				const summary = document.getElementById('thresholdSummary');
				if (!summary) return;

				const entries = Object.entries(this.thresholds);
				if (!entries.length) {
					summary.innerHTML = '<p class="text-zinc-500">Belum ada threshold yang diatur</p>';
					return;
				}

				summary.innerHTML = entries.map(([sensorKey, value]) => {
					const label = this.getSensorLabel(sensorKey);
					let nilai = '-';
					let updated = '';
					
					if (typeof value === 'object' && value !== null) {
						nilai = value.nilai ?? value ?? '-';
						if (value.updated_at) {
							updated = new Date(value.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
						}
					} else {
						nilai = value ?? '-';
					}

					return `
						<div class="flex items-center justify-between py-1 border-b border-zinc-100">
							<div>
								<div class="font-medium">${label}</div>
								${updated ? `<div class="text-xs text-zinc-500">Update: ${updated}</div>` : ''}
							</div>
							<span class="font-semibold">${nilai}</span>
						</div>
					`;
				}).join('');
			}

			displayNotifications(notifications) {
				this.notifications = notifications && notifications.length ? notifications : [];
				this.renderNotifications();
			}

			renderNotifications() {
				const activityList = document.getElementById('activityList');
				const badge = document.getElementById('notificationBadge');

				if (!activityList) return;

				if (!this.notifications.length) {
					activityList.innerHTML = '<li class="text-sm text-zinc-500">Tidak ada notifikasi baru</li>';
				} else {
					activityList.innerHTML = this.notifications.slice(0, this.notificationLimit).map(n => {
						const waktu = n.waktu ? (typeof n.waktu === 'string' ? new Date(n.waktu) : new Date(n.waktu * 1000)) : new Date();
						return `<li class="flex items-start gap-2">
							<span class="inline-block h-2 w-2 rounded-full bg-red-500 mt-1.5"></span>
							<span>${n.pesan} - ${this.formatTime(waktu)}</span>
						</li>`;
					}).join('');
				}

				if (badge) {
					if (this.notifications.length) {
						badge.textContent = this.notifications.length;
						badge.classList.remove('hidden');
					} else {
						badge.classList.add('hidden');
					}
				}
			}

			async addNotification(message) {
				const newNotif = {
					id: `local-${Date.now()}`,
					pesan: message,
					waktu: Date.now(),
				};

				this.notifications = [newNotif, ...this.notifications].slice(0, this.notificationLimit);
				this.renderNotifications();

				// Save notification to server
				try {
					await fetch('/api/create-notification', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
						},
						body: JSON.stringify({
							kolam_id: this.kolamId,
							pesan: message,
						}),
					});
				} catch (error) {
					console.error('Gagal menyimpan notifikasi:', error);
				}
			}

			updateData() {
				// Dummy data generator (fallback)
				const newPh = this.randomInRange(6.5, 9.0, this.baseValues.ph, 0.2);
				this.baseValues.ph = newPh;
				this.updateValue('phValue', newPh);
				this.updateStatus('phStatus', this.getStatus(newPh, 'ph'));

				const newTemp = this.randomInRange(24, 35, this.baseValues.temperature, 0.5);
				this.baseValues.temperature = newTemp;
				this.updateValue('tempValue', newTemp, '°C');
				this.updateStatus('tempStatus', this.getStatus(newTemp, 'temp'));

				const newSalinity = this.randomInRange(10, 25, this.baseValues.salinity, 0.4);
				this.baseValues.salinity = newSalinity;
				this.updateValue('salinityValue', newSalinity, '‰');
				this.updateStatus('salinityStatus', this.getStatus(newSalinity, 'salinity'));

				const newDo = this.randomInRange(4, 10, this.baseValues.do, 0.3);
				this.baseValues.do = newDo;
				this.updateValue('doValue', newDo);
				this.updateStatus('doStatus', this.getStatus(newDo, 'do'));
			}

			addActivity() {
				const activityList = document.getElementById('activityList');
				if (activityList && activityList.children.length > 0) {
					const messages = this.activityMessages;
					const randomMsg = messages[Math.floor(Math.random() * messages.length)];
					const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
					const newItem = document.createElement('li');
					newItem.textContent = `• [${time}] ${randomMsg}`;
					newItem.className = 'animate-pulse';
					activityList.insertBefore(newItem, activityList.firstChild);
					if (activityList.children.length > 5) {
						activityList.removeChild(activityList.lastChild);
					}
					setTimeout(() => {
						newItem.classList.remove('animate-pulse');
					}, 2000);
				}
			}

			updateLastSync() {
				const syncElement = document.getElementById('lastSync');
				if (syncElement) {
					const now = new Date();
					const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
					syncElement.textContent = timeStr;
				}
			}

			checkThreshold(sensorKey, value) {
				const thresholdData = this.thresholds?.[sensorKey];
				if (!thresholdData || value === undefined || value === null) return;

				const limit = parseFloat(thresholdData.nilai);
				const numericValue = parseFloat(value);
				if (Number.isNaN(limit) || Number.isNaN(numericValue)) return;

				const exceeded = numericValue > limit;
				if (exceeded && !this.thresholdAlertState[sensorKey]) {
					this.thresholdAlertState[sensorKey] = true;
					const label = this.getSensorLabel(sensorKey);
					const message = `${label} ${numericValue.toFixed(1)} melebihi batas ${limit}`;
					this.addNotification(message);
				} else if (!exceeded) {
					this.thresholdAlertState[sensorKey] = false;
				}
			}

			getSensorLabel(sensorKey) {
				const labels = {
					ph: 'pH',
					suhu: 'Suhu',
					salinitas: 'Salinitas',
					oksigen: 'Oksigen Terlarut',
				};
				return labels[sensorKey] || sensorKey;
			}

			formatTime(timestamp) {
				if (!timestamp) return '-';
				const date = typeof timestamp === 'number' ? new Date(timestamp) : new Date(timestamp);
				return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
			}
		}

		function setupTimerForm() {
			const form = document.getElementById('timerForm');
			const errorEl = document.getElementById('timerError');
			const countdownEl = document.getElementById('timerCountdown');
			const statusEl = document.getElementById('timerStatus');
			const plannedEl = document.getElementById('timerPlannedMessage');
			const historyEl = document.getElementById('timerHistory');
			const cancelBtn = document.getElementById('cancelTimerBtn');
			if (!form) return;

			let countdownInterval = null;
			let targetTime = null;

			const resetTimerUI = (statusText = 'Belum berjalan') => {
				statusEl.textContent = statusText;
				countdownEl.textContent = '--:--';
				plannedEl.textContent = 'Set timer untuk mendapatkan notifikasi otomatis.';
			};

			const appendHistory = (message) => {
				if (!historyEl) return;
				const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
				const entry = document.createElement('div');
				entry.className = 'flex flex-col rounded-lg border border-zinc-200 px-3 py-2';
				entry.innerHTML = `
					<span class="font-medium text-zinc-800">${message}</span>
					<span class="text-xs text-zinc-500">Dicatat ${time}</span>
				`;
				if (historyEl.firstElementChild && historyEl.firstElementChild.tagName.toLowerCase() === 'p') {
					historyEl.innerHTML = '';
				}
				historyEl.prepend(entry);
				while (historyEl.children.length > 4) {
					historyEl.removeChild(historyEl.lastChild);
				}
			};

			form.addEventListener('submit', (e) => {
				e.preventDefault();
				const duration = parseInt(form.duration.value, 10);
				const priority = form.priority.value || 'Normal';
				const task = form.task.value.trim() || 'Monitoring rutin kolam';

				if (Number.isNaN(duration) || duration <= 0) {
					errorEl.textContent = 'Durasi timer harus lebih dari 0 menit.';
					errorEl.classList.remove('hidden');
					return;
				}

				errorEl.classList.add('hidden');

				if (countdownInterval) {
					clearInterval(countdownInterval);
				}

				targetTime = Date.now() + duration * 60 * 1000;
				statusEl.textContent = 'Timer berjalan';
				plannedEl.textContent = `${task} • Prioritas ${priority} • ${duration} menit`;

				const updateCountdown = () => {
					if (!targetTime) return;
					const remaining = targetTime - Date.now();
					if (remaining <= 0) {
						clearInterval(countdownInterval);
						countdownInterval = null;
						countdownEl.textContent = '00:00';
						statusEl.textContent = 'Timer selesai';
						const message = `Timer selesai: ${task} (${priority})`;
						appendHistory(message);
						if (window.dashboardUpdater) {
							window.dashboardUpdater.addNotification(message);
						}
						return;
					}
					const minutes = Math.floor(remaining / 60000);
					const seconds = Math.floor((remaining % 60000) / 1000);
					countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
				};

				updateCountdown();
				countdownInterval = setInterval(updateCountdown, 1000);
			});

			cancelBtn?.addEventListener('click', () => {
				if (countdownInterval) {
					clearInterval(countdownInterval);
					countdownInterval = null;
					appendHistory('Timer dibatalkan oleh peternak');
				}
				targetTime = null;
				resetTimerUI('Timer dibatalkan');
			});
		}

		async function setupPreMonitoringForm() {
			// This function is no longer used - replaced by Mulai Monitoring form
			// Keeping for backward compatibility
		}

		// Hapus Threshold
		async function hapusThreshold(thresholdId) {
			if (!confirm('Yakin hapus threshold ini?')) {
				return;
			}

			try {
				const response = await fetch(`/threshold/${thresholdId}`, {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					},
				});

				const result = await response.json();

				if (result.success) {
					// Reload page to update threshold list
					window.location.reload();
				} else {
					alert('Gagal menghapus threshold: ' + (result.message || 'Unknown error'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('Terjadi kesalahan saat menghapus threshold');
			}
		}

		// Clear all sensor data and charts
		function clearAllSensorData() {
			// Reset sensor card values using dashboardUpdater method
			if (window.dashboardUpdater) {
				window.dashboardUpdater.clearAllData();
			} else {
				// Fallback if dashboardUpdater not available
				const resetValue = (elementId, defaultValue, suffix = '') => {
					const element = document.getElementById(elementId);
					if (element) {
						element.textContent = defaultValue + suffix;
					}
				};

				resetValue('phValue', '-');
				resetValue('tempValue', '-');
				resetValue('salinityValue', '-');
				resetValue('doValue', '-');
				
				resetValue('phStatus', '-');
				resetValue('tempStatus', '-');
				resetValue('salinityStatus', '-');
				resetValue('doStatus', '-');
			}
		}

		// Realtime Data Simulator
		class RealtimeDataSimulator {
			constructor() {
				this.intervals = {};
			}

			startSimulation(sessionId, kolamId, umurBudidaya, timerInterval) {
				// Stop existing simulation for this session if any
				this.stopSimulation(sessionId);

				// Convert timer interval to milliseconds
				// timerInterval is in seconds (fixed 10 seconds)
				const intervalMs = parseInt(timerInterval) * 1000; // seconds to milliseconds
				
				console.log(`Starting simulation for session ${sessionId}: ${timerInterval} second(s) = ${intervalMs}ms interval`);

				// Start simulation immediately on first run (with delay to ensure dashboardUpdater is ready)
				setTimeout(() => {
					console.log(`[Simulator] Starting first simulation for session ${sessionId}`);
					this.simulateData(kolamId, umurBudidaya, sessionId);
				}, 2000);

				// Start simulation with interval
				const interval = setInterval(async () => {
					// Check if session is paused before calling API
					// We'll check this by calling API, but if paused, API will return early
					console.log(`[Simulator] Running simulation for session ${sessionId} at ${new Date().toLocaleTimeString()}`);
					const result = await this.simulateData(kolamId, umurBudidaya, sessionId);
					
					// If session is paused, stop the interval
					if (result && result.paused) {
						console.log(`[Simulator] Session ${sessionId} is paused, stopping simulation`);
						this.stopSimulation(sessionId);
					}
				}, intervalMs);

				this.intervals[sessionId] = interval;
			}

			async simulateData(kolamId, umurBudidaya, sessionId = null) {
				try {
					if (!sessionId) {
						console.error('Session ID is required');
						return { paused: false };
					}
					
					const response = await fetch('{{ route("api.simulate-realtime-data") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
							'Accept': 'application/json',
						},
						body: JSON.stringify({
							kolam_id: kolamId,
							umur_budidaya: umurBudidaya,
							session_id: sessionId,
						}),
					});

					if (!response.ok) {
						const errorText = await response.text();
						console.error('[Simulator] API Error:', response.status, errorText);
						throw new Error(`HTTP error! status: ${response.status}`);
					}

					const result = await response.json();
					console.log('[Simulator] API Response:', result);
					
					// Check if session is paused
					if (result.paused) {
						console.log('[Simulator] Monitoring session is paused, stopping simulation');
						// Stop simulation for this session
						this.stopSimulation(sessionId);
						// If paused but has data, still update dashboard with last data
						if (result.data && window.dashboardUpdater) {
							window.dashboardUpdater.updateFromSimulatedData(result.data);
						}
						return { paused: true };
					}
					
					// Check if session expired (24 hours)
					if (result.expired) {
						console.log('[Simulator] Monitoring session expired (24 hours)');
						if (sessionId) {
							this.stopSimulation(sessionId);
							// Reload page to update UI
							setTimeout(() => {
								window.location.reload();
							}, 1000);
						}
						return;
					}
					
					if (result.success) {
						// Show notification if threshold exceeded
						if (result.notifications && result.notifications.length > 0) {
							result.notifications.forEach(notif => {
								if (window.dashboardUpdater) {
									window.dashboardUpdater.addNotification(notif.message);
								}
							});
						}

						// Update dashboard values if available
						if (window.dashboardUpdater && result.data) {
							console.log('[Simulator] Updating dashboard with data:', result.data);
							console.log('[Simulator] dashboardUpdater available:', !!window.dashboardUpdater);
							console.log('[Simulator] result.data:', result.data);
							window.dashboardUpdater.updateFromSimulatedData(result.data);
						} else {
							console.warn('[Simulator] Cannot update dashboard - dashboardUpdater:', !!window.dashboardUpdater, 'data:', !!result.data);
							console.warn('[Simulator] result object:', result);
						}

						// Refresh notifications list
						if (window.dashboardUpdater && window.dashboardUpdater.loadNotifications) {
							window.dashboardUpdater.loadNotifications();
						}

						console.log('[Simulator] Data simulated successfully:', result.data);
						return { paused: false, success: true };
					} else {
						console.warn('[Simulator] Simulation failed:', result.message || 'Unknown error');
						return { paused: false, success: false };
					}
				} catch (error) {
					console.error('[Simulator] Error simulating data:', error);
					return { paused: false, error: true };
				}
			}

			stopSimulation(sessionId) {
				if (this.intervals[sessionId]) {
					clearInterval(this.intervals[sessionId]);
					delete this.intervals[sessionId];
				}
			}

			startAllSimulations() {
				@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
					console.log(`[Simulator] Starting all simulations for {{ $monitoringSessions->count() }} session(s)`);
					console.log(`[Simulator] dashboardUpdater available:`, !!window.dashboardUpdater);
					
					@foreach($monitoringSessions as $session)
						@if(!$session->is_paused)
							console.log(`[Simulator] Starting session {{ $session->session_id }}: kolam={{ $session->kolam_id }}, hari={{ $session->umur_budidaya }}, interval={{ $session->timer_monitoring }}s`);
							this.startSimulation(
								{{ $session->session_id }},
								'{{ $session->kolam_id }}',
								{{ $session->umur_budidaya }},
								'{{ $session->timer_monitoring }}'
							);
						@else
							console.log(`[Simulator] Skipping session {{ $session->session_id }} - paused`);
						@endif
					@endforeach
					
					setTimeout(() => {
						console.log(`[Simulator] All simulations started. Active intervals:`, Object.keys(this.intervals));
					}, 2000);
				@else
					console.log('[Simulator] No active monitoring sessions found');
				@endif
			}

			stopAllSimulations() {
				Object.keys(this.intervals).forEach(sessionId => {
					this.stopSimulation(sessionId);
				});
			}
		}

		// Update timer display for all active sessions (simple countdown)
		function updateTimers() {
			@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
				@foreach($monitoringSessions as $session)
					@if($session->mulai_monitoring)
						(function() {
							const sessionId = {{ $session->session_id }};
							const startTime = new Date('{{ $session->mulai_monitoring->toIso8601String() }}').getTime();
							let totalPausedSeconds = {{ $session->total_paused_seconds ?? 0 }};
							let isPaused = {{ $session->is_paused ? 'true' : 'false' }};
							@if($session->paused_at)
								let pausedAtTime = new Date('{{ $session->paused_at->toIso8601String() }}').getTime();
							@else
								let pausedAtTime = null;
							@endif
							const timerEl = document.getElementById('duration-' + sessionId);
							
							if (timerEl) {
								const maxDuration = 86400; // 24 hours in seconds
								
								// Calculate initial remaining time
								let remainingSeconds;
								if (isPaused && pausedAtTime) {
									const elapsedAtPause = Math.floor((pausedAtTime - startTime) / 1000);
									const activeAtPause = Math.max(0, elapsedAtPause - totalPausedSeconds);
									remainingSeconds = Math.max(0, maxDuration - activeAtPause);
								} else {
									const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
									const activeSeconds = Math.max(0, elapsedSeconds - totalPausedSeconds);
									remainingSeconds = Math.max(0, maxDuration - activeSeconds);
								}
								
								// Store frozen value if paused
								let frozenRemaining = isPaused ? remainingSeconds : null;
								
								function updateTimer() {
									// If paused, keep frozen value
									if (isPaused && frozenRemaining !== null) {
										const hours = Math.floor(frozenRemaining / 3600);
										const minutes = Math.floor((frozenRemaining % 3600) / 60);
										const seconds = frozenRemaining % 60;
										timerEl.textContent = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
										return;
									}
									
									// If not paused, countdown
									if (remainingSeconds > 0) {
										remainingSeconds--;
										const hours = Math.floor(remainingSeconds / 3600);
										const minutes = Math.floor((remainingSeconds % 3600) / 60);
										const seconds = remainingSeconds % 60;
										timerEl.textContent = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
									} else {
										// Timer reached 0
										timerEl.textContent = '00:00:00';
										if (window['timerInterval_' + sessionId]) {
											clearInterval(window['timerInterval_' + sessionId]);
											delete window['timerInterval_' + sessionId];
										}
										window.location.reload();
									}
								}
								
								// Update immediately
								updateTimer();
								
								// Update every second (only if not paused)
								if (!isPaused) {
									const intervalId = setInterval(updateTimer, 1000);
									window['timerInterval_' + sessionId] = intervalId;
								}
							}
						})();
					@endif
				@endforeach
			@endif
		}

		// Initialize dashboard updater when page loads
		document.addEventListener('DOMContentLoaded', async () => {
			try {
				console.log('[Dashboard] Initializing dashboard...');
				const updater = new DashboardDataUpdater();
				window.dashboardUpdater = updater;
				console.log('[Dashboard] DashboardUpdater created:', !!window.dashboardUpdater);

				// Wait a bit for charts to initialize
				await new Promise(resolve => setTimeout(resolve, 1000));
				console.log('[Dashboard] Charts should be initialized now');

				// Update timers
				updateTimers();
				
				// Initialize realtime data simulator ONLY if there are active monitoring sessions
				@if(isset($monitoringSessions) && $monitoringSessions->count() > 0)
					console.log('[Dashboard] Creating RealtimeDataSimulator...');
					const simulator = new RealtimeDataSimulator();
					// Wait a bit more before starting simulations to ensure dashboardUpdater is ready
					setTimeout(() => {
						console.log('[Dashboard] Starting simulations...');
						simulator.startAllSimulations();
						window.realtimeSimulator = simulator;
					}, 1500);
				@else
					console.log('[Dashboard] No active monitoring sessions');
					// No active monitoring sessions - don't start simulator and clear all data
					window.realtimeSimulator = null;
					clearAllSensorData();
				@endif
			} catch (error) {
				console.error('[Dashboard] Error initializing dashboard:', error);
			}
			
			// Cleanup on page unload
			window.addEventListener('beforeunload', () => {
				// Stop all simulations
				if (window.realtimeSimulator) {
					Object.keys(window.realtimeSimulator.intervals).forEach(sessionId => {
						window.realtimeSimulator.stopSimulation(sessionId);
					});
				}
			});
		});
	</script>

	<script>
		// Notification popup functions
		function toggleNotificationPopup() {
			const popup = document.getElementById('notificationPopup');
			if (popup) {
				popup.classList.toggle('hidden');
				if (!popup.classList.contains('hidden')) {
					loadNotifications();
				}
			}
		}

		async function loadNotifications() {
			try {
				const response = await fetch('/api/notifications', {
					method: 'GET',
					headers: {
						'Accept': 'application/json',
					},
				});

				if (!response.ok) {
					throw new Error('Failed to load notifications');
				}

				const result = await response.json();
				const notifications = result.notifications || [];
				const listEl = document.getElementById('notificationList');
				const badgeEl = document.getElementById('notificationBadge');

				if (!listEl) return;

				if (notifications.length === 0) {
listEl.innerHTML = '<li class="text-center py-8 text-zinc-500">Tidak ada notifikasi</li>';
				} else {
					listEl.innerHTML = notifications.map(notif => {
						// Use notif_id if id is not available (fallback)
						const notifId = notif.id || notif.notif_id;
						
						if (!notifId) {
							console.error('Notification ID is missing:', notif);
							return '';
						}
						
						const waktu = notif.waktu ? new Date(notif.waktu) : new Date();
						const waktuFormatted = waktu.toLocaleString('id-ID', {
							day: '2-digit',
							month: 'short',
							year: 'numeric',
							hour: '2-digit',
							minute: '2-digit'
						});
						
						return `
							<li class="flex items-start gap-2 py-3 border-b border-zinc-100 last:border-b-0">
								<span class="inline-block h-2 w-2 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></span>
								<div class="flex-1 min-w-0">
									<span class="break-words">${notif.pesan || ''}</span>
									<div class="text-xs text-zinc-500 mt-1">${waktuFormatted}</div>
								</div>
								<button onclick="deleteNotification(${notifId})" class="text-zinc-400 hover:text-red-500 transition-colors ml-2 flex-shrink-0" title="Hapus notifikasi">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
									</svg>
								</button>
							</li>
						`;
					}).join('');
				}

				// Update badge
				if (badgeEl) {
					if (notifications.length > 0) {
						badgeEl.textContent = notifications.length;
						badgeEl.classList.remove('hidden');
					} else {
						badgeEl.textContent = '0';
						badgeEl.classList.add('hidden');
					}
				}
			} catch (error) {
				console.error('Error loading notifications:', error);
				const listEl = document.getElementById('notificationList');
				if (listEl) {
					listEl.innerHTML = '<li class="text-center py-8 text-red-500">Gagal memuat notifikasi</li>';
				}
			}
		}

		async function deleteNotification(notificationId) {
			if (!notificationId || notificationId === 'undefined' || notificationId === undefined) {
				console.error('Invalid notification ID:', notificationId);
				alert('ID notifikasi tidak valid');
				return;
			}

			if (!confirm('Yakin ingin menghapus notifikasi ini?')) {
				return;
			}

			const csrfToken = document.querySelector('meta[name="csrf-token"]');
			if (!csrfToken) {
				alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
				return;
			}

			try {
				const response = await fetch(`/api/notifications/${notificationId}`, {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': csrfToken.content,
						'Accept': 'application/json',
					},
				});

				if (!response.ok) {
					const errorData = await response.json().catch(() => ({}));
					throw new Error(errorData.message || 'Failed to delete notification');
				}

				const result = await response.json();
				if (result.success) {
					// Reload notifications
					loadNotifications();
				} else {
					alert('Gagal menghapus notifikasi: ' + (result.message || 'Unknown error'));
				}
			} catch (error) {
				console.error('Error deleting notification:', error);
				alert('Terjadi kesalahan saat menghapus notifikasi: ' + error.message);
			}
		}

		async function clearAllNotifications() {
			if (!confirm('Yakin ingin menghapus semua riwayat notifikasi?')) {
				return;
			}

			const csrfToken = document.querySelector('meta[name="csrf-token"]');
			if (!csrfToken) {
				alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
				return;
			}

			try {
				const response = await fetch('/api/notifications/clear-all', {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': csrfToken.content,
						'Accept': 'application/json',
					},
				});

				if (!response.ok) {
					throw new Error('Failed to clear all notifications');
				}

				const result = await response.json();
				if (result.success) {
					// Reload notifications
					loadNotifications();
					alert('Semua notifikasi berhasil dihapus');
				} else {
					alert('Gagal menghapus semua notifikasi: ' + (result.message || 'Unknown error'));
				}
			} catch (error) {
				console.error('Error clearing all notifications:', error);
				alert('Terjadi kesalahan saat menghapus semua notifikasi');
			}
		}

		// Load notifications on page load
		document.addEventListener('DOMContentLoaded', function() {
			loadNotifications();
		});
	</script>
	@include('components.profil-modal')
</body>
</html>
