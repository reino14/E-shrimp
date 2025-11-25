<!-- Profil Modal -->
<div id="profilModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeProfilModal()">
	<div class="bg-white rounded-2xl max-w-md w-full shadow-xl">
		<div class="px-6 py-4 border-b border-zinc-200 flex items-center justify-between">
			<h2 class="text-lg font-semibold text-zinc-900">Profil Saya</h2>
			<button onclick="closeProfilModal()" class="text-zinc-400 hover:text-zinc-600 transition-colors">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>
		<div class="p-6" id="profilContent">
			<div class="text-center py-8">
				<div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
				<p class="mt-4 text-sm text-zinc-500">Memuat data profil...</p>
			</div>
		</div>
	</div>
</div>

<script>
	async function openProfilModal() {
		const modal = document.getElementById('profilModal');
		const content = document.getElementById('profilContent');
		
		// Show modal
		modal.classList.remove('hidden');
		
		// Reset content to loading state
		content.innerHTML = `
			<div class="text-center py-8">
				<div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
				<p class="mt-4 text-sm text-zinc-500">Memuat data profil...</p>
			</div>
		`;
		
		// Fetch profil data
		try {
			const response = await fetch('{{ route("user.profil") }}', {
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
				},
			});
			
			if (!response.ok) {
				const errorText = await response.text();
				throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
			}
			
			const contentType = response.headers.get('content-type');
			if (!contentType || !contentType.includes('application/json')) {
				throw new Error('Response is not JSON');
			}
			
			const result = await response.json();
			
			if (result.success && result.peternak) {
				const peternak = result.peternak;
				content.innerHTML = `
					<div class="space-y-4">
						<div>
							<label class="block text-sm font-medium text-zinc-700 mb-1">Nama</label>
							<div class="text-sm text-zinc-900">${peternak.nama || '-'}</div>
						</div>
						<div>
							<label class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
							<div class="text-sm text-zinc-900">${peternak.email || '-'}</div>
						</div>
						<div>
							<label class="block text-sm font-medium text-zinc-700 mb-1">Role</label>
							<div class="text-sm text-zinc-900">${peternak.role || 'Peternak'}</div>
						</div>
						${peternak.tracker_id ? `
						<div>
							<label class="block text-sm font-medium text-zinc-700 mb-1">Tracker ID</label>
							<div class="text-sm text-zinc-900">${peternak.tracker_id}</div>
						</div>
						` : ''}
					</div>
				`;
			} else {
				content.innerHTML = `
					<div class="text-center py-8 text-zinc-500">
						<p class="text-sm">${result.message || 'Data profil tidak ditemukan. Silakan login kembali.'}</p>
					</div>
				`;
			}
		} catch (error) {
			console.error('Error loading profil:', error);
			let errorMessage = 'Gagal memuat data profil. Silakan coba lagi.';
			
			// Try to parse error message if it's a JSON response
			if (error.message && error.message.includes('JSON')) {
				errorMessage = 'Format data tidak valid. Silakan refresh halaman.';
			} else if (error.message && error.message.includes('404')) {
				errorMessage = 'Data profil tidak ditemukan. Silakan login kembali.';
			}
			
			content.innerHTML = `
				<div class="text-center py-8 text-red-500">
					<p class="text-sm">${errorMessage}</p>
				</div>
			`;
		}
	}

	function closeProfilModal() {
		const modal = document.getElementById('profilModal');
		modal.classList.add('hidden');
	}

	// Close modal on Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			closeProfilModal();
		}
	});
</script>






