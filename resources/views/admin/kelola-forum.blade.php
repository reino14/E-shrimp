<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kelola Forum – E‑shrimp</title>
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
				<a href="{{ route('admin.kelola-artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Kelola Artikel</a>
				<a href="{{ route('admin.kelola-forum') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Kelola Forum</a>
			</nav>
		</aside>
		<main class="col-span-12 md:col-span-10 p-4 sm:p-6">
			<div class="flex items-center justify-between mb-6">
				<h1 class="text-lg sm:text-xl font-semibold">Kelola Forum</h1>
				<button onclick="document.getElementById('modalTambahPost').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Tambah Post</button>
			</div>

			<!-- Modal Tambah Post -->
			<div id="modalTambahPost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
					<h2 class="font-semibold mb-4">Tambah Post Baru</h2>
					<form id="formTambahPost" class="space-y-4">
						<div>
							<label class="block text-sm mb-1">Judul</label>
							<input type="text" id="postJudul" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
						</div>
						<div>
							<label class="block text-sm mb-1">Isi</label>
							<textarea id="postIsi" rows="6" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
						</div>
						<div id="errorMessagePost" class="text-sm text-red-600 hidden"></div>
						<div class="flex gap-2">
							<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Post</button>
							<button type="button" onclick="document.getElementById('modalTambahPost').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Modal Reply -->
			<div id="modalReply" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-2xl w-full p-6">
					<h2 class="font-semibold mb-4">Reply Post</h2>
					<form id="formReply" class="space-y-4">
						<input type="hidden" id="replyParentId">
						<div>
							<label class="block text-sm mb-1">Isi Reply</label>
							<textarea id="replyIsi" rows="4" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
						</div>
						<div id="errorMessageReply" class="text-sm text-red-600 hidden"></div>
						<div class="flex gap-2">
							<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Reply</button>
							<button type="button" onclick="document.getElementById('modalReply').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
						</div>
					</form>
				</div>
			</div>

			<div id="forumList" class="space-y-4">
				<div class="text-center py-8 text-zinc-500">Memuat data...</div>
			</div>
		</main>
	</div>
	<script>
		// Wait for Vite bundle to load, then use window.firebaseRTDB
		let getAllForumPosts, createForumPost, replyForumPost, deleteForumPost, deletePeternakPosts, getAllPeternaks;
		
		function initFunctions() {
			if (window.firebaseRTDB) {
				getAllForumPosts = window.firebaseRTDB.getAllForumPosts;
				createForumPost = window.firebaseRTDB.createForumPost;
				replyForumPost = window.firebaseRTDB.replyForumPost;
				deleteForumPost = window.firebaseRTDB.deleteForumPost;
				deletePeternakPosts = window.firebaseRTDB.deletePeternakPosts;
				getAllPeternaks = window.firebaseRTDB.getAllPeternaks;
				loadForums();
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

		// Load forums from RDTB
		async function loadForums() {
			if (!getAllForumPosts) {
				console.error('Functions not loaded yet');
				return;
			}
			
			const forumList = document.getElementById('forumList');
			try {
				const forums = await getAllForumPosts();
				const peternaks = await getAllPeternaks();
				const peternakMap = {};
				peternaks.forEach(p => {
					peternakMap[p.email_peternak] = p.nama || p.email_peternak;
				});
				
				if (forums.length === 0) {
					forumList.innerHTML = '<div class="text-center py-8 text-zinc-500">Tidak ada post forum</div>';
					return;
				}

				forumList.innerHTML = forums.map(forum => {
					const tanggal = forum.tanggal_iso 
						? new Date(forum.tanggal_iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
						: new Date(forum.tanggal || 0).toLocaleDateString('id-ID');
					const nama = forum.email_peternak ? (peternakMap[forum.email_peternak] || forum.email_peternak) : 'Admin';
					
					// Build replies HTML
					let repliesHTML = '';
					if (forum.replies && Object.keys(forum.replies).length > 0) {
						repliesHTML = `
							<div class="mt-4 pl-4 border-l-2 border-zinc-300 space-y-3">
								${Object.entries(forum.replies).map(([replyId, reply]) => {
									const replyTanggal = reply.tanggal_iso 
										? new Date(reply.tanggal_iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
										: (reply.created_at_iso 
											? new Date(reply.created_at_iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
											: new Date(reply.tanggal || reply.created_at || 0).toLocaleDateString('id-ID'));
									const replyNama = reply.email_peternak ? (peternakMap[reply.email_peternak] || reply.email_peternak) : 'Admin';
									return `
										<div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4">
											<p class="text-sm text-zinc-700 mb-2 whitespace-pre-wrap">${reply.isi || ''}</p>
											<div class="flex items-center justify-between">
												<div class="flex items-center gap-3 text-xs text-zinc-500">
													<span>Oleh: ${replyNama}</span>
													<span>${replyTanggal}</span>
													<span class="text-blue-600">Reply</span>
												</div>
												<button onclick="hapusPost('${replyId}')" class="px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50 text-xs">Hapus</button>
											</div>
										</div>
									`;
								}).join('')}
							</div>
						`;
					}
					
					return `
						<div class="rounded-2xl border border-zinc-200 p-6">
							<div class="flex items-start justify-between">
								<div class="flex-1">
									<h3 class="font-semibold mb-2">${forum.judul || 'Untitled'}</h3>
									<p class="text-sm text-zinc-600 mb-3 whitespace-pre-wrap">${forum.isi || ''}</p>
									<div class="flex items-center gap-4 text-xs text-zinc-500">
										<span>Oleh: ${nama}</span>
										<span>${tanggal}</span>
									</div>
									${repliesHTML}
								</div>
								<div class="flex gap-2 ml-4">
									${!forum.post_peternak_id ? `<button onclick="replyPost('${forum.id || forum.forum_id}', '${(forum.judul || '').replace(/'/g, "\\'")}')" class="px-3 py-1 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-xs">Reply</button>` : ''}
									<button onclick="hapusPost('${forum.id || forum.forum_id}')" class="px-3 py-1 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 text-xs">Hapus</button>
									${forum.email_peternak ? `<button onclick="hapusPostPeternak('${forum.email_peternak}')" class="px-3 py-1 rounded-lg border border-orange-300 text-orange-600 hover:bg-orange-50 text-xs">Hapus Semua Post Peternak</button>` : ''}
								</div>
							</div>
						</div>
					`;
				}).join('');
			} catch (error) {
				console.error('Error loading forums:', error);
				forumList.innerHTML = '<div class="text-center py-8 text-red-500">Error memuat data</div>';
			}
		}

		// Handle tambah post form
		document.getElementById('formTambahPost').addEventListener('submit', async (e) => {
			e.preventDefault();
			
			if (!createForumPost) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			const judul = document.getElementById('postJudul').value.trim();
			const isi = document.getElementById('postIsi').value.trim();
			const errorMsg = document.getElementById('errorMessagePost');
			const submitBtn = e.target.querySelector('button[type="submit"]');
			
			try {
				submitBtn.disabled = true;
				submitBtn.textContent = "Memposting...";
				errorMsg.classList.add('hidden');

				const result = await createForumPost(judul, isi, null); // null = admin post
				
				if (result.success) {
					document.getElementById('modalTambahPost').classList.add('hidden');
					e.target.reset();
					await loadForums();
					alert('Post berhasil ditambahkan!');
				} else {
					errorMsg.textContent = result.error || 'Gagal menambahkan post';
					errorMsg.classList.remove('hidden');
				}
			} catch (error) {
				errorMsg.textContent = 'Terjadi kesalahan: ' + error.message;
				errorMsg.classList.remove('hidden');
			} finally {
				submitBtn.disabled = false;
				submitBtn.textContent = "Post";
			}
		});

		// Handle reply form
		document.getElementById('formReply').addEventListener('submit', async (e) => {
			e.preventDefault();
			
			if (!replyForumPost) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			const parentId = document.getElementById('replyParentId').value;
			const isi = document.getElementById('replyIsi').value.trim();
			const errorMsg = document.getElementById('errorMessageReply');
			const submitBtn = e.target.querySelector('button[type="submit"]');
			
			try {
				submitBtn.disabled = true;
				submitBtn.textContent = "Memposting...";
				errorMsg.classList.add('hidden');

				const result = await replyForumPost(parentId, isi, null); // null = admin reply
				
				if (result.success) {
					document.getElementById('modalReply').classList.add('hidden');
					e.target.reset();
					await loadForums();
					alert('Reply berhasil ditambahkan!');
				} else {
					errorMsg.textContent = result.error || 'Gagal menambahkan reply';
					errorMsg.classList.remove('hidden');
				}
			} catch (error) {
				errorMsg.textContent = 'Terjadi kesalahan: ' + error.message;
				errorMsg.classList.remove('hidden');
			} finally {
				submitBtn.disabled = false;
				submitBtn.textContent = "Reply";
			}
		});

		// Handle reply button
		window.replyPost = function(forumId, judul) {
			document.getElementById('replyParentId').value = forumId;
			document.getElementById('replyIsi').value = '';
			document.getElementById('errorMessageReply').classList.add('hidden');
			document.getElementById('modalReply').classList.remove('hidden');
		};

		// Handle hapus post
		window.hapusPost = async function(forumId) {
			if (!deleteForumPost) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			if (!confirm('Yakin hapus post ini?')) return;
			
			try {
				const result = await deleteForumPost(forumId);
				if (result.success) {
					await loadForums();
					alert('Post berhasil dihapus!');
				} else {
					alert('Gagal menghapus post: ' + (result.error || 'Unknown error'));
				}
			} catch (error) {
				alert('Terjadi kesalahan: ' + error.message);
			}
		};

		// Handle hapus semua post peternak
		window.hapusPostPeternak = async function(emailPeternak) {
			if (!deletePeternakPosts) {
				alert('Modul belum dimuat. Silakan refresh halaman.');
				return;
			}
			
			if (!confirm(`Yakin hapus semua post dari peternak ${emailPeternak}?`)) return;
			
			try {
				const result = await deletePeternakPosts(emailPeternak);
				if (result.success) {
					await loadForums();
					alert('Semua post peternak berhasil dihapus!');
				} else {
					alert('Gagal menghapus post: ' + (result.error || 'Unknown error'));
				}
			} catch (error) {
				alert('Terjadi kesalahan: ' + error.message);
			}
		};
	</script>
</body>
</html>
