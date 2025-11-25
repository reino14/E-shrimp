<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Detail Peternak – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased text-zinc-800 bg-white" data-requires-auth="true">
	<div class="min-h-screen grid grid-cols-12">
		<aside class="col-span-12 md:col-span-2 border-r border-zinc-200 bg-zinc-50 p-4">
			<div class="flex items-center gap-2 mb-6">
				<div class="h-6 w-6 rounded bg-zinc-900"></div>
				<div class="font-semibold">E‑SHRIMP</div>
			</div>
			<nav class="space-y-1 text-sm">
				<a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Dashboard</a>
				<a href="{{ route('admin.kelola-user') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Kelola User</a>
				<a href="{{ route('admin.kelola-artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Artikel</a>
				<a href="{{ route('admin.kelola-forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Forum</a>
			</nav>
		</aside>
		<main class="col-span-12 md:col-span-10 p-4 sm:p-6">
			<div class="flex items-center justify-between mb-6">
				<div>
					<a href="{{ route('admin.kelola-user') }}" class="text-sm text-zinc-600 hover:text-zinc-800 mb-2 inline-block">← Kembali ke Kelola User</a>
					<h1 class="text-lg sm:text-xl font-semibold">Detail Peternak</h1>
				</div>
			</div>

			<!-- Data Diri Peternak -->
			<div class="rounded-2xl border border-zinc-200 p-6 mb-6">
				<h2 class="font-semibold mb-4 text-lg">Data Diri</h2>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label class="text-xs text-zinc-500 uppercase tracking-wide">Email</label>
						<div class="font-semibold mt-1">{{ $peternak->email_peternak }}</div>
					</div>
					<div>
						<label class="text-xs text-zinc-500 uppercase tracking-wide">Nama</label>
						<div class="font-semibold mt-1">{{ $peternak->nama ?? '-' }}</div>
					</div>
					<div>
						<label class="text-xs text-zinc-500 uppercase tracking-wide">Role</label>
						<div class="font-semibold mt-1">{{ $peternak->role ?? 'peternak' }}</div>
					</div>
					<div>
						<label class="text-xs text-zinc-500 uppercase tracking-wide">Terdaftar</label>
						<div class="font-semibold mt-1">{{ $peternak->created_at ? $peternak->created_at->format('d M Y H:i') : '-' }}</div>
					</div>
				</div>
			</div>

			<!-- Statistik -->
			<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
				<div class="rounded-xl border border-zinc-200 p-4">
					<div class="text-sm text-zinc-500">Total Kolam</div>
					<div class="text-2xl font-semibold mt-1">{{ $kolams->count() }}</div>
				</div>
				<div class="rounded-xl border border-zinc-200 p-4">
					<div class="text-sm text-zinc-500">Monitoring Sessions</div>
					<div class="text-2xl font-semibold mt-1">{{ $monitoringSessions->count() }}</div>
				</div>
				<div class="rounded-xl border border-zinc-200 p-4">
					<div class="text-sm text-zinc-500">Total Data Sensor</div>
					<div class="text-2xl font-semibold mt-1">{{ $sensorDataStats->total_records ?? 0 }}</div>
				</div>
				<div class="rounded-xl border border-zinc-200 p-4">
					<div class="text-sm text-zinc-500">Notifikasi</div>
					<div class="text-2xl font-semibold mt-1">{{ $notificationsCount }}</div>
					<div class="text-xs text-red-600 mt-1">{{ $unreadNotificationsCount }} belum dibaca</div>
				</div>
			</div>

			<!-- Daftar Kolam -->
			<div class="rounded-2xl border border-zinc-200 overflow-hidden mb-6">
				<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base">Kolam ({{ $kolams->count() }})</div>
				@if($kolams->count() > 0)
					<div class="overflow-x-auto">
						<table class="w-full">
							<thead class="bg-zinc-50 border-b border-zinc-200">
								<tr>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Kolam ID</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Dibuat</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Monitoring Aktif</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Total Data Sensor</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Aksi</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-zinc-200">
								@foreach($kolams as $kolam)
									@php
										$activeSessions = $kolamActiveSessions[$kolam->kolam_id] ?? 0;
										$sensorCount = $kolamSensorCounts[$kolam->kolam_id] ?? 0;
									@endphp
									<tr>
										<td class="px-4 py-3 text-sm font-medium">{{ $kolam->kolam_id }}</td>
										<td class="px-4 py-3 text-sm">{{ $kolam->created_at ? $kolam->created_at->format('d M Y') : '-' }}</td>
										<td class="px-4 py-3 text-sm">
											<span class="px-2 py-1 rounded-full text-xs {{ $activeSessions > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
												{{ $activeSessions }} aktif
											</span>
										</td>
										<td class="px-4 py-3 text-sm">{{ $sensorCount }}</td>
										<td class="px-4 py-3 text-sm">
											<button onclick="hapusKolam('{{ $kolam->kolam_id }}')" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="p-8 text-center text-sm text-zinc-500">Belum ada kolam</div>
				@endif
			</div>

			<!-- Monitoring Sessions -->
			<div class="rounded-2xl border border-zinc-200 overflow-hidden mb-6">
				<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base">Monitoring Sessions ({{ $monitoringSessions->count() }})</div>
				@if($monitoringSessions->count() > 0)
					<div class="overflow-x-auto">
						<table class="w-full">
							<thead class="bg-zinc-50 border-b border-zinc-200">
								<tr>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Kolam</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Nama Kapal</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Umur Budidaya</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Status</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Mulai</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Selesai</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Aksi</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-zinc-200">
								@foreach($monitoringSessions as $session)
									<tr>
										<td class="px-4 py-3 text-sm">{{ $session->kolam_id }}</td>
										<td class="px-4 py-3 text-sm">{{ $session->nama_kapal }}</td>
										<td class="px-4 py-3 text-sm">Hari ke-{{ $session->umur_budidaya }}</td>
										<td class="px-4 py-3 text-sm">
											<span class="px-2 py-1 rounded-full text-xs {{ $session->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
												{{ $session->is_active ? 'Aktif' : 'Selesai' }}
											</span>
										</td>
										<td class="px-4 py-3 text-sm">{{ $session->mulai_monitoring ? $session->mulai_monitoring->format('d M Y H:i') : '-' }}</td>
										<td class="px-4 py-3 text-sm">{{ $session->selesai_monitoring ? $session->selesai_monitoring->format('d M Y H:i') : '-' }}</td>
										<td class="px-4 py-3 text-sm">
											<button onclick="hapusMonitoringSession({{ $session->session_id }})" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="p-8 text-center text-sm text-zinc-500">Belum ada monitoring session</div>
				@endif
			</div>

			<!-- Statistik Sensor Data -->
			@if($sensorDataStats && $sensorDataStats->total_records > 0)
				<div class="rounded-2xl border border-zinc-200 p-6 mb-6">
					<h2 class="font-semibold mb-4 text-lg">Statistik Data Sensor</h2>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Rata-rata pH</label>
							<div class="font-semibold mt-1">{{ number_format($sensorDataStats->avg_ph ?? 0, 2) }}</div>
						</div>
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Rata-rata Suhu</label>
							<div class="font-semibold mt-1">{{ number_format($sensorDataStats->avg_suhu ?? 0, 2) }}°C</div>
						</div>
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Rata-rata Oksigen</label>
							<div class="font-semibold mt-1">{{ number_format($sensorDataStats->avg_oksigen ?? 0, 2) }}</div>
						</div>
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Rata-rata Salinitas</label>
							<div class="font-semibold mt-1">{{ number_format($sensorDataStats->avg_salinitas ?? 0, 2) }}‰</div>
						</div>
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Data Pertama</label>
							<div class="font-semibold mt-1 text-sm">{{ $sensorDataStats->first_record ? \Carbon\Carbon::parse($sensorDataStats->first_record)->format('d M Y H:i') : '-' }}</div>
						</div>
						<div>
							<label class="text-xs text-zinc-500 uppercase tracking-wide">Data Terakhir</label>
							<div class="font-semibold mt-1 text-sm">{{ $sensorDataStats->last_record ? \Carbon\Carbon::parse($sensorDataStats->last_record)->format('d M Y H:i') : '-' }}</div>
						</div>
					</div>
				</div>
			@endif

			<!-- Latest Sensor Data -->
			@if($latestSensorData->count() > 0)
				<div class="rounded-2xl border border-zinc-200 overflow-hidden">
					<div class="px-4 py-3 border-b border-zinc-200 font-semibold text-sm sm:text-base">Data Sensor Terbaru (10 terakhir)</div>
					<div class="overflow-x-auto">
						<table class="w-full">
							<thead class="bg-zinc-50 border-b border-zinc-200">
								<tr>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Waktu</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Kolam</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">pH</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Suhu</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Oksigen</th>
									<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Salinitas</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-zinc-200">
								@foreach($latestSensorData as $data)
									<tr>
										<td class="px-4 py-3 text-sm">{{ $data->waktu ? \Carbon\Carbon::parse($data->waktu)->format('d M Y H:i') : '-' }}</td>
										<td class="px-4 py-3 text-sm">{{ $data->kolam_id }}</td>
										<td class="px-4 py-3 text-sm">{{ number_format($data->ph ?? 0, 2) }}</td>
										<td class="px-4 py-3 text-sm">{{ number_format($data->suhu ?? 0, 2) }}°C</td>
										<td class="px-4 py-3 text-sm">{{ number_format($data->oksigen ?? 0, 2) }}</td>
										<td class="px-4 py-3 text-sm">{{ number_format($data->salinitas ?? 0, 2) }}‰</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</main>
	</div>
	<script>
		// Delete Kolam
		async function hapusKolam(kolamId) {
			if (!confirm(`Yakin hapus kolam ${kolamId}? Semua data terkait (monitoring sessions, sensor data, notifikasi, threshold) akan ikut terhapus.`)) {
				return;
			}

			try {
				const response = await fetch(`/admin/kolam/${kolamId}`, {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					},
				});

				const result = await response.json();

				if (result.success) {
					alert('Kolam berhasil dihapus');
					if (result.redirect) {
						window.location.href = result.redirect;
					} else {
						window.location.reload();
					}
				} else {
					alert('Gagal menghapus kolam: ' + (result.message || 'Unknown error'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('Terjadi kesalahan saat menghapus kolam');
			}
		}

		// Delete Monitoring Session
		async function hapusMonitoringSession(sessionId) {
			if (!confirm('Yakin hapus monitoring session ini?')) {
				return;
			}

			try {
				const response = await fetch(`/admin/monitoring-session/${sessionId}`, {
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					},
				});

				const result = await response.json();

				if (result.success) {
					alert('Monitoring session berhasil dihapus');
					if (result.redirect) {
						window.location.href = result.redirect;
					} else {
						window.location.reload();
					}
				} else {
					alert('Gagal menghapus monitoring session: ' + (result.message || 'Unknown error'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('Terjadi kesalahan saat menghapus monitoring session');
			}
		}
	</script>
</body>
</html>

