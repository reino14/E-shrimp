<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Prediksi Pertumbuhan – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
				<a href="{{ route('user.prediksi-pertumbuhan') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium flex items-center gap-2">
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
			<h1 class="text-lg sm:text-xl font-semibold mb-6">Prediksi Pertumbuhan & Kebutuhan Pakan</h1>

			@if(!$canPredict)
				<!-- Empty State: Belum bisa prediksi -->
				<div class="rounded-2xl border border-zinc-200 bg-white p-12 text-center">
					<svg class="w-16 h-16 text-zinc-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
					</svg>
					<h3 class="text-lg font-semibold text-zinc-900 mb-2">Belum Dapat Melakukan Prediksi</h3>
					<p class="text-sm text-zinc-500 mb-4">Kolam harus memiliki histori monitoring dengan umur budidaya minimal hari ke-14 untuk melakukan prediksi pertumbuhan</p>
					<a href="{{ route('user.manajemen-kolam') }}" class="inline-block px-6 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
						Lihat Monitoring
					</a>
				</div>
			@else
				<!-- Kolam Selection & Prediction Button -->
				<div class="mb-6 rounded-2xl border border-zinc-200 p-6 bg-white">
					<div class="mb-6">
						<h2 class="font-semibold text-lg mb-2">Pilih Kolam untuk Prediksi</h2>
						<p class="text-sm text-zinc-600 mb-4">Pilih kolam yang sudah memiliki histori monitoring dengan umur budidaya minimal hari ke-14</p>
						
						<div class="space-y-4">
							<div>
								<label for="kolam-select" class="block text-sm font-medium text-zinc-700 mb-2">
									Kolam
								</label>
								<select 
									id="kolam-select" 
									class="w-full px-4 py-2.5 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors"
									onchange="handleKolamChange()"
								>
									<option value="">-- Pilih Kolam --</option>
									@foreach($eligibleKolams as $eligible)
										<option 
											value="{{ $eligible['kolam_id'] }}" 
											data-max-umur="{{ $eligible['max_umur'] }}"
											data-nama-kapal="{{ $eligible['nama_kapal'] }}"
										>
											{{ $eligible['kolam_id'] }} (Hari ke-{{ $eligible['max_umur'] }})
										</option>
									@endforeach
								</select>
							</div>
							
							<div id="kolam-info" class="hidden p-4 bg-zinc-50 rounded-lg border border-zinc-200">
								<div class="flex items-start gap-3">
									<svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
									</svg>
									<div class="flex-1">
										<p class="text-sm font-medium text-zinc-900 mb-1">Informasi Kolam</p>
										<p class="text-xs text-zinc-600">
											Kolam: <span id="info-kolam-id" class="font-semibold">-</span><br>
											Umur Budidaya: <span id="info-umur" class="font-semibold">-</span> hari<br>
											Kapal: <span id="info-kapal" class="font-semibold">-</span>
										</p>
									</div>
								</div>
							</div>
							
							<button 
								id="btn-start-prediction" 
								onclick="startPrediction()" 
								disabled
								class="w-full px-6 py-3 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors disabled:bg-zinc-300 disabled:cursor-not-allowed disabled:text-zinc-500"
							>
								Mulai Prediksi Pertumbuhan
							</button>
						</div>
					</div>
				</div>

				<!-- Prediction Results -->
				<div id="predictionResults" class="hidden">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
						<!-- Estimasi Pertumbuhan -->
						<div class="rounded-2xl border border-zinc-200 p-6 bg-white">
							<h2 class="font-semibold mb-4">Estimasi Pertumbuhan</h2>
							<div class="space-y-3">
								<div class="flex justify-between items-center py-2 border-b border-zinc-100">
									<span class="text-sm text-zinc-600">Berat Udang</span>
									<span class="font-semibold" id="pred-berat">-</span>
								</div>
								<div class="flex justify-between items-center py-2 border-b border-zinc-100">
									<span class="text-sm text-zinc-600">Laju Pertumbuhan Harian</span>
									<span class="font-semibold" id="pred-laju">-</span>
								</div>
							</div>
						</div>

						<!-- Kebutuhan Pakan -->
						<div class="rounded-2xl border border-zinc-200 p-6 bg-white">
							<h2 class="font-semibold mb-4">Kebutuhan Pakan</h2>
							<div class="space-y-3">
								<div class="flex justify-between items-center py-2 border-b border-zinc-100">
									<span class="text-sm text-zinc-600">Feed Rate</span>
									<span class="font-semibold" id="pred-feed-rate">-</span>
								</div>
								<div class="flex justify-between items-center py-2 border-b border-zinc-100">
									<span class="text-sm text-zinc-600">Pakan per Hari</span>
									<span class="font-semibold" id="pred-pakan-hari">-</span>
								</div>
								<div class="flex justify-between items-center py-2 border-b border-zinc-100">
									<span class="text-sm text-zinc-600">Akumulasi Pakan</span>
									<span class="font-semibold" id="pred-akumulasi">-</span>
								</div>
							</div>
						</div>
					</div>

					<!-- Info Card -->
					<div class="mt-6 rounded-2xl border border-zinc-200 p-6 bg-zinc-50">
						<div class="flex items-start gap-3">
							<svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
							<div>
								<p class="text-sm text-zinc-700 font-medium mb-1">Informasi Prediksi</p>
								<p class="text-xs text-zinc-600">Prediksi berdasarkan data rata-rata harian dari hasil monitoring setiap 7 hari. Data diambil dari kolam: <span id="pred-kolam-info" class="font-semibold">-</span></p>
								<p class="text-xs text-zinc-500 mt-2">Update prediksi dengan mengklik tombol "Mulai Prediksi" di atas.</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Loading State -->
				<div id="predictionLoading" class="hidden rounded-2xl border border-zinc-200 p-8 bg-white">
					<div class="text-center">
						<div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600 mb-4"></div>
						<p class="text-sm text-zinc-600 font-medium">Memproses prediksi...</p>
					</div>
				</div>
			@endif
		</main>
	</div>
	@include('components.whatsapp-widget')
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
		openBtn && openBtn.addEventListener('click', openSidebar);
		backdrop && backdrop.addEventListener('click', closeSidebar);
		window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSidebar(); });

		// Handle kolam selection change
		function handleKolamChange() {
			const selectEl = document.getElementById('kolam-select');
			const btnEl = document.getElementById('btn-start-prediction');
			const infoEl = document.getElementById('kolam-info');
			const selectedOption = selectEl.options[selectEl.selectedIndex];
			
			if (selectEl.value) {
				// Enable button and show info
				btnEl.disabled = false;
				infoEl.classList.remove('hidden');
				
				// Update info
				document.getElementById('info-kolam-id').textContent = selectEl.value;
				document.getElementById('info-umur').textContent = selectedOption.getAttribute('data-max-umur');
				document.getElementById('info-kapal').textContent = selectedOption.getAttribute('data-nama-kapal') || '-';
			} else {
				// Disable button and hide info
				btnEl.disabled = true;
				infoEl.classList.add('hidden');
			}
		}

		// Display prediction results
		function displayPredictionResults(pred, kolamId) {
			const loadingEl = document.getElementById('predictionLoading');
			const resultsEl = document.getElementById('predictionResults');
			const btnEl = document.getElementById('btn-start-prediction');
			
			// Format values with proper handling
			const formatValue = (value, unit = '') => {
				if (value === null || value === undefined || isNaN(value)) return '-';
				return parseFloat(value).toFixed(2) + (unit ? ' ' + unit : '');
			};
			
			document.getElementById('pred-berat').textContent = formatValue(pred.Berat_udang_gr, 'gram');
			document.getElementById('pred-laju').textContent = formatValue(pred.Laju_pertumbuhan_harian_gr, 'gram/hari');
			document.getElementById('pred-feed-rate').textContent = formatValue(pred.Feed_Rate_persen, '%');
			document.getElementById('pred-pakan-hari').textContent = formatValue(pred.Pakan_per_hari_kg, 'kg');
			document.getElementById('pred-akumulasi').textContent = formatValue(pred.Akumulasi_pakan_kg, 'kg');
			document.getElementById('pred-kolam-info').textContent = kolamId;

			// Show results
			loadingEl.classList.add('hidden');
			resultsEl.classList.remove('hidden');

			// Scroll to results
			resultsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
			
			if (btnEl) {
				btnEl.disabled = false;
				btnEl.textContent = 'Mulai Prediksi Pertumbuhan';
			}
		}

		// Prediction function
		async function startPrediction() {
			const selectEl = document.getElementById('kolam-select');
			const kolamId = selectEl.value;
			
			if (!kolamId) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Silakan pilih kolam terlebih dahulu'
				});
				return;
			}

			const loadingEl = document.getElementById('predictionLoading');
			const resultsEl = document.getElementById('predictionResults');
			const btnEl = document.getElementById('btn-start-prediction');

			// Show loading
			loadingEl.classList.remove('hidden');
			resultsEl.classList.add('hidden');
			
			if (btnEl) {
				btnEl.disabled = true;
				btnEl.textContent = 'Memproses...';
			}

			try {
				const response = await fetch('/api/predict-growth', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
						'Accept': 'application/json',
					},
					body: JSON.stringify({
						kolam_id: kolamId,
					}),
				});

				const result = await response.json();

				if (!response.ok) {
					throw new Error(result.message || 'Gagal melakukan prediksi');
				}

				if (result.success && result.prediction) {
					// Display prediction results
					displayPredictionResults(result.prediction, kolamId);
				} else {
					throw new Error('Prediksi tidak tersedia');
				}
			} catch (error) {
				console.error('Error:', error);
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: error.message || 'Terjadi kesalahan saat melakukan prediksi'
				});
				loadingEl.classList.add('hidden');
			} finally {
				if (btnEl) {
					btnEl.disabled = false;
					btnEl.textContent = 'Mulai Prediksi Pertumbuhan';
				}
			}
		}
	</script>
	@include('components.profil-modal')
</body>
</html>

