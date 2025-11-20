<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Login – E‑shrimp</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased text-zinc-800 bg-white">
	<div class="min-h-screen grid md:grid-cols-2">
		<div class="bg-zinc-100/80 flex items-center justify-center p-6">
			<div class="w-full max-w-md">
				<div class="mb-8">
					<div class="inline-flex items-center gap-2">
						<div class="h-6 w-6 rounded bg-zinc-900"></div>
						<span class="font-semibold">E‑Shrimp</span>
					</div>
				</div>
				<div class="rounded-2xl bg-white/70 backdrop-blur p-6 shadow-sm border border-zinc-200">
					<h1 class="text-lg font-semibold text-zinc-900">Welcome Back</h1>
					<p class="text-sm text-zinc-600 mt-1">Masuk untuk memantau atau mengelola tambak udang.</p>

					<form id="loginForm" class="space-y-3 mt-6">
						<div>
							<label class="block text-xs mb-1 text-zinc-700">Email</label>
							<input id="loginEmail" type="email" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-900/10">
						</div>
						<div>
							<label class="block text-xs mb-1 text-zinc-700">Password</label>
							<input id="loginPassword" type="password" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-900/10">
						</div>
						<button type="submit" class="w-full rounded-lg bg-zinc-900 text-white py-2 text-sm hover:bg-zinc-800">Masuk</button>
					</form>
				</div>
				<div class="mt-6 text-center">
					<a href="/" class="text-sm text-zinc-600 hover:text-zinc-900">← Kembali ke Landing</a>
				</div>
			</div>
		</div>
		<div class="relative hidden md:block">
			<div class="absolute inset-0 flex items-center justify-center px-8">
				<div class="max-w-xl text-center">
					<div class="text-5xl md:text-6xl lg:text-7xl font-semibold tracking-tight">E‑SHRIMP</div>
					<div class="text-zinc-700 mt-1 text-base md:text-lg">The best monitoring system</div>
					<div class="mt-2">
						<img src="/image/shrimp.png" alt="Shrimp" class="w-full mx-auto object-contain object-top max-h-[440px] lg:max-h-[560px]">
					</div>
					<div class="mt-4 grid grid-cols-2 gap-3 text-sm text-zinc-700">
						<ul class="space-y-2">
							<li>Data Analytics</li>
							<li>Real‑time Monitoring</li>
						</ul>
						<ul class="space-y-2">
							<li>Smart Alerts</li>
							<li>Export Reports</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>


