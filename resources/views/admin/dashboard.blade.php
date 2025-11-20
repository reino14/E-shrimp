<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard – E‑shrimp</title>
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
				<a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Dashboard</a>
				<a href="{{ route('admin.kelola-user') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola User</a>
				<a href="{{ route('admin.kelola-artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Artikel</a>
				<a href="{{ route('admin.kelola-forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Forum</a>
			</nav>
			<div class="mt-8">
				<div class="text-xs uppercase text-zinc-500 tracking-wide mb-1">Other</div>
				<a href="/" data-logout class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm">Logout</a>
			</div>
		</aside>
		<!-- Backdrop for mobile drawer -->
		<div id="backdrop" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>

		<main class="col-span-12 md:col-span-10 p-4 sm:p-6 md:ml-0">
			<div class="flex items-center justify-between mb-6">
				<h1 class="text-lg sm:text-xl font-semibold">Admin Dashboard</h1>
			</div>

			<!-- Stats Cards -->
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
				<div class="rounded-xl bg-zinc-800 text-white p-6">
					<div class="text-sm text-zinc-300">Total Users</div>
					<div class="text-3xl font-semibold mt-2">{{ $totalUsers }}</div>
				</div>
				<div class="rounded-xl bg-zinc-800 text-white p-6">
					<div class="text-sm text-zinc-300">Total Artikel</div>
					<div class="text-3xl font-semibold mt-2">{{ $totalArtikel }}</div>
				</div>
				<div class="rounded-xl bg-zinc-800 text-white p-6">
					<div class="text-sm text-zinc-300">Total Forum Posts</div>
					<div class="text-3xl font-semibold mt-2">{{ $totalForum }}</div>
				</div>
			</div>

			<!-- Quick Actions -->
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
				<div class="rounded-2xl border border-zinc-200 p-6">
					<h2 class="font-semibold mb-4">Quick Actions</h2>
					<div class="space-y-2">
						<a href="{{ route('admin.kelola-user') }}" class="block px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm text-center">Kelola User</a>
						<a href="{{ route('admin.kelola-artikel') }}" class="block px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm text-center">Kelola Artikel</a>
						<a href="{{ route('admin.kelola-forum') }}" class="block px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm text-center">Kelola Forum</a>
					</div>
				</div>
				<div class="rounded-2xl border border-zinc-200 p-6">
					<h2 class="font-semibold mb-4">Recent Activity</h2>
					<ul class="text-sm text-zinc-600 space-y-2">
						<li>• Sistem berjalan normal</li>
						<li>• Semua fitur aktif</li>
						<li>• Database terhubung</li>
					</ul>
				</div>
			</div>
		</main>
	</div>
	<script>
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


