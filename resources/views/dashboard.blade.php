<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Dashboard – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
				<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Dashboard</a>
				<a href="{{ route('user.grafik-realtime') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Grafik Real-time</a>
				<a href="{{ route('user.histori-data') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Histori Data</a>
				<a href="{{ route('user.artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Daily Article</a>
				<a href="{{ route('user.forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Community</a>
				<a href="{{ route('user.prediksi-pertumbuhan') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Prediksi Pertumbuhan</a>
				<a href="{{ route('user.price-tracker') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Price Tracker</a>
				<a href="{{ route('user.klasifikasi-kualitas') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Klasifikasi Kualitas</a>
			</nav>
			<div class="mt-8">
				<div class="text-xs uppercase text-zinc-500 tracking-wide mb-1">Other</div>
				<a href="{{ route('user.profil') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm">Profil</a>
				<a href="/" data-logout class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm">Logout</a>
			</div>
		</aside>
		<!-- Backdrop for mobile drawer -->
		<div id="backdrop" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>

		<main class="col-span-12 md:col-span-10 p-4 sm:p-6 md:ml-0">
			<div class="flex items-center justify-between">
				<h1 class="text-lg sm:text-xl font-semibold">Kolam #1</h1>
				<div class="text-xs sm:text-sm text-zinc-600">
					Last sync: <span id="lastSync">just now</span>
				</div>
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
				<div class="rounded-xl bg-zinc-800 text-white p-4">
					<div class="text-sm text-zinc-300">pH</div>
					<div class="text-2xl font-semibold transition-all duration-300" id="phValue">7.2</div>
					<div class="mt-2 text-xs text-zinc-400">Status: <span id="phStatus">Aman</span></div>
				</div>
				<div class="rounded-xl bg-zinc-800 text-white p-4">
					<div class="text-sm text-zinc-300">Suhu</div>
					<div class="text-2xl font-semibold transition-all duration-300" id="tempValue">28.5°C</div>
					<div class="mt-2 text-xs text-zinc-400">Status: <span id="tempStatus">Aman</span></div>
				</div>
				<div class="rounded-xl bg-zinc-800 text-white p-4">
					<div class="text-sm text-zinc-300">Salinitas</div>
					<div class="text-2xl font-semibold transition-all duration-300" id="salinityValue">15.2%</div>
					<div class="mt-2 text-xs text-zinc-400">Status: <span id="salinityStatus">Stabil</span></div>
				</div>
				<div class="rounded-xl bg-zinc-800 text-white p-4">
					<div class="text-sm text-zinc-300">Oksigen Terlarut</div>
					<div class="text-2xl font-semibold transition-all duration-300" id="doValue">7.2</div>
					<div class="mt-2 text-xs text-zinc-400">Status: <span id="doStatus">Baik</span></div>
				</div>
			</div>

			<div class="mt-6 rounded-2xl border border-zinc-200 overflow-hidden">
				<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base">Grafik Real‑time</div>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
					<div class="h-48 sm:h-56 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400 text-sm">
						Chart placeholder - pH Trend
					</div>
					<div class="h-48 sm:h-56 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400 text-sm">
						Chart placeholder - Temperature Trend
					</div>
				</div>
			</div>

			<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
				<div class="rounded-2xl border border-zinc-200">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between">
						<span>Notifikasi</span>
						@php $notifCount = isset($notifikasis) ? $notifikasis->count() : 0; @endphp
						<span id="notificationBadge" class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full {{ $notifCount > 0 ? '' : 'hidden' }}">
							{{ $notifCount }}
						</span>
					</div>
					<ul id="activityList" class="p-4 text-sm text-zinc-700 space-y-2 max-h-64 overflow-y-auto">
						@if(isset($notifikasis) && $notifikasis->count() > 0)
							@foreach($notifikasis as $notif)
							<li class="flex items-start gap-2">
								<span class="inline-block h-2 w-2 rounded-full bg-red-500 mt-1.5"></span>
								<span>{{ $notif->pesan }} - {{ $notif->waktu->format('H:i') }}</span>
							</li>
							@endforeach
						@else
							<li>Tidak ada notifikasi baru</li>
						@endif
					</ul>
				</div>
				<div class="rounded-2xl border border-zinc-200">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between">
						<span>Threshold</span>
						<button onclick="document.getElementById('modalThreshold').classList.remove('hidden')" class="text-xs px-2 py-1 rounded border border-zinc-300 hover:bg-zinc-50">Atur</button>
					</div>
					<div id="thresholdSummary" class="p-4 text-sm text-zinc-700 space-y-2">
						@if(isset($thresholds) && $thresholds->count() > 0)
							@foreach($thresholds as $threshold)
							<div class="flex justify-between items-center py-1 border-b border-zinc-100">
								<span>{{ ucfirst($threshold->sensor_tipe) }}:</span>
								<span class="font-semibold">{{ $threshold->nilai }}</span>
							</div>
							@endforeach
						@else
							<p class="text-zinc-500">Belum ada threshold yang diatur</p>
						@endif
					</div>
				</div>
			</div>

			<div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">
				<div class="rounded-2xl border border-zinc-200">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between">
						<span>Timer Monitoring & Alarm Threshold</span>
						<button type="button" id="cancelTimerBtn" class="text-xs px-2 py-1 rounded border border-zinc-300 hover:bg-zinc-50">Batalkan Timer</button>
					</div>
					<div class="p-4 space-y-4 text-sm text-zinc-700">
						<form id="timerForm" data-kolam="{{ $kolam->kolam_id ?? 'KOLAM-001' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<div>
								<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Durasi (menit)</label>
								<input type="number" name="duration" min="1" placeholder="Misal 15" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
							</div>
							<div>
								<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Prioritas</label>
								<select name="priority" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
									<option value="Normal">Normal</option>
									<option value="Tinggi">Tinggi</option>
									<option value="Kritis">Kritis</option>
								</select>
							</div>
							<div class="md:col-span-2">
								<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Tugas / Catatan Timer</label>
								<input type="text" name="task" placeholder="Contoh: Cek aerator & pakan otomatis" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
							</div>
							<div class="md:col-span-2">
								<button type="submit" class="w-full md:w-auto px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Mulai Timer</button>
							</div>
						</form>
						<p id="timerError" class="text-xs text-red-600 hidden"></p>
						<div class="rounded-xl bg-zinc-50 p-4">
							<div class="text-xs uppercase text-zinc-500 tracking-wide">Status Timer</div>
							<div id="timerStatus" class="text-xl font-semibold mt-1">Belum berjalan</div>
							<div id="timerCountdown" class="text-2xl font-mono text-zinc-800 mt-2">--:--</div>
							<div id="timerPlannedMessage" class="text-sm text-zinc-600 mt-2">Set timer untuk mendapatkan notifikasi otomatis.</div>
						</div>
						<div>
							<div class="text-xs uppercase text-zinc-500 tracking-wide mb-2">Riwayat Timer</div>
							<div id="timerHistory" class="space-y-2 text-sm text-zinc-600">
								<p class="text-zinc-500">Belum ada timer yang selesai.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="rounded-2xl border border-zinc-200">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base flex items-center justify-between">
						<span>Briefing & Perintah Monitoring</span>
						<span id="preMonitoringStatus" class="text-xs text-zinc-500"></span>
					</div>
					<div class="p-4 space-y-4">
						<form id="preMonitoringForm" data-kolam="{{ $kolam->kolam_id ?? 'KOLAM-001' }}" class="space-y-4 text-sm text-zinc-700">
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Jenis Monitoring</label>
									<select name="jenis" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
										<option value="Pemantauan Rutin">Pemantauan Rutin</option>
										<option value="Sampling Kualitas">Sampling Kualitas</option>
										<option value="Maintenance">Maintenance</option>
										<option value="Persiapan Panen">Persiapan Panen</option>
									</select>
								</div>
								<div>
									<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Jadwal Mulai</label>
									<input type="datetime-local" name="waktu" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
								</div>
								<div>
									<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Penanggung Jawab</label>
									<input type="text" name="penanggung_jawab" placeholder="Nama petugas" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
								</div>
								<div>
									<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Peralatan / Sensor</label>
									<input type="text" name="peralatan" placeholder="CTH: DO meter, aerator, drone" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
								</div>
							</div>
							<div>
								<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Instruksi Utama</label>
								<textarea name="perintah" rows="3" placeholder="Deskripsikan langkah monitoring..." class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
							</div>
							<div>
								<label class="block text-xs uppercase tracking-wide text-zinc-500 mb-1">Catatan Tambahan</label>
								<textarea name="catatan" rows="2" placeholder="Catatan risiko, kondisi kolam, dll" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
							</div>
							<div class="flex flex-wrap gap-2">
								<button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500 text-sm">Simpan Briefing</button>
								<button type="reset" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Reset</button>
							</div>
						</form>
						<div id="preMonitoringFeedback" class="text-xs text-emerald-600 hidden"></div>
						<div>
							<div class="text-xs uppercase tracking-wide text-zinc-500 mb-2">Briefing Terakhir</div>
							<div id="monitoringBriefHistory" class="space-y-3 text-sm text-zinc-700">
								<p class="text-zinc-500">Belum ada briefing yang tersimpan.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

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

		// Firebase Realtime Database updater
		class DashboardDataUpdater {
			constructor() {
				this.kolamId = '{{ $kolam->kolam_id ?? "KOLAM-001" }}';
				this.unsubscribeSensor = null;
				this.useFirebase = true; // Set to false to use dummy data
				this.thresholds = {};
				this.thresholdAlertState = {};
				this.notifications = [];
				this.notificationLimit = 5;
				this.firebaseHelpers = {};
				this.init();
			}

			async init() {
				if (this.useFirebase) {
					// Import Firebase RTDB functions dynamically
					const { listenSensorData, getThresholds, getUnreadNotifications, createNotification } = await import('./firebase-rtdb.js');
					this.firebaseHelpers.createNotification = createNotification;
					
					// Listen to real-time sensor data
					this.unsubscribeSensor = listenSensorData(this.kolamId, (dataArray) => {
						if (dataArray.length > 0) {
							const latest = dataArray[0];
							this.updateFromFirebase(latest);
						}
					});

					// Load thresholds from Firebase
					const thresholds = await getThresholds(this.kolamId);
					this.displayThresholds(thresholds);

					// Load notifications from Firebase
					const notifications = await getUnreadNotifications(this.kolamId, 5);
					this.displayNotifications(notifications);
				} else {
					// Fallback to dummy data
					this.initDummyData();
				}
				
				this.updateLastSync();
				setInterval(() => this.updateLastSync(), 1000);
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
				if (element) {
					element.style.transform = 'scale(1.1)';
					element.style.color = '#fbbf24';
					setTimeout(() => {
						element.textContent = newValue.toFixed(1) + suffix;
						element.style.transform = 'scale(1)';
						element.style.color = '';
					}, 150);
				}
			}

			updateStatus(elementId, status) {
				const element = document.getElementById(elementId);
				if (element) {
					element.textContent = status;
				}
			}

			updateFromFirebase(data) {
				// Update values from Firebase data
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
					const nilai = value?.nilai ?? '-';
					const updated = value?.updated_at
						? new Date(value.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
						: '';

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
					activityList.innerHTML = this.notifications.slice(0, this.notificationLimit).map(n =>
						`<li class="flex items-start gap-2">
							<span class="inline-block h-2 w-2 rounded-full bg-red-500 mt-1.5"></span>
							<span>${n.pesan} - ${this.formatTime(n.waktu || n.timestamp)}</span>
						</li>`
					).join('');
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

			addNotification(message) {
				const newNotif = {
					id: `local-${Date.now()}`,
					pesan: message,
					waktu: Date.now(),
				};

				this.notifications = [newNotif, ...this.notifications].slice(0, this.notificationLimit);
				this.renderNotifications();

				if (this.firebaseHelpers?.createNotification) {
					this.firebaseHelpers.createNotification(this.kolamId, message).catch((error) => {
						console.error('Gagal menyimpan notifikasi ke Firebase:', error);
					});
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
			const form = document.getElementById('preMonitoringForm');
			const historyEl = document.getElementById('monitoringBriefHistory');
			const feedbackEl = document.getElementById('preMonitoringFeedback');
			const statusEl = document.getElementById('preMonitoringStatus');

			if (!form) return;

			const kolamId = form.dataset.kolam;
			const { saveMonitoringInstruction, getMonitoringInstructions } = await import('./firebase-rtdb.js');

			const renderHistory = (items) => {
				if (!historyEl) return;
				if (!items.length) {
					historyEl.innerHTML = '<p class="text-zinc-500">Belum ada briefing yang tersimpan.</p>';
					return;
				}

				historyEl.innerHTML = items.map(item => {
					const jenis = item.jenis || 'Monitoring';
					const penanggungJawab = item.penanggung_jawab || '-';
					const startTime = item.start_time_iso || item.start_time || item.start_timestamp;
					const startLabel = startTime
						? new Date(startTime).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
						: 'Belum dijadwalkan';
					const perintah = item.perintah || '';

					return `
						<div class="rounded-xl border border-zinc-200 p-3">
							<div class="flex items-center justify-between">
								<span class="font-semibold">${jenis}</span>
								<span class="text-xs text-zinc-500">${startLabel}</span>
							</div>
							<p class="text-sm text-zinc-600 mt-1">${perintah}</p>
							<div class="text-xs text-zinc-500 mt-2">PJ: ${penanggungJawab}</div>
						</div>
					`;
				}).join('');
			};

			const loadHistory = async () => {
				if (historyEl) {
					historyEl.innerHTML = '<p class="text-xs text-zinc-400">Memuat briefing...</p>';
				}
				const data = await getMonitoringInstructions(kolamId, 4);
				renderHistory(data);
			};

			await loadHistory();

			const showFeedback = (message, isError = false) => {
				if (!feedbackEl) return;
				feedbackEl.textContent = message;
				feedbackEl.classList.toggle('text-red-600', isError);
				feedbackEl.classList.toggle('text-emerald-600', !isError);
				feedbackEl.classList.remove('hidden');
				setTimeout(() => feedbackEl.classList.add('hidden'), 4000);
			};

			form.addEventListener('submit', async (e) => {
				e.preventDefault();
				const formData = new FormData(form);
				const payload = {
					jenis: formData.get('jenis') || 'Monitoring',
					start_time: formData.get('waktu'),
					penanggung_jawab: formData.get('penanggung_jawab'),
					peralatan: formData.get('peralatan'),
					perintah: formData.get('perintah'),
					catatan: formData.get('catatan'),
				};

				const submitBtn = form.querySelector('button[type="submit"]');
				const prevText = submitBtn.textContent;
				submitBtn.disabled = true;
				submitBtn.textContent = 'Menyimpan...';
				statusEl.textContent = 'Menyimpan briefing...';

				try {
					const result = await saveMonitoringInstruction(kolamId, payload);
					if (result.success) {
						form.reset();
						await loadHistory();
						showFeedback('Briefing tersimpan & dibagikan ke tim.');
						statusEl.textContent = 'Briefing tersimpan';
						const scheduleLabel = payload.start_time
							? new Date(payload.start_time).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
							: 'jadwal belum ditentukan';
						if (window.dashboardUpdater) {
							window.dashboardUpdater.addNotification(`Briefing ${payload.jenis} dijadwalkan (${scheduleLabel})`);
						}
					} else {
						showFeedback(result.error || 'Gagal menyimpan briefing.', true);
						statusEl.textContent = 'Terjadi kesalahan';
					}
				} catch (error) {
					console.error(error);
					showFeedback('Terjadi kesalahan saat menyimpan briefing.', true);
					statusEl.textContent = 'Terjadi kesalahan';
				} finally {
					submitBtn.disabled = false;
					submitBtn.textContent = prevText;
					setTimeout(() => statusEl.textContent = '', 3000);
				}
			});
		}

		async function initMonitoringControls() {
			setupTimerForm();
			await setupPreMonitoringForm();
		}

		// Initialize dashboard updater when page loads
		document.addEventListener('DOMContentLoaded', async () => {
			const updater = new DashboardDataUpdater();
			window.dashboardUpdater = updater;

			await initMonitoringControls();
			
			// Cleanup on page unload
			window.addEventListener('beforeunload', () => {
				if (updater.unsubscribeSensor) {
					updater.unsubscribeSensor();
				}
			});
		});
	</script>
</body>
</html>
