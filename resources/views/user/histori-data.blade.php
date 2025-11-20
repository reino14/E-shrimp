<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Histori Data – E‑shrimp</title>
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
				<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Dashboard</a>
				<a href="{{ route('user.grafik-realtime') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Grafik Real-time</a>
				<a href="{{ route('user.histori-data') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Histori Data</a>
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
			<h1 class="text-lg sm:text-xl font-semibold mb-6">Histori Data</h1>
			<div class="rounded-2xl border border-zinc-200 overflow-hidden">
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-zinc-50 border-b border-zinc-200">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Waktu</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">pH</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Suhu (°C)</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Oksigen</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Salinitas (‰)</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Kualitas Air</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-zinc-200">
							@forelse($historicalData as $data)
							<tr>
								<td class="px-4 py-3 text-sm">{{ $data->waktu->format('d M Y H:i') }}</td>
								<td class="px-4 py-3 text-sm">{{ number_format($data->ph, 1) }}</td>
								<td class="px-4 py-3 text-sm">{{ number_format($data->suhu, 1) }}</td>
								<td class="px-4 py-3 text-sm">{{ number_format($data->oksigen, 1) }}</td>
								<td class="px-4 py-3 text-sm">{{ number_format($data->salinitas, 1) }}</td>
								<td class="px-4 py-3 text-sm">{{ $data->kualitas_air ?? '-' }}</td>
							</tr>
							@empty
							<tr>
								<td colspan="6" class="px-4 py-8 text-center text-sm text-zinc-500">Tidak ada data</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="px-4 py-3 border-t border-zinc-200">
					{{ $historicalData->links() }}
				</div>
			</div>
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
	</script>
</body>
</html>

