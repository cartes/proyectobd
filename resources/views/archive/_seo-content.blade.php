{{-- Contenido SEO fijo del país (config/seo_countries.php). Se renderiza siempre, haya o no perfiles. --}}
@php
    $roleLabel = $type === 'sugar_daddy' ? 'Sugar Daddy' : 'Sugar Babies';
@endphp

<div class="px-6 md:px-10 py-12 max-w-4xl mx-auto text-white/80 leading-relaxed space-y-12">
    @if (!empty($seo['intro']))
        <section class="space-y-4 text-lg">
            @foreach ($seo['intro'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </section>
    @else
        <section class="text-lg">
            <p>
                Descubre el sugar dating en {{ $country->name }} con BigDad: perfiles moderados, chat solo con match
                mutuo y total discreción para Sugar Daddies y Sugar Babies mayores de 18 años.
            </p>
        </section>
    @endif

    @if (!empty($seo['cities_text']) || !empty($seo['cities']))
        <section>
            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-4">
                Ciudades principales para {{ $roleLabel }} en {{ $country->name }}
            </h2>
            @foreach ($seo['cities_text'] as $paragraph)
                <p class="mb-4">{{ $paragraph }}</p>
            @endforeach
            @if (!empty($seo['cities']))
                <ul class="flex flex-wrap gap-3 mt-6">
                    @foreach ($seo['cities'] as $cityName)
                        <li class="px-4 py-2 glass-card rounded-full text-white/80 text-xs font-bold border border-white/10">{{ $cityName }}</li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endif

    @if (!empty($seo['how_it_works']))
        <section>
            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-4">
                Cómo funciona BigDad en {{ $country->name }}
            </h2>
            @foreach ($seo['how_it_works'] as $paragraph)
                <p class="mb-4">{{ $paragraph }}</p>
            @endforeach
        </section>
    @endif

    @if (!empty($seo['safety']))
        <section>
            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-4">Seguridad y discreción</h2>
            @foreach ($seo['safety'] as $paragraph)
                <p class="mb-4">{{ $paragraph }}</p>
            @endforeach
        </section>
    @endif

    @if (!empty($seo['faqs']))
        <section>
            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-6">
                Preguntas frecuentes sobre {{ $roleLabel }} en {{ $country->name }}
            </h2>
            <div class="space-y-4">
                @foreach ($seo['faqs'] as $faq)
                    <div class="glass-card rounded-2xl p-6 border border-white/10">
                        <h3 class="text-lg font-black text-white mb-2">{{ $faq['question'] }}</h3>
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
