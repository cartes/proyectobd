@extends('layouts.mobile-app')

@php
    $isDaddy = $type === 'sugar_daddy';
    $routeName = \App\Models\Country::archiveRouteName($type);
    $otherType = $isDaddy ? 'sugar_baby' : 'sugar_daddy';
    $otherUrl = route(\App\Models\Country::archiveRouteName($otherType), $country->slug);
    $baseUrl = route($routeName, $country->slug);
    $currentPage = $users ? $users->currentPage() : 1;
    $canonicalUrl = $baseUrl . ($currentPage > 1 ? '?page=' . $currentPage : '');
    $hasProfiles = $users && $users->total() > 0;

    $pageTitle = $seo['meta_title'];
    $metaDescription = $seo['meta_description'];
    $ogImage = $hasProfiles && $users->first()->primaryPhoto ? $users->first()->primaryPhoto->url : asset('favicon.png');
    // /sugar-daddies sin contenido propio sería una página casi vacía: no se indexa (ni entra al sitemap).
    $metaRobots = $isDaddy && !$seo['has_content'] ? 'noindex, follow' : 'index, follow';

    $roleLabel = $isDaddy ? 'Sugar Daddy' : 'Sugar Babies';

    $graph = [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'BigDad', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => "{$roleLabel} en {$country->name}", 'item' => $baseUrl],
            ],
        ],
        [
            '@type' => 'CollectionPage',
            'name' => $pageTitle,
            'description' => $metaDescription,
            'url' => $canonicalUrl,
            'inLanguage' => 'es',
        ],
    ];

    if ($hasProfiles) {
        $graph[1]['mainEntity'] = [
            '@type' => 'ItemList',
            'name' => "Sugar Babies en {$country->name}",
            'itemListElement' => $users->values()->map(fn ($profile, $index) => [
                '@type' => 'ListItem',
                'position' => (($users->currentPage() - 1) * $users->perPage()) + $index + 1,
                'url' => url('/profile/' . $profile->id),
                'name' => "{$profile->name}, {$profile->age} años – " . ($profile->city ?? $country->name),
            ])->all(),
        ];
    }

    if (!empty($seo['faqs'])) {
        $graph[] = [
            '@type' => 'FAQPage',
            'mainEntity' => collect($seo['faqs'])->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ])->all(),
        ];
    }

    $schema = ['@context' => 'https://schema.org', '@graph' => $graph];
@endphp

@section('full_title', $pageTitle)
@section('page-title', $pageTitle)
@section('meta_robots', $metaRobots)
@section('meta_description', $metaDescription)
@section('canonical_url', $canonicalUrl)
@section('og_title', $pageTitle)
@section('og_description', $metaDescription)
@section('og_url', $canonicalUrl)
@section('og_image', $ogImage)
@section('og_type', 'website')

@push('schema_markup')
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) !!}</script>
@endpush

@section('content')
    <div class="min-h-screen" style="background: var(--theme-gradient-deep);">
        <div class="relative z-0">
            {{-- Header del Archivo --}}
            <div class="px-6 py-12 backdrop-blur-xl border-b border-white/10 shadow-2xl relative overflow-hidden"
                style="background: rgba(var(--primary-rgb, 219, 39, 119), 0.2);">

                {{-- Decoración de fondo --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -left-12 w-48 h-48 bg-fuchsia-500/10 rounded-full blur-2xl"></div>

                <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
                    {{-- Bandera Gigante y Redonda --}}
                    <div class="relative">
                        <div
                            class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-white/30 shadow-2xl ring-8 ring-white/10 animate-float">
                            <img src="https://flagcdn.com/w160/{{ strtolower($country->iso_code) }}.png"
                                alt="Bandera de {{ $country->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-lg border border-pink-100">
                            <span class="text-2xl">✨</span>
                        </div>
                    </div>

                    <div class="text-center md:text-left">
                        <h1
                            class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter drop-shadow-2xl leading-tight">
                            {{ $roleLabel }} en <br>
                            <span
                                class="text-pink-400 drop-shadow-[0_0_15px_rgba(244,114,182,0.4)]">{{ $country->name }}</span>
                        </h1>
                        <p
                            class="text-white/80 text-lg font-black mt-4 uppercase tracking-[0.2em] flex items-center justify-center md:justify-start gap-3">
                            <span class="w-8 h-[2px] bg-pink-500"></span>
                            @if ($hasProfiles)
                                {{ $users->total() }} Perfiles Públicos
                            @else
                                Sugar Dating en {{ $country->name }}
                            @endif
                            <span class="w-8 h-[2px] bg-pink-500"></span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Contenido SEO fijo (siempre visible, renderizado en servidor) --}}
            @include('archive._seo-content')

            {{-- Perfiles (solo Sugar Babies; los Sugar Daddies son siempre privados) o CTA --}}
            @if ($isDaddy)
                @include('archive._cta')
            @else
                @include('archive._profiles')
            @endif

            {{-- Enlace cruzado babies <-> daddies del mismo país --}}
            <nav aria-label="Más sobre {{ $country->name }}" class="px-6 md:px-10 pb-10 max-w-4xl mx-auto">
                <a href="{{ $otherUrl }}"
                    class="block glass-card rounded-2xl p-6 border border-white/10 hover:border-pink-500/50 transition-all text-center">
                    <span class="text-white/60 text-xs font-black uppercase tracking-widest">También te puede interesar</span>
                    <span class="block text-xl font-black text-white mt-2">
                        {{ $isDaddy ? 'Sugar Babies' : 'Sugar Daddy' }} en {{ $country->name }} →
                    </span>
                </a>
            </nav>

            {{-- Ciudades con perfiles públicos --}}
            @if ($countryCities->count() > 0)
                <div class="px-6 pb-16 max-w-7xl mx-auto">
                    <h2 class="text-white/60 text-xs font-black uppercase tracking-widest mb-6">Buscar por ciudad</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($countryCities as $cityItem)
                            <a href="{{ route('archive.city', [$country->slug, $cityItem->slug]) }}"
                                class="px-4 py-2 glass-card rounded-full text-white/70 text-xs font-bold border border-white/10 hover:border-pink-500/50 hover:text-white transition-all">
                                {{ $cityItem->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
