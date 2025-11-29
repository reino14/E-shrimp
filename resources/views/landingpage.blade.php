<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>E‑shrimp – Smart Shrimp Farming</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<style>
	html {
    scroll-behavior: smooth;
	}
</style>
<body class="antialiased text-zinc-800 bg-white">
	<header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-zinc-200">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 py-4 flex items-center justify-between">
			<div class="flex items-center gap-3">
				<img src="/image/logo-eshrimp.png" alt="Logo" class="h-14 w-14 object-contain rounded" />
				<span class="font-semibold">E‑Shrimp</span>
			</div>
			<nav class="hidden md:flex items-center gap-6 text-sm">
				<a href="#home" class="hover:text-zinc-900 text-zinc-600">Home</a>
				<a href="#features" class="hover:text-zinc-900 text-zinc-600">Product</a>
				<a href="#boat" class="hover:text-zinc-900 text-zinc-600">Monitoring Boat</a>
				<a href="#contact" class="hover:text-zinc-900 text-zinc-600">Contact</a>
			</nav>
			<div class="flex items-center gap-2">
				<a href="/login" class="px-3 py-1.5 rounded-full text-sm border border-zinc-300 hover:bg-zinc-50">Log in</a>
				<a href="#contact" class="px-4 py-1.5 rounded-full text-sm bg-zinc-900 text-white hover:bg-zinc-800">Get Started</a>
			</div>
		</div>
	</header>

	<main>
		<section class="relative">
			<div id="home" class="mx-auto max-w-7xl px-4 sm:px-6 pt-14 pb-16 grid lg:grid-cols-2 gap-10 items-center">
				<div class="space-y-6">
					<div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 px-3 py-1 text-xs text-zinc-600">
						<span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
						Empowering Smart Shrimp Farming
					</div>
					<h1 class="text-4xl sm:text-5xl font-semibold tracking-tight">
						Automate <span class="text-zinc-500 font-normal">Monitoring with</span> E‑shrimp System
					</h1>
					<p class="text-zinc-600 max-w-xl">
						Real‑time pond metrics, anomaly alerts, and insights to maximize growth and reduce risk—powered by our IoT monitoring boat and integrated dashboard.
					</p>
					<div class="flex flex-wrap gap-3">
						<a href="#order"><button class="px-5 py-2 rounded-full bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Order Now!</button></a>
						<a href="#features"><button class="px-5 py-2 rounded-full border border-zinc-300 hover:bg-zinc-50 text-sm">Learn More</button></a>
					</div>
					<div class="flex items-center gap-6 pt-2 text-sm text-zinc-600">
						<div><span class="font-semibold text-zinc-900">Version</span> 1.0</div>
						<div class="hidden sm:block h-4 w-px bg-zinc-300"></div>
						<div class="flex items-center gap-2">
							<span class="font-semibold text-zinc-900">50+</span> farmers helped
							<div class="flex -space-x-2">
								<div class="h-5 w-5 rounded-full bg-zinc-900"></div>
								<div class="h-5 w-5 rounded-full bg-zinc-700"></div>
								<div class="h-5 w-5 rounded-full bg-zinc-500"></div>
								<div class="h-5 w-5 rounded-full bg-zinc-300"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="relative">
					<div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
						<img src="/image/shrimp farmer.png" alt="Shrimp illustration" class="w-full h-full object-cover object-center max-h-[560px] mx-auto">
					</div>
				</div>
			</div>
		</section>

		<section id="features" class="py-10 sm:py-16">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-center">
				<div class="aspect-[16/10] rounded-2xl overflow-hidden shadow-lg">
					<video 
						src="/image/e-shrimp-simulation.mp4" 
						class="w-full h-full object-cover"
						autoplay 
						muted 
						loop >
					</video>
				</div>
				<div>
					<h2 class="text-3xl sm:text-4xl font-semibold leading-tight">
						Digitilize Shrimp Farmer <span class="text-zinc-500 font-normal">Through Innovation</span>
					</h2>
					<p class="text-zinc-600 mt-4">Designed to improve productivity and sustainability, the system provides real-time insights that empower farmers to optimize every stage of cultivation.</p>
				</div>
			</div>

			<div class="mx-auto max-w-7xl px-4 sm:px-6 mt-10">
				<div class="rounded-2xl border border-zinc-200 p-6 sm:p-8 bg-white/60">
					<div class="grid sm:grid-cols-3 gap-8">
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-900 flex items-center justify-center">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
								</svg>
							</div>
							<h3 class="font-semibold">IoT Sensor Ready</h3>
							<p class="text-sm text-zinc-600">Capture pond metrics with sensors and automate routine checks to save time.</p>
						</div>
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-700 flex items-center justify-center">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
								</svg>
							</div>
							<h3 class="font-semibold">Insightful Alerts</h3>
							<p class="text-sm text-zinc-600">Real-time notifications help you react quickly before water quality deteriorates.</p>
						</div>
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-500 flex items-center justify-center">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
								</svg>
							</div>
							<h3 class="font-semibold">Growth Prediction</h3>
							<p class="text-sm text-zinc-600">Accurately forecast shrimp growth and feeding needs across different farms and cycles.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="boat" class="py-10 sm:py-16 bg-zinc-50">
			<div class="mx-auto max-w-7xl px-4 sm:px-6">
				<h2 class="text-2xl sm:text-3xl font-semibold mb-6 text-center md:text-left">IoT‑Based Monitoring Boat</h2>
				<div class="grid lg:grid-cols-2 gap-8">
					<div class="space-y-4">
						<div class="rounded-xl border border-zinc-200 p-5 bg-white">
							<h3 class="font-semibold mb-2">E‑SHRIMP Monitoring Robot</h3>
							<ul class="text-sm text-zinc-600 space-y-1 list-disc pl-5">
								<li>Real‑time DO, pH, temperature, salinity</li>
								<li>Remote Control</li>
								<li>Wi‑Fi/Bluetooth connectivity</li>
							</ul>
						</div>
						<div class="grid sm:grid-cols-3 gap-4">
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">100%</div>
								<div class="text-xs text-zinc-600">Farm Ready Design</div>
							</div>
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">24H</div>
								<div class="text-xs text-zinc-600">Continuous monitoring readiness</div>
							</div>
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">0 → 1</div>
								<div class="text-xs text-zinc-600">Transforming traditional shrimp farming</div>
							</div>
						</div>
					</div>
					<div class="aspect-[4/3] aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
						<img src="/image/boat.jpg" alt="Aquaculture technician" class="w-full h-full object-cover">
					</div>
				</div>

				<div class="grid lg:grid-cols-2 gap-8 mt-8">
					<div class="rounded-2xl border border-zinc-200 p-5 bg-white">
						<h3 class="font-semibold mb-3">Sensor Suite</h3>
						<div class="grid sm:grid-cols-2 gap-4 text-sm text-zinc-600">
							<ul class="list-disc pl-5 space-y-1">
								<li>Dissolved Oxygen</li>
								<li>pH</li>
								<li>Temperature</li>
							</ul>
							<ul class="list-disc pl-5 space-y-1">
								<li>Salinity</li>
								<li>Battery & System Health</li>
							</ul>
						</div>
					</div>
					<div class="rounded-2xl border border-zinc-200 p-5 bg-white">
						<h3 class="font-semibold mb-3">Integrated Monitoring System</h3>
						<div class="grid sm:grid-cols-2 gap-4 text-sm text-zinc-600">
							<ul class="list-disc pl-5 space-y-1">
								<li>Real-time monitoring chart</li>
								<li>Daily growth & feeding insights</li>
								<li>Real-time alert notifications</li>
							</ul>
							<ul class="list-disc pl-5 space-y-1">
								<li>Price Tracker</li>
								<li>Daily Article</li>
								<li>Community Forum</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="order" class="py-12 sm:py-16 bg-zinc-50">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-center">
				<div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
					<img src="/image/shrimp farm.png" alt="Happy farmer" class="w-full h-full object-cover">
				</div>
				<div>
					<h3 class="text-2xl sm:text-3xl font-semibold">Order Now <span class="text-zinc-500 font-normal">Your E‑shrimp System</span></h3>
					<p class="text-zinc-600 mt-3">Bring automation and data‑driven decisions to your farm.</p>
					<div class="mt-6">
						<a href="https://wa.me/6285694014009"><button class="px-5 py-2 rounded-full bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Contact Sales</button></a>
					</div>
				</div>
			</div>
		</section>
	</main>

	<footer id="contact" class="border-t border-zinc-200 py-10">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 grid md:grid-cols-4 gap-8 text-sm">
			<div class="space-y-2">
				<div class="flex items-center gap-2">
					<div class="h-6 w-6 rounded bg-zinc-900"></div>
					<span class="font-semibold">E‑Shrimp</span>
				</div>
				<p class="text-zinc-600">Smart monitoring for modern aquaculture.</p>
				<p class="text-zinc-500">&copy; <span id="y"></span> E‑Shrimp. All rights reserved.</p>
			</div>
			<div>
				<div class="font-semibold mb-2">Product</div>
				<ul class="space-y-1 text-zinc-600">
					<li><a href="#features" class="hover:text-zinc-900">Features</a></li>
					<li><a href="#boat" class="hover:text-zinc-900">Monitoring Boat</a></li>
				</ul>
			</div>
			<div>
				<div class="font-semibold mb-2">Company</div>
				<ul class="space-y-1 text-zinc-600">
					<li><a href="#" class="hover:text-zinc-900">About</a></li>
					<li><a href="#" class="hover:text-zinc-900">Blog</a></li>
					<li><a href="#" class="hover:text-zinc-900">Careers</a></li>
				</ul>
			</div>
			<div>
				<div class="font-semibold mb-2">Quick Contact</div>
				<ul class="space-y-1 text-zinc-600">
					<li>Email: hello@eshrimp.app</li>
					<li>Phone: +62 812‑3456‑7890</li>
					<li>Address: Indonesia</li>
				</ul>
			</div>
		</div>
		<script>
			document.getElementById('y').textContent = new Date().getFullYear();
		</script>
	</footer>
</body>
</html>


