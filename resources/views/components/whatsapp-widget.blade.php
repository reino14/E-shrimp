@php
	$whatsappNumber = $whatsappNumber ?? '6285694014009';
	$encodedMessage = urlencode('Halo Admin E-shrimp, saya butuh bantuan untuk monitoring tambak.');
@endphp

<div data-whatsapp-widget class="fixed bottom-4 right-4 z-50 flex flex-col items-end gap-3">
	<div data-whatsapp-card class="hidden w-72 sm:w-80 rounded-2xl border border-emerald-100 bg-white shadow-2xl p-4 text-sm text-zinc-700">
		<div class="flex items-center justify-between mb-2">
			<div>
				<p class="text-xs uppercase tracking-wide text-emerald-500 font-semibold">Customer Service</p>
				<p class="text-base font-semibold text-zinc-900">Tim E-shrimp</p>
			</div>
			<button data-whatsapp-close class="text-zinc-400 hover:text-zinc-600 transition" aria-label="Tutup popup customer service">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
		</div>
		<p class="text-xs text-zinc-500 mb-3">Kami siap membantu kebutuhan monitoring tambak udang Anda setiap saat.</p>
		<ul class="text-xs text-zinc-600 space-y-1 mb-4">
			<li>• Konsultasi sensor & threshold</li>
			<li>• Bantuan masalah login & dashboard</li>
			<li>• Dukungan teknis lapangan</li>
		</ul>
		<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $encodedMessage }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 text-white py-2 text-sm font-medium hover:bg-emerald-400 transition">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 32 32">
				<path fill="currentColor" d="M16 2.9A13.1 13.1 0 0 0 4.6 23.1l-1.5 5.6l5.7-1.5A13.1 13.1 0 1 0 16 2.9Zm7.6 18.7a3.6 3.6 0 0 1-2.6 1.7c-.7 0-1.6.3-5.3-1.1c-4.4-1.8-7.3-6.3-7.5-6.6s-1.8-2.4-1.8-4.6s1.1-3.4 1.6-3.8a1.7 1.7 0 0 1 1.2-.5h.9c.3 0 .7-.1 1 .8s1.3 3.4 1.4 3.7s.2.6 0 1c-.3.6-.6.8-.8 1s-.4.4-.2.8a10.4 10.4 0 0 0 1.9 2.4a8.8 8.8 0 0 0 3.1 2.1c.4.1.7.1 1-.1s1.2-1 1.6-1.4s.7-.3 1.2-.1s3.1 1.5 3.7 1.7s.9.4 1 .6a3.2 3.2 0 0 1-.3 1.8Z"/>
			</svg>
			Hubungi via WhatsApp
		</a>
	</div>
	<button type="button" data-whatsapp-toggle class="h-14 w-14 rounded-full bg-emerald-500 text-white shadow-2xl flex items-center justify-center hover:bg-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition" aria-label="Buka customer service WhatsApp">
		<svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 32 32" fill="currentColor">
			<path d="M16 2.9A13.1 13.1 0 0 0 4.6 23.1l-1.5 5.6l5.7-1.5A13.1 13.1 0 1 0 16 2.9Z"/>
			<path d="M23.6 21.6a3.6 3.6 0 0 1-2.6 1.7c-.7 0-1.6.3-5.3-1.1c-4.4-1.8-7.3-6.3-7.5-6.6s-1.8-2.4-1.8-4.6s1.1-3.4 1.6-3.8a1.7 1.7 0 0 1 1.2-.5h.9c.3 0 .7-.1 1 .8s1.3 3.4 1.4 3.7s.2.6 0 1c-.3.6-.6.8-.8 1s-.4.4-.2.8a10.4 10.4 0 0 0 1.9 2.4a8.8 8.8 0 0 0 3.1 2.1c.4.1.7.1 1-.1s1.2-1 1.6-1.4s.7-.3 1.2-.1s3.1 1.5 3.7 1.7s.9.4 1 .6a3.2 3.2 0 0 1-.3 1.8Z" fill="#fff"/>
		</svg>
	</button>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const widget = document.querySelector('[data-whatsapp-widget]');
		if (!widget) return;

		const card = widget.querySelector('[data-whatsapp-card]');
		const toggleButton = widget.querySelector('[data-whatsapp-toggle]');
		const closeButton = widget.querySelector('[data-whatsapp-close]');

		const closeCard = () => card && card.classList.add('hidden');
		const toggleCard = () => card && card.classList.toggle('hidden');

		toggleButton && toggleButton.addEventListener('click', (event) => {
			event.stopPropagation();
			toggleCard();
		});

		closeButton && closeButton.addEventListener('click', (event) => {
			event.stopPropagation();
			closeCard();
		});

		document.addEventListener('click', (event) => {
			if (!widget.contains(event.target) && card && !card.classList.contains('hidden')) {
				closeCard();
			}
		});
	});
</script>

