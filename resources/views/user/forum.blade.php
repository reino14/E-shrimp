<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Forum – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
				<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
					</svg>
					Dashboard
				</a>
				<a href="{{ route('user.manajemen-kapal') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
					</svg>
					Manajemen Kapal
				</a>
				<a href="{{ route('user.manajemen-kolam') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
					</svg>
					Manajemen Kolam
				</a>
				<a href="{{ route('user.histori-data') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
					Histori Data
				</a>
				<a href="{{ route('user.artikel') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
					</svg>
					Daily Article
				</a>
				<a href="{{ route('user.forum') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
					</svg>
					Community
				</a>
				<a href="{{ route('user.prediksi-pertumbuhan') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
					</svg>
					Prediksi Pertumbuhan
				</a>
				<a href="{{ route('user.price-tracker') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
					Price Tracker
				</a>
			</nav>
			<div class="mt-8">
				<div class="text-xs uppercase text-zinc-500 tracking-wide mb-1">Other</div>
				<button onclick="openProfilModal()" class="w-full text-left block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
					</svg>
					Profil
				</button>
				<a href="/" data-logout class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200 text-sm flex items-center gap-2">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
					</svg>
					Logout
				</a>
			</div>
		</aside>
		<!-- Backdrop for mobile drawer -->
		<div id="backdrop" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>

		<main class="col-span-12 md:col-span-10 p-4 sm:p-6 md:ml-0">
			<div class="flex items-center justify-between mb-6">
				<h1 class="text-lg sm:text-xl font-semibold">Community Forum</h1>
				<button onclick="document.getElementById('modalPost').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Post Baru</button>
			</div>

			<!-- Modal Post -->
			<div id="modalPost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
				<div class="bg-white rounded-2xl max-w-2xl w-full p-6">
					<h2 class="font-semibold mb-4">Buat Post Baru</h2>
					<form id="formPostForum">
						<div class="space-y-4">
							<div>
								<label class="block text-sm mb-1">Judul</label>
								<input type="text" id="postJudul" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
							</div>
							<div>
								<label class="block text-sm mb-1">Isi</label>
								<textarea id="postIsi" rows="6" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm"></textarea>
							</div>
							<div id="errorMessage" class="text-sm text-red-600 hidden"></div>
							<div class="flex gap-2">
								<button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Post</button>
								<button type="button" onclick="document.getElementById('modalPost').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-sm">Batal</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div id="forumContainer" class="space-y-4">
				<div class="text-center py-8 text-zinc-500">Memuat forum...</div>
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

		// Load forum posts from Firebase RDTB
		let getAllForumPosts, createForumPost, replyForumPost;
		
		async function initForumFunctions() {
			if (window.firebaseRTDB) {
				getAllForumPosts = window.firebaseRTDB.getAllForumPosts;
				createForumPost = window.firebaseRTDB.createForumPost;
				replyForumPost = window.firebaseRTDB.replyForumPost;
				loadForums();
			} else {
				setTimeout(initForumFunctions, 100);
			}
		}

		async function loadForums() {
			if (!getAllForumPosts) {
				console.error('Forum functions not loaded yet');
				return;
			}
			
			const container = document.getElementById('forumContainer');
			try {
				const forums = await getAllForumPosts();
				
				if (forums.length === 0) {
					container.innerHTML = '<div class="text-center py-8 text-zinc-500">Tidak ada post forum</div>';
					return;
				}

				// Sort by created_at descending
				forums.sort((a, b) => (b.created_at || 0) - (a.created_at || 0));

				container.innerHTML = forums.map(forum => {
					const date = forum.created_at ? new Date(forum.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
					const author = forum.email_peternak || forum.email_admin || 'Unknown';
					
					// Build replies HTML
					let repliesHTML = '';
					if (forum.replies && Object.keys(forum.replies).length > 0) {
						repliesHTML = `
							<div class="mt-4 pl-4 border-l-2 border-zinc-300 space-y-3">
								${Object.entries(forum.replies).map(([replyId, reply]) => {
									const replyDate = reply.created_at ? new Date(reply.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
									const replyAuthor = reply.email_peternak || reply.email_admin || 'Unknown';
									return `
										<div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4">
											<p class="text-sm text-zinc-700 mb-2 whitespace-pre-wrap">${reply.isi || ''}</p>
											<div class="flex items-center gap-3 text-xs text-zinc-500">
												<span>Oleh: ${replyAuthor}</span>
												<span>${replyDate}</span>
												<span class="text-blue-600">Reply</span>
											</div>
										</div>
									`;
								}).join('')}
							</div>
						`;
					}
					
					return `
						<div class="rounded-2xl border border-zinc-200 p-6">
							<h3 class="font-semibold mb-2">${forum.judul || 'No Title'}</h3>
							<p class="text-sm text-zinc-600 mb-3 whitespace-pre-wrap">${forum.isi || ''}</p>
							<div class="flex items-center justify-between text-xs text-zinc-500">
								<span>Oleh: ${author} | ${date}</span>
								${!forum.post_peternak_id ? `<button onclick="showReplyForm('${forum.id}')" class="px-3 py-1 rounded-lg border border-zinc-300 hover:bg-zinc-50 text-xs">Reply</button>` : ''}
							</div>
							${repliesHTML}
						</div>
					`;
				}).join('');
			} catch (error) {
				console.error('Error loading forums:', error);
				container.innerHTML = '<div class="text-center py-8 text-red-500">Error memuat forum</div>';
			}
		}

		// Handle post form
		document.getElementById('formPostForum').addEventListener('submit', async (e) => {
			e.preventDefault();
			
			if (!createForumPost) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Modul belum dimuat. Silakan refresh halaman.'
				});
				return;
			}
			
			const judul = document.getElementById('postJudul').value.trim();
			const isi = document.getElementById('postIsi').value.trim();
			const errorMsg = document.getElementById('errorMessage');
			const submitBtn = e.target.querySelector('button[type="submit"]');
			const userEmail = sessionStorage.getItem('userEmail');
			
			try {
				submitBtn.disabled = true;
				submitBtn.textContent = "Memposting...";
				errorMsg.classList.add('hidden');

				const result = await createForumPost(judul, isi, userEmail);
				
				if (result.success) {
					document.getElementById('modalPost').classList.add('hidden');
					e.target.reset();
					await loadForums();
				} else {
					errorMsg.textContent = result.error || 'Gagal membuat post';
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

		// Modal Reply
		const modalReply = document.createElement('div');
		modalReply.id = 'modalReply';
		modalReply.className = 'hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';
		modalReply.innerHTML = `
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
		`;
		document.body.appendChild(modalReply);

		// Handle reply form
		document.getElementById('formReply').addEventListener('submit', async (e) => {
			e.preventDefault();
			
			if (!replyForumPost) {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Modul belum dimuat. Silakan refresh halaman.'
				});
				return;
			}
			
			const parentId = document.getElementById('replyParentId').value;
			const isi = document.getElementById('replyIsi').value.trim();
			const errorMsg = document.getElementById('errorMessageReply');
			const submitBtn = e.target.querySelector('button[type="submit"]');
			const userEmail = sessionStorage.getItem('userEmail');
			
			try {
				submitBtn.disabled = true;
				submitBtn.textContent = "Memposting...";
				errorMsg.classList.add('hidden');

				const result = await replyForumPost(parentId, isi, userEmail);
				
				if (result.success) {
					document.getElementById('modalReply').classList.add('hidden');
					e.target.reset();
					await loadForums();
				} else {
					errorMsg.textContent = result.error || 'Gagal membuat reply';
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

		function showReplyForm(forumId) {
			document.getElementById('replyParentId').value = forumId;
			document.getElementById('replyIsi').value = '';
			document.getElementById('errorMessageReply').classList.add('hidden');
			document.getElementById('modalReply').classList.remove('hidden');
		}

		// Initialize
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initForumFunctions);
		} else {
			initForumFunctions();
		}
	</script>
	@include('components.profil-modal')
</body>
</html>

