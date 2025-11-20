<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>E‑shrimp – Smart Shrimp Farming</title>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased text-zinc-800 bg-white">
	<header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-zinc-200">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 py-4 flex items-center justify-between">
			<div class="flex items-center gap-3">
				<div class="h-6 w-6 rounded bg-zinc-900"></div>
				<span class="font-semibold">E‑Shrimp</span>
			</div>
			<nav class="hidden md:flex items-center gap-6 text-sm">
				<a href="#features" class="hover:text-zinc-900 text-zinc-600">Features</a>
				<a href="#boat" class="hover:text-zinc-900 text-zinc-600">Monitoring Boat</a>
				<a href="#contact" class="hover:text-zinc-900 text-zinc-600">Contact</a>
			</nav>
			<div class="flex items-center gap-2">
				<a href="/login" class="px-3 py-1.5 rounded-full text-sm border border-zinc-300 hover:bg-zinc-50">Log in</a>
				<a href="/login" class="px-4 py-1.5 rounded-full text-sm bg-zinc-900 text-white hover:bg-zinc-800">Get Started</a>
			</div>
		</div>
	</header>

	<main>
		<section class="relative">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 pt-14 pb-16 grid lg:grid-cols-2 gap-10 items-center">
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
						<button class="px-5 py-2 rounded-full bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Request Demo</button>
						<button class="px-5 py-2 rounded-full border border-zinc-300 hover:bg-zinc-50 text-sm">Learn More</button>
					</div>
					<div class="flex items-center gap-6 pt-2 text-sm text-zinc-600">
						<div><span class="font-semibold text-zinc-900">295.4K+</span> data points daily</div>
						<div class="hidden sm:block h-4 w-px bg-zinc-300"></div>
						<div class="flex items-center gap-2">
							<span class="font-semibold text-zinc-900">728M+</span> healthy shrimps
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
					<div class="aspect-[4/3] w-full rounded-2xl overflow-hidden bg-zinc-200 p-4 sm:p-8">
						<img src="/image/shrimp.png" alt="Shrimp illustration" class="w-full h-full object-contain object-center max-h-[560px] mx-auto">
					</div>
				</div>
			</div>
		</section>

		<section id="features" class="py-10 sm:py-16">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-center">
				<div>
					<h2 class="text-3xl sm:text-4xl font-semibold leading-tight">
						Digitilize Shrimp Farmer <span class="text-zinc-500 font-normal">Through Innovation</span>
					</h2>
					<p class="text-zinc-600 mt-4">Platform terintegrasi yang membantu peternak memantau kualitas air, mengelola tim, hingga mengambil keputusan berbasis data.</p>
				</div>
				<div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
					<img src="https://images.unsplash.com/photo-1504593811423-6dd665756598?auto=format&fit=crop&w=1400&q=80" alt="Aquaculture technician" class="w-full h-full object-cover">
				</div>
			</div>

			<div class="mx-auto max-w-7xl px-4 sm:px-6 mt-10">
				<div class="rounded-2xl border border-zinc-200 p-6 sm:p-8 bg-white/60">
					<div class="grid sm:grid-cols-3 gap-8">
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-900"></div>
							<h3 class="font-semibold">IoT Sensor Ready</h3>
							<p class="text-sm text-zinc-600">Capture pond metrics with sensors and automate routine checks to save time.</p>
						</div>
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-700"></div>
							<h3 class="font-semibold">Insightful Alerts</h3>
							<p class="text-sm text-zinc-600">Notif real-time membantu Anda bereaksi cepat sebelum kualitas air memburuk.</p>
						</div>
						<div class="space-y-2">
							<div class="h-10 w-10 rounded-lg bg-zinc-500"></div>
							<h3 class="font-semibold">Benchmarking Data</h3>
							<p class="text-sm text-zinc-600">Dataset besar memungkinkan perbandingan performa lintas tambak dan musim.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="py-10 sm:py-16">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-start">
				<div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-xl">
					<img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1200&q=80" alt="Shrimp farmer checking pond" class="w-full h-full object-cover">
				</div>
				<div class="space-y-6">
					<h2 class="text-3xl sm:text-4xl font-semibold">Transforming Farmers <span class="text-zinc-500 font-normal">into Automation</span></h2>
					<p class="text-zinc-600">Automate daily monitoring, centralize records, and use predictive insights to plan better feeding and aeration schedules.</p>
					<div class="flex flex-wrap gap-3">
						<button class="px-4 py-2 rounded-full border border-zinc-300 hover:bg-zinc-50 text-sm">Turning ideas into impactful</button>
						<button class="px-4 py-2 rounded-full border border-zinc-300 hover:bg-zinc-50 text-sm">Future of Aquaculture</button>
						<button class="px-4 py-2 rounded-full border border-zinc-300 hover:bg-zinc-50 text-sm">Your Brand Today</button>
					</div>
					<div class="grid sm:grid-cols-2 gap-6 pt-2">
						<div class="rounded-xl border border-zinc-200 p-4">
							<div class="h-8 w-8 rounded bg-zinc-900 mb-2"></div>
							<div class="font-semibold">Streamlined SOP</div>
							<p class="text-sm text-zinc-600">Scale operations confidently with automated monitoring workflows.</p>
						</div>
						<div class="rounded-xl border border-zinc-200 p-4">
							<div class="h-8 w-8 rounded bg-zinc-600 mb-2"></div>
							<div class="font-semibold">Beautiful UX</div>
							<p class="text-sm text-zinc-600">Dashboard intuitif untuk tim kualitas air, teknisi, dan manajemen.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="boat" class="py-10 sm:py-16 bg-zinc-50">
			<div class="mx-auto max-w-7xl px-4 sm:px-6">
				<h2 class="text-2xl sm:text-3xl font-semibold mb-6 text-center md:text-left">IoT‑Based Monitoring Boat</h2>
				<div class="grid lg:grid-cols-2 gap-8">
					<div class="aspect-[16/10] rounded-2xl overflow-hidden shadow-lg">
						<img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1400&q=80" alt="Autonomous monitoring boat" class="w-full h-full object-cover">
					</div>
					<div class="space-y-4">
						<div class="rounded-xl border border-zinc-200 p-5 bg-white">
							<h3 class="font-semibold mb-2">E‑SHRIMP Monitoring Robot</h3>
							<ul class="text-sm text-zinc-600 space-y-1 list-disc pl-5">
								<li>Real‑time DO, pH, temperature, salinity</li>
								<li>GPS routes, auto‑patrol, docking</li>
								<li>LoRa/Wi‑Fi connectivity</li>
								<li>Water sampling module</li>
							</ul>
						</div>
						<div class="grid sm:grid-cols-3 gap-4">
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">4h</div>
								<div class="text-xs text-zinc-600">Battery life</div>
							</div>
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">±0.1</div>
								<div class="text-xs text-zinc-600">pH accuracy</div>
							</div>
							<div class="rounded-xl border border-zinc-200 p-4 text-center bg-white">
								<div class="text-2xl font-semibold">IP67</div>
								<div class="text-xs text-zinc-600">Ruggedized</div>
							</div>
						</div>
					</div>
				</div>

				<div class="grid lg:grid-cols-2 gap-8 mt-8">
					<div class="rounded-2xl border border-zinc-200 p-5 bg-white">
						<h3 class="font-semibold mb-3">Sensor Suite</h3>
						<div class="grid sm:grid-cols-2 gap-4 text-sm text-zinc-600">
							<ul class="list-disc pl-5 space-y-1">
								<li>Dissolved Oxygen</li>
								<li>pH & Temperature</li>
								<li>Salinity & Turbidity</li>
							</ul>
							<ul class="list-disc pl-5 space-y-1">
								<li>GPS & IMU</li>
								<li>Water Level</li>
								<li>Battery & System Health</li>
							</ul>
						</div>
					</div>
					<div class="rounded-2xl border border-zinc-200 p-5 bg-white">
						<h3 class="font-semibold mb-3">Integrated Design (ESP32)</h3>
						<div class="grid sm:grid-cols-2 gap-4 text-sm text-zinc-600">
							<ul class="list-disc pl-5 space-y-1">
								<li>Telemetry via MQTT</li>
								<li>Local buffering</li>
								<li>OTA firmware updates</li>
							</ul>
							<ul class="list-disc pl-5 space-y-1">
								<li>Failsafe return‑to‑dock</li>
								<li>Modular sensor bay</li>
								<li>Open API</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="py-12 sm:py-16 bg-zinc-50">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 grid lg:grid-cols-2 gap-10 items-center">
				<div>
					<h3 class="text-2xl sm:text-3xl font-semibold">Order Now <span class="text-zinc-500 font-normal">Your E‑shrimp System</span></h3>
					<p class="text-zinc-600 mt-3">Bring automation and data‑driven decisions to your farm.</p>
					<div class="mt-6">
						<button class="px-5 py-2 rounded-full bg-zinc-900 text-white hover:bg-zinc-800 text-sm">Contact Sales</button>
					</div>
				</div>
				<div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
					<img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80" alt="Happy farmer" class="w-full h-full object-cover">
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


