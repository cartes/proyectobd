{{-- CTA de registro: se muestra en /sugar-daddies (perfiles siempre privados) o cuando no hay perfiles públicos --}}
<section class="px-6 md:px-10 pb-10 max-w-7xl mx-auto">
    <div class="glass-card rounded-[3rem] p-10 md:p-16 text-center border border-white/10 shadow-2xl">
        @if ($type === 'sugar_daddy')
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">Tu perfil, siempre privado</h2>
            <p class="text-white/70 font-bold mb-10 leading-relaxed max-w-2xl mx-auto">
                Los perfiles de Sugar Daddies en {{ $country->name }} nunca se muestran en páginas públicas.
                Crea tu cuenta gratis, explora Sugar Babies de tu ciudad y conversa solo cuando haya match mutuo.
            </p>
        @else
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">Sé de las primeras en {{ $country->name }}</h2>
            <p class="text-white/70 font-bold mb-10 leading-relaxed max-w-2xl mx-auto">
                La comunidad de BigDad en {{ $country->name }} está creciendo. Crea tu perfil gratis hoy y
                destaca desde el primer día frente a los Sugar Daddies de tu ciudad.
            </p>
        @endif
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register', ['role' => 'sugar_baby']) }}" class="theme-btn px-10">Soy Sugar Baby</a>
            <a href="{{ route('register', ['role' => 'sugar_daddy']) }}"
                class="px-10 py-4 rounded-2xl border border-white/20 text-white font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all">
                Soy Sugar Daddy
            </a>
        </div>
    </div>
</section>
