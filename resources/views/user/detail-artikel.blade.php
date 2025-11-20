<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $artikel->judul }} – E‑shrimp</title>
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
				<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white hover:border border-transparent hover:border-zinc-200">Dashboard</a>
				<a href="{{ route('user.artikel') }}" class="block px-3 py-2 rounded-lg bg-white border border-zinc-200 font-medium">Daily Article</a>
			</nav>
		</aside>
		<main class="col-span-12 md:col-span-10 p-4 sm:p-6">
			<a href="{{ route('user.artikel') }}" class="text-sm text-zinc-600 hover:text-zinc-900 mb-4 inline-block">← Kembali ke Artikel</a>
			<article class="max-w-3xl">
				<h1 class="text-2xl sm:text-3xl font-semibold mb-4">{{ $artikel->judul }}</h1>
				<div class="text-sm text-zinc-500 mb-6">{{ $artikel->tanggal->format('d M Y') }}</div>
				<div class="prose prose-sm max-w-none text-zinc-700 leading-relaxed whitespace-pre-line">{{ $artikel->konten }}</div>
			</article>
		</main>
	</div>
	@include('components.whatsapp-widget')
</body>
</html>

