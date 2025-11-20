<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Klasifikasi Kualitas – E‑shrimp</title>
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
				<a href="{{ route('user.price-tracker') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Price Tracker</a>
				<a href="{{ route('user.klasifikasi-kualitas') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Klasifikasi Kualitas</a>
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
			<h1 class="text-lg sm:text-xl font-semibold mb-6">Klasifikasi Kualitas Tambak Udang</h1>
			
			@if($latestData)
			<!-- Overall Status -->
			<div class="mb-6 rounded-2xl border-2 p-6 
				@if($klasifikasi['overall']['warna'] === 'green') border-green-500 bg-green-50
				@elseif($klasifikasi['overall']['warna'] === 'yellow') border-yellow-500 bg-yellow-50
				@elseif($klasifikasi['overall']['warna'] === 'red') border-red-500 bg-red-50
				@else border-zinc-300 bg-zinc-50
				@endif">
				<div class="flex items-center justify-between">
					<div>
						<h2 class="text-xl font-semibold mb-1">Status Keseluruhan: {{ $klasifikasi['overall']['status'] }}</h2>
						<p class="text-sm">{{ $klasifikasi['overall']['deskripsi'] }}</p>
					</div>
					<div class="text-3xl font-bold 
						@if($klasifikasi['overall']['warna'] === 'green') text-green-600
						@elseif($klasifikasi['overall']['warna'] === 'yellow') text-yellow-600
						@elseif($klasifikasi['overall']['warna'] === 'red') text-red-600
						@else text-zinc-600
						@endif">
						{{ $klasifikasi['overall']['kategori'] }}
					</div>
				</div>
			</div>

			<!-- Parameter Details -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<!-- pH -->
				<div class="rounded-2xl border border-zinc-200 p-6">
					<div class="flex items-center justify-between mb-4">
						<h3 class="font-semibold">pH</h3>
						<span class="text-2xl font-bold">{{ number_format($latestData->ph ?? 0, 1) }}</span>
					</div>
					<div class="space-y-2">
						<div class="flex items-center gap-2">
							<span class="inline-block w-3 h-3 rounded-full 
								@if($klasifikasi['ph']['warna'] === 'green') bg-green-500
								@elseif($klasifikasi['ph']['warna'] === 'yellow') bg-yellow-500
								@else bg-red-500
								@endif"></span>
							<span class="text-sm font-medium">{{ $klasifikasi['ph']['status'] }}</span>
						</div>
						<p class="text-xs text-zinc-600">{{ $klasifikasi['ph']['deskripsi'] }}</p>
					</div>
				</div>

				<!-- Suhu -->
				<div class="rounded-2xl border border-zinc-200 p-6">
					<div class="flex items-center justify-between mb-4">
						<h3 class="font-semibold">Suhu</h3>
						<span class="text-2xl font-bold">{{ number_format($latestData->suhu ?? 0, 1) }}°C</span>
					</div>
					<div class="space-y-2">
						<div class="flex items-center gap-2">
							<span class="inline-block w-3 h-3 rounded-full 
								@if($klasifikasi['suhu']['warna'] === 'green') bg-green-500
								@elseif($klasifikasi['suhu']['warna'] === 'yellow') bg-yellow-500
								@else bg-red-500
								@endif"></span>
							<span class="text-sm font-medium">{{ $klasifikasi['suhu']['status'] }}</span>
						</div>
						<p class="text-xs text-zinc-600">{{ $klasifikasi['suhu']['deskripsi'] }}</p>
					</div>
				</div>

				<!-- Oksigen -->
				<div class="rounded-2xl border border-zinc-200 p-6">
					<div class="flex items-center justify-between mb-4">
						<h3 class="font-semibold">Oksigen Terlarut</h3>
						<span class="text-2xl font-bold">{{ number_format($latestData->oksigen ?? 0, 1) }} mg/L</span>
					</div>
					<div class="space-y-2">
						<div class="flex items-center gap-2">
							<span class="inline-block w-3 h-3 rounded-full 
								@if($klasifikasi['oksigen']['warna'] === 'green') bg-green-500
								@elseif($klasifikasi['oksigen']['warna'] === 'yellow') bg-yellow-500
								@else bg-red-500
								@endif"></span>
							<span class="text-sm font-medium">{{ $klasifikasi['oksigen']['status'] }}</span>
						</div>
						<p class="text-xs text-zinc-600">{{ $klasifikasi['oksigen']['deskripsi'] }}</p>
					</div>
				</div>

				<!-- Salinitas -->
				<div class="rounded-2xl border border-zinc-200 p-6">
					<div class="flex items-center justify-between mb-4">
						<h3 class="font-semibold">Salinitas</h3>
						<span class="text-2xl font-bold">{{ number_format($latestData->salinitas ?? 0, 1) }}‰</span>
					</div>
					<div class="space-y-2">
						<div class="flex items-center gap-2">
							<span class="inline-block w-3 h-3 rounded-full 
								@if($klasifikasi['salinitas']['warna'] === 'green') bg-green-500
								@elseif($klasifikasi['salinitas']['warna'] === 'yellow') bg-yellow-500
								@else bg-red-500
								@endif"></span>
							<span class="text-sm font-medium">{{ $klasifikasi['salinitas']['status'] }}</span>
						</div>
						<p class="text-xs text-zinc-600">{{ $klasifikasi['salinitas']['deskripsi'] }}</p>
					</div>
				</div>
			</div>

			<div class="mt-6 text-xs text-zinc-500">
				Data terakhir: {{ $latestData->waktu ? $latestData->waktu->format('d M Y H:i') : '-' }}
			</div>
			@else
			<div class="rounded-2xl border border-zinc-200 p-8 text-center">
				<p class="text-zinc-500">Belum ada data sensor. Data akan muncul setelah robot kapal mengambil sampel.</p>
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
	</script>
</body>
</html>

