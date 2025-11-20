<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kelola Artikel – E‑shrimp</title>
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
				<a href="{{ route('admin.kelola-user') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola User</a>
				<a href="{{ route('admin.kelola-artikel') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Kelola Artikel</a>
				<a href="{{ route('admin.kelola-forum') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Forum</a>
			</nav>
		</aside>
		<main class="col-span-12 md:col-span-10 p-4 sm:p-6">
			<div class="flex items-center justify-between mb-6">
				<h1 class="text-lg sm:text-xl font-semibold">Kelola Artikel</h1>
				<button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Tambah Artikel</button>
			</div>
			
			<!-- Modal Tambah Artikel -->
			<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-2xl w-full p-6">
					<h2 class="font-semibold mb-4">Tambah Artikel</h2>
					<form action="{{ route('admin.tambah-artikel') }}" method="POST">
						@csrf
						<div class="space-y-4">
							<div>
								<label class="block text-sm mb-1">Judul</label>
								<input type="text" name="judul" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
							</div>
							<div>
								<label class="block text-sm mb-1">Konten</label>
								<textarea name="konten" rows="6" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
							</div>
							<div class="flex gap-2">
								<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Simpan</button>
								<button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="space-y-4">
				@forelse($artikels as $artikel)
				<div class="rounded-2xl border border-zinc-200 p-6">
					<div class="flex items-start justify-between">
						<div class="flex-1">
							<h3 class="font-semibold mb-2">{{ $artikel->judul }}</h3>
							<p class="text-sm text-zinc-600 mb-2">{{ Str::limit($artikel->konten, 150) }}</p>
							<div class="text-xs text-zinc-500">{{ $artikel->tanggal->format('d M Y') }}</div>
						</div>
						<div class="flex gap-2 ml-4">
							<button onclick="editArtikel('{{ $artikel->artikel_id }}', '{{ $artikel->judul }}', '{{ addslashes($artikel->konten) }}')" class="px-3 py-1 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-xs">Edit</button>
							<form action="{{ route('admin.hapus-artikel', $artikel->artikel_id) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel ini?')">
								@csrf
								@method('DELETE')
								<button type="submit" class="px-3 py-1 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 text-xs">Hapus</button>
							</form>
						</div>
					</div>
				</div>
				@empty
				<div class="text-center py-8 text-zinc-500">Tidak ada artikel</div>
				@endforelse
			</div>
			<div class="mt-4">{{ $artikels->links() }}</div>
		</main>
	</div>
	<script>
		function editArtikel(id, judul, konten) {
			// Simple edit - in production, use modal
			const newJudul = prompt('Judul:', judul);
			if (!newJudul) return;
			const newKonten = prompt('Konten:', konten);
			if (!newKonten) return;
			
			const form = document.createElement('form');
			form.method = 'POST';
			form.action = `/admin/artikel/${id}`;
			form.innerHTML = `
				@csrf
				@method('PUT')
				<input type="hidden" name="judul" value="${newJudul}">
				<input type="hidden" name="konten" value="${newKonten}">
			`;
			document.body.appendChild(form);
			form.submit();
		}
	</script>
</body>
</html>


