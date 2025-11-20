<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Price Tracker – E‑shrimp</title>
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
				<a href="{{ route('user.histori-data') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Histori Data</a>
				<a href="{{ route('user.artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Daily Article</a>
				<a href="{{ route('user.forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Community</a>
				<a href="{{ route('user.prediksi-pertumbuhan') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Prediksi Pertumbuhan</a>
				<a href="{{ route('user.price-tracker') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Price Tracker</a>
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
			<div class="flex items-center justify-between mb-6">
				<h1 class="text-lg sm:text-xl font-semibold">Price Tracker - Harga Udang Pasaran</h1>
				<div class="text-xs text-zinc-500">Update: {{ now()->format('d M Y H:i') }}</div>
			</div>
			
			<div class="rounded-2xl border border-zinc-200 overflow-hidden">
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-zinc-50 border-b border-zinc-200">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Ukuran</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Harga (Rp/kg)</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Perubahan</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Trend</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-zinc-200">
							@foreach($hargaUdang as $harga)
							<tr>
								<td class="px-4 py-3 text-sm font-medium">{{ $harga['ukuran'] }}</td>
								<td class="px-4 py-3 text-sm">Rp {{ number_format($harga['harga'], 0, ',', '.') }}</td>
								<td class="px-4 py-3 text-sm">
									<span class="{{ $harga['trend'] === 'up' ? 'text-green-600' : 'text-red-600' }}">
										{{ $harga['perubahan'] }}
									</span>
								</td>
								<td class="px-4 py-3 text-sm">
									@if($harga['trend'] === 'up')
									<span class="inline-flex items-center gap-1 text-green-600">
										<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
										</svg>
										Naik
									</span>
									@else
									<span class="inline-flex items-center gap-1 text-red-600">
										<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
										</svg>
										Turun
									</span>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="mt-6 rounded-2xl border border-zinc-200 p-6 bg-zinc-50">
				<p class="text-sm text-zinc-600">
					<strong>Catatan:</strong> Harga yang ditampilkan adalah harga pasar rata-rata dan dapat berubah sewaktu-waktu. 
					Harga aktual dapat berbeda tergantung lokasi, kualitas, dan kondisi pasar. 
					Disarankan untuk melakukan verifikasi langsung dengan pembeli sebelum melakukan transaksi.
				</p>
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

