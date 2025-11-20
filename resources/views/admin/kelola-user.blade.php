<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kelola User – E‑shrimp</title>
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
				<h1 class="text-lg sm:text-xl font-semibold">Kelola Peternak</h1>
				<button onclick="document.getElementById('modalTambahPeternak').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Tambah Peternak</button>
			</div>

			<!-- Modal Tambah Peternak -->
			<div id="modalTambahPeternak" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-md w-full p-6">
					<h2 class="font-semibold mb-4">Tambah Peternak Baru</h2>
					<form id="formTambahPeternak" class="space-y-4">
						<div>
							<label class="block text-sm mb-1">Email</label>
							<input type="email" id="peternakEmail" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
						</div>
						<div>
							<label class="block text-sm mb-1">Nama</label>
							<input type="text" id="peternakNama" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
						</div>
						<div>
							<label class="block text-sm mb-1">Password</label>
							<input type="password" id="peternakPassword" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
						</div>
						<div>
							<label class="block text-sm mb-1">Tracker ID (Opsional)</label>
							<input type="text" id="peternakTrackerId" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
						</div>
						<div id="errorMessage" class="text-sm text-red-600 hidden"></div>
						<div class="flex gap-2">
							<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Simpan</button>
							<button type="button" onclick="document.getElementById('modalTambahPeternak').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
						</div>
					</form>
				</div>
			</div>

			<div class="rounded-2xl border border-zinc-200 overflow-hidden">
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-zinc-50 border-b border-zinc-200">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Email</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Nama</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Role</th>
								<th class="px-4 py-3 text-left text-xs font-semibold text-zinc-700">Aksi</th>
							</tr>
						</thead>
						<tbody id="peternakTableBody" class="divide-y divide-zinc-200">
							<tr>
								<td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500">Memuat data...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</main>
	</div>
	<script>
		// Wait for Vite bundle to load, then use window.firebaseRTDB
		let getAllPeternaks, createPeternak, deletePeternak;
		
		function initFunctions() {
			if (window.firebaseRTDB) {
				getAllPeternaks = window.firebaseRTDB.getAllPeternaks;
				createPeternak = window.firebaseRTDB.createPeternak;
				deletePeternak = window.firebaseRTDB.deletePeternak;
				loadPeternaks();
			} else {
				// Retry after a short delay
				setTimeout(initFunctions, 100);
			}
		}
		
		// Start initialization
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initFunctions);
		} else {
			initFunctions();
		}

		// Load peternaks from RDTB
		async function loadPeternaks() {
			if (!getAllPeternaks) {
				console.error('Functions not loaded yet');
				return;
			}
			const tbody = document.getElementById('peternakTableBody');
			try {
				const peternaks = await getAllPeternaks();
				
				if (peternaks.length === 0) {
					tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500">Tidak ada peternak</td></tr>';
					return;
				}

				tbody.innerHTML = peternaks.map(peternak => `
					<tr>
						<td class="px-4 py-3 text-sm">${peternak.email_peternak}</td>
						<td class="px-4 py-3 text-sm">${peternak.nama || '-'}</td>
						<td class="px-4 py-3 text-sm">${peternak.role || 'peternak'}</td>
						<td class="px-4 py-3 text-sm">
							<button onclick="hapusPeternak('${peternak.email_peternak}')" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
						</td>
					</tr>
				`).join('');
			} catch (error) {
				console.error('Error loading peternaks:', error);
				tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-sm text-red-500">Error memuat data</td></tr>';
			}
		}

		// Handle tambah peternak form
		document.getElementById('formTambahPeternak').addEventListener('submit', async (e) => {
			e.preventDefault();
			
			if (!createPeternak) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			const email = document.getElementById('peternakEmail').value.trim();
			const nama = document.getElementById('peternakNama').value.trim();
			const password = document.getElementById('peternakPassword').value;
			const trackerId = document.getElementById('peternakTrackerId').value.trim() || null;
			const errorMsg = document.getElementById('errorMessage');
			const submitBtn = e.target.querySelector('button[type="submit"]');
			
			try {
				submitBtn.disabled = true;
				submitBtn.textContent = "Menyimpan...";
				errorMsg.classList.add('hidden');

				const result = await createPeternak(email, nama, password, trackerId);
				
				if (result.success) {
					document.getElementById('modalTambahPeternak').classList.add('hidden');
					e.target.reset();
					await loadPeternaks();
					alert('Peternak berhasil ditambahkan!');
				} else {
					errorMsg.textContent = result.error || 'Gagal menambahkan peternak';
					errorMsg.classList.remove('hidden');
				}
			} catch (error) {
				errorMsg.textContent = 'Terjadi kesalahan: ' + error.message;
				errorMsg.classList.remove('hidden');
			} finally {
				submitBtn.disabled = false;
				submitBtn.textContent = "Simpan";
			}
		});

		// Handle hapus peternak
		window.hapusPeternak = async function(email) {
			if (!deletePeternak) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			if (!confirm(`Yakin hapus peternak ${email}?`)) return;
			
			try {
				const result = await deletePeternak(email);
				if (result.success) {
					await loadPeternaks();
					alert('Peternak berhasil dihapus!');
				} else {
					alert('Gagal menghapus peternak: ' + (result.error || 'Unknown error'));
				}
			} catch (error) {
				alert('Terjadi kesalahan: ' + error.message);
			}
		};

	</script>
</body>
</html>

