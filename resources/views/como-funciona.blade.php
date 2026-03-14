@extends('layouts.public-static')

@section('meta_title', 'Cómo Funciona | Big-dad, Sugar Dating exclusivo en Latinoamérica')
@section('meta_description',
    'Descubre cómo funciona Big-dad: crea tu perfil, encuentra conexiones compatibles y vive una experiencia de Sugar Dating más clara, privada y segura en Latinoamérica.')
@section('meta_keywords',
    'como funciona big-dad, sugar dating latinoamerica, sugar daddy, sugar baby, relaciones exclusivas, plataforma sugar dating')
@section('canonical_url', route('como-funciona'))

@section('og_title', 'Cómo Funciona | Big-dad')
@section('og_description',
    'Conoce el proceso simple, discreto y seguro para usar Big-dad: registro, exploración, match mutuo y conexiones auténticas.')
@section('og_url', route('como-funciona'))
@section('og_image', asset('images/og-image.jpg'))

@section('twitter_title', 'Cómo Funciona | Big-dad')
@section('twitter_description',
    'Así funciona Big-dad: una experiencia clara, privada y cuidada para Sugar Dating en Latinoamérica.')
@section('twitter_image', asset('images/og-image.jpg'))

@section('head_meta')
    <style>
        h1, h2, h3, h4 {
            font-family: 'Montserrat', sans-serif;
        }

        .gradient-text-pink {
            background: linear-gradient(135deg, #f472b6, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-text-amber {
            background: linear-gradient(135deg, #fbbf24, #f97316, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-glow:hover {
            box-shadow: 0 0 40px rgba(244, 114, 182, 0.15);
        }

        .faq-item summary {
            list-style: none;
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-item[open] .faq-icon {
            transform: rotate(45deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }
    </style>
@endsection

@section('structured_data')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@@type": "ListItem",
                    "position": 1,
                    "name": "Inicio",
                    "item": "{{ url('/') }}"
                },
                {
                    "@@type": "ListItem",
                    "position": 2,
                    "name": "Cómo Funciona",
                    "item": "{{ route('como-funciona') }}"
                }
            ]
        }
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebPage",
            "@@id": "{{ route('como-funciona') }}",
            "url": "{{ route('como-funciona') }}",
            "name": "Cómo Funciona | Big-dad",
            "description": "Guía paso a paso para usar Big-dad, la plataforma de Sugar Dating enfocada en privacidad, seguridad y conexiones auténticas en Latinoamérica.",
            "inLanguage": "es-CL",
            "breadcrumb": {
                "@@type": "BreadcrumbList",
                "itemListElement": [
                    { "@@type": "ListItem", "position": 1, "name": "Inicio", "item": "{{ url('/') }}" },
                    { "@@type": "ListItem", "position": 2, "name": "Cómo Funciona", "item": "{{ route('como-funciona') }}" }
                ]
            }
        }
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "HowTo",
            "name": "Cómo usar Big-dad",
            "description": "Paso a paso para crear tu perfil, encontrar compatibilidades y conversar dentro de Big-dad.",
            "totalTime": "PT5M",
            "step": [
                {
                    "@@type": "HowToStep",
                    "position": 1,
                    "name": "Crea tu perfil",
                    "text": "Completa tu perfil con fotos reales, información clara y expectativas auténticas."
                },
                {
                    "@@type": "HowToStep",
                    "position": 2,
                    "name": "Explora perfiles",
                    "text": "Descubre personas compatibles según estilo de vida, ubicación y objetivos."
                },
                {
                    "@@type": "HowToStep",
                    "position": 3,
                    "name": "Haz match",
                    "text": "Cuando el interés es mutuo, se habilita el chat privado dentro de la plataforma."
                },
                {
                    "@@type": "HowToStep",
                    "position": 4,
                    "name": "Conecta con claridad",
                    "text": "Habla con respeto, define expectativas y avanza sólo si hay afinidad real."
                }
            ]
        }
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                {
                    "@@type": "Question",
                    "name": "¿Es gratis registrarse en Big-dad?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Sí. Puedes crear tu perfil y comenzar a explorar la plataforma sin costo."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "¿Qué es un Sugar Daddy?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Un Sugar Daddy suele ser una persona madura, exitosa y financieramente estable que busca relaciones transparentes y mutuamente beneficiosas."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "¿Es seguro usar Big-dad?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Big-dad prioriza la moderación de perfiles, el match mutuo para habilitar chats y herramientas de privacidad para la comunidad."
                    }
                }
            ]
        }
    </script>
@endsection

@section('content')
    <section class="relative overflow-hidden border-b border-white/5 bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(236,72,153,0.15),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(168,85,247,0.12),_transparent_30%)]"></div>

        <div class="relative mx-auto max-w-7xl px-6 pb-20 pt-8 md:pb-24 md:pt-14">
            <nav aria-label="Breadcrumb" class="mb-8">
                <ol class="flex items-center gap-2 text-sm text-slate-500">
                    <li><a href="{{ route('welcome') }}" class="transition-colors hover:text-pink-400">Inicio</a></li>
                    <li class="text-slate-700">/</li>
                    <li class="font-medium text-slate-300">Cómo Funciona</li>
                </ol>
            </nav>

            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <span class="inline-flex rounded-full border border-pink-500/30 bg-pink-500/10 px-5 py-2 text-xs font-bold uppercase tracking-[0.3em] text-pink-300">
                        Proceso simple · privado · sin ruido
                    </span>

                    <h1 class="mt-6 max-w-4xl text-5xl font-black leading-tight md:text-7xl">
                        Así funciona
                        <span class="gradient-text-pink">Big-dad</span>
                        para conexiones más claras y exclusivas.
                    </h1>

                    <p class="mt-8 max-w-3xl text-xl leading-relaxed text-slate-300 md:text-2xl">
                        Diseñamos una experiencia pública coherente con el resto del sitio: menos artificio, más claridad.
                        En Big-dad todo gira en torno a perfiles moderados, privacidad, match mutuo y conversaciones con expectativas transparentes.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-8 py-4 text-base font-bold text-white shadow-2xl shadow-pink-500/30 transition-all hover:scale-105 hover:shadow-pink-500/40">
                            Crear cuenta gratis
                        </a>
                        <a href="{{ route('plans.public') }}"
                            class="inline-flex items-center gap-2 rounded-full border border-white/15 px-8 py-4 text-base font-bold text-white transition-all hover:bg-white/5">
                            Ver planes
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap gap-6 text-sm text-slate-400">
                        <span class="flex items-center gap-2"><span class="text-green-400">✓</span> Moderación manual</span>
                        <span class="flex items-center gap-2"><span class="text-green-400">✓</span> Match mutuo para chatear</span>
                        <span class="flex items-center gap-2"><span class="text-green-400">✓</span> Sólo mayores de 18 años</span>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-8 shadow-2xl shadow-fuchsia-950/20 backdrop-blur">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-pink-500/20 bg-gradient-to-br from-pink-500/10 to-transparent p-6">
                            <div class="mb-4 text-3xl">📝</div>
                            <h2 class="text-xl font-bold text-white">Perfil real</h2>
                            <p class="mt-3 text-sm leading-relaxed text-slate-400">Fotos y propuesta revisadas antes de ser públicas.</p>
                        </div>
                        <div class="rounded-3xl border border-violet-500/20 bg-gradient-to-br from-violet-500/10 to-transparent p-6">
                            <div class="mb-4 text-3xl">🔍</div>
                            <h2 class="text-xl font-bold text-white">Búsqueda enfocada</h2>
                            <p class="mt-3 text-sm leading-relaxed text-slate-400">Explora perfiles alineados con tu estilo de vida y tus objetivos.</p>
                        </div>
                        <div class="rounded-3xl border border-indigo-500/20 bg-gradient-to-br from-indigo-500/10 to-transparent p-6">
                            <div class="mb-4 text-3xl">💬</div>
                            <h2 class="text-xl font-bold text-white">Chat con match</h2>
                            <p class="mt-3 text-sm leading-relaxed text-slate-400">La conversación se abre sólo cuando el interés es mutuo.</p>
                        </div>
                        <div class="rounded-3xl border border-amber-500/20 bg-gradient-to-br from-amber-500/10 to-transparent p-6">
                            <div class="mb-4 text-3xl">🥂</div>
                            <h2 class="text-xl font-bold text-white">Experiencia premium</h2>
                            <p class="mt-3 text-sm leading-relaxed text-slate-400">Relaciones adultas con claridad, respeto y mejores expectativas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-900/40 py-20" aria-labelledby="steps-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-14 max-w-3xl text-center">
                <h2 id="steps-heading" class="text-4xl font-black text-white md:text-5xl">
                    Cuatro pasos para <span class="gradient-text-pink">empezar bien</span>
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-400">
                    La lógica es simple: crear un perfil honesto, descubrir compatibilidades, conectar sólo cuando haya interés mutuo y avanzar con expectativas claras.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <article id="paso-1" class="card-glow rounded-3xl border border-slate-800 bg-slate-950/80 p-8">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-300">Paso 01</span>
                    <h3 class="mt-5 text-2xl font-bold text-white">Crea tu perfil</h3>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                        Regístrate, sube tus fotos, completa tu información y define con claridad qué estás buscando dentro de la plataforma.
                    </p>
                </article>

                <article id="paso-2" class="card-glow rounded-3xl border border-slate-800 bg-slate-950/80 p-8">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-violet-300">Paso 02</span>
                    <h3 class="mt-5 text-2xl font-bold text-white">Explora perfiles</h3>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                        Usa filtros y señales de compatibilidad para descubrir perfiles alineados con tu estilo de vida, ciudad y nivel de privacidad.
                    </p>
                </article>

                <article id="paso-3" class="card-glow rounded-3xl border border-slate-800 bg-slate-950/80 p-8">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-indigo-300">Paso 03</span>
                    <h3 class="mt-5 text-2xl font-bold text-white">Haz match</h3>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                        El chat se habilita sólo con match mutuo. Eso reduce el ruido y mejora la calidad de las conversaciones desde el primer contacto.
                    </p>
                </article>

                <article id="paso-4" class="card-glow rounded-3xl border border-slate-800 bg-slate-950/80 p-8">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-amber-300">Paso 04</span>
                    <h3 class="mt-5 text-2xl font-bold text-white">Conecta con claridad</h3>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                        Si la afinidad es real, avanzas a una conversación más seria sobre intereses, límites, estilo de vida y expectativas compartidas.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-24" aria-labelledby="roles-heading">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <h2 id="roles-heading" class="text-4xl font-black text-white md:text-5xl">
                    Una plataforma, <span class="gradient-text-pink">dos recorridos</span>
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-400">
                    Big-dad ordena la experiencia según el tipo de perfil. Así cada persona ve señales, beneficios y dinámicas más coherentes con su rol.
                </p>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <article class="relative overflow-hidden rounded-[2rem] border border-amber-500/30 bg-gradient-to-br from-slate-900 to-slate-950 p-10">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent"></div>
                    <div class="relative">
                        <div class="mb-5 text-4xl">💼</div>
                        <h3 class="gradient-text-amber text-3xl font-black">Sugar Daddy</h3>
                        <p class="mt-5 leading-relaxed text-slate-300">
                            Un Sugar Daddy suele ser una persona madura, estable y financieramente sólida que valora conexiones transparentes, acuerdos claros y experiencias de alto nivel.
                        </p>
                        <ul class="mt-6 space-y-3 text-sm text-slate-300">
                            <li class="flex gap-3"><span class="text-amber-400">✦</span><span>Mayor claridad sobre estilo de vida y expectativas.</span></li>
                            <li class="flex gap-3"><span class="text-amber-400">✦</span><span>Perfiles moderados para mejorar confianza y calidad.</span></li>
                            <li class="flex gap-3"><span class="text-amber-400">✦</span><span>Herramientas para descubrir perfiles compatibles con más precisión.</span></li>
                        </ul>
                    </div>
                </article>

                <article class="relative overflow-hidden rounded-[2rem] border border-pink-500/30 bg-gradient-to-br from-slate-900 to-slate-950 p-10">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-transparent"></div>
                    <div class="relative">
                        <div class="mb-5 text-4xl">✨</div>
                        <h3 class="gradient-text-pink text-3xl font-black">Sugar Baby</h3>
                        <p class="mt-5 leading-relaxed text-slate-300">
                            Una Sugar Baby suele buscar conexiones con personas exitosas, mejor contexto de vida y relaciones adultas donde la seguridad, el respeto y la discreción sean prioritarios.
                        </p>
                        <ul class="mt-6 space-y-3 text-sm text-slate-300">
                            <li class="flex gap-3"><span class="text-pink-400">✦</span><span>Mayor control sobre visibilidad, límites y forma de interactuar.</span></li>
                            <li class="flex gap-3"><span class="text-pink-400">✦</span><span>Chats abiertos sólo con match mutuo para evitar ruido.</span></li>
                            <li class="flex gap-3"><span class="text-pink-400">✦</span><span>Comunidad enfocada en afinidad, no sólo exposición.</span></li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-slate-900/40 py-20" aria-labelledby="security-heading">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mx-auto mb-14 max-w-3xl text-center">
                <h2 id="security-heading" class="text-4xl font-black text-white md:text-5xl">
                    Seguridad y privacidad <span class="gradient-text-pink">desde el diseño</span>
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-400">
                    No es sólo un discurso visual. La experiencia está pensada para filtrar mejor, moderar contenido y reducir fricción innecesaria.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">🛡️</div>
                    <h3 class="text-xl font-bold text-white">Moderación real</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">Fotos de perfil y propuestas pasan por revisión antes de mostrarse públicamente.</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">🔒</div>
                    <h3 class="text-xl font-bold text-white">Privacidad cuidada</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">La plataforma prioriza el control sobre tu exposición y el manejo responsable de tus datos.</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">💬</div>
                    <h3 class="text-xl font-bold text-white">Chat con match</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">Las conversaciones comienzan mejor cuando ambas personas ya mostraron interés.</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">🚫</div>
                    <h3 class="text-xl font-bold text-white">Menos ruido</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">El diseño busca reducir interacciones vacías y favorecer conexiones más intencionales.</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">🔞</div>
                    <h3 class="text-xl font-bold text-white">Sólo adultos</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">El registro exige mayoría de edad y la plataforma está orientada exclusivamente a mayores de 18 años.</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-7">
                    <div class="mb-4 text-3xl">👁️</div>
                    <h3 class="text-xl font-bold text-white">Control de visibilidad</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400">Cada perfil puede construir una presencia más discreta y alineada con su contexto personal.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20" aria-labelledby="plans-teaser-heading">
        <div class="mx-auto max-w-5xl px-6 text-center">
            <h2 id="plans-teaser-heading" class="text-4xl font-black text-white md:text-5xl">
                Empieza gratis y <span class="gradient-text-pink">escala cuando quieras</span>
            </h2>
            <p class="mx-auto mt-4 max-w-3xl text-lg leading-relaxed text-slate-400">
                Puedes entrar sin costo y luego activar funciones premium para mejorar visibilidad, filtros y posibilidades de conexión.
            </p>

            <div class="mt-10 grid gap-5 text-left sm:grid-cols-3">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-500">Gratis</p>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li>✓ Perfil público</li>
                        <li>✓ Explorar perfiles</li>
                        <li>✓ Dar y recibir likes</li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-pink-500/40 bg-gradient-to-br from-pink-500/10 to-purple-500/10 p-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-pink-400">Premium</p>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li>✓ Mensajes ilimitados</li>
                        <li>✓ Filtros avanzados</li>
                        <li>✓ Más visibilidad</li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-amber-500/30 bg-slate-900/60 p-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-amber-400">Extras</p>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li>✓ Boosts</li>
                        <li>✓ Super likes</li>
                        <li>✓ Verificación express</li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('plans.public') }}"
                class="mt-10 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 px-10 py-4 text-base font-bold text-white shadow-2xl shadow-pink-500/30 transition-all hover:scale-105 hover:opacity-90">
                Ver todos los planes
            </a>
        </div>
    </section>

    <section class="bg-slate-900/40 py-24" aria-labelledby="faq-heading">
        <div class="mx-auto max-w-4xl px-6">
            <div class="mb-14 text-center">
                <h2 id="faq-heading" class="text-4xl font-black text-white md:text-5xl">
                    Preguntas <span class="gradient-text-pink">frecuentes</span>
                </h2>
                <p class="mt-4 text-lg text-slate-400">Lo esencial para entender cómo funciona la plataforma.</p>
            </div>

            <div class="space-y-4">
                <details class="faq-item group overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/60 transition-colors hover:border-pink-500/30">
                    <summary class="flex cursor-pointer items-center justify-between p-6 text-base font-bold text-white select-none">
                        <span>¿Es gratis registrarse en Big-dad?</span>
                        <span class="faq-icon ml-4 flex-shrink-0 text-2xl font-light text-pink-400">+</span>
                    </summary>
                    <div class="px-6 pb-6 text-sm leading-relaxed text-slate-400">
                        Sí. El acceso inicial es gratuito y luego puedes elegir funciones premium si quieres ampliar tus herramientas dentro de la plataforma.
                    </div>
                </details>

                <details class="faq-item group overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/60 transition-colors hover:border-pink-500/30">
                    <summary class="flex cursor-pointer items-center justify-between p-6 text-base font-bold text-white select-none">
                        <span>¿Qué es un Sugar Daddy?</span>
                        <span class="faq-icon ml-4 flex-shrink-0 text-2xl font-light text-pink-400">+</span>
                    </summary>
                    <div class="px-6 pb-6 text-sm leading-relaxed text-slate-400">
                        Un Sugar Daddy suele ser una persona madura, exitosa y financieramente estable que busca una relación transparente, adulta y mutuamente beneficiosa.
                    </div>
                </details>

                <details class="faq-item group overflow-hidden rounded-2xl border border-slate-700/60 bg-slate-900/60 transition-colors hover:border-pink-500/30">
                    <summary class="flex cursor-pointer items-center justify-between p-6 text-base font-bold text-white select-none">
                        <span>¿Es seguro usar Big-dad?</span>
                        <span class="faq-icon ml-4 flex-shrink-0 text-2xl font-light text-pink-400">+</span>
                    </summary>
                    <div class="px-6 pb-6 text-sm leading-relaxed text-slate-400">
                        La plataforma prioriza revisión de contenido, control de visibilidad y chat habilitado sólo con match mutuo para mejorar seguridad y contexto.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-slate-950 py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(168,85,247,0.14),_transparent_42%)]"></div>
        <div class="relative mx-auto max-w-4xl px-6 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.35em] text-pink-300">¿Listo para empezar?</p>
            <h2 class="mt-5 text-5xl font-black leading-tight text-white md:text-6xl">
                Tu siguiente conexión
                <span class="gradient-text-pink">empieza hoy</span>
            </h2>
            <p class="mx-auto mt-6 max-w-2xl text-xl leading-relaxed text-slate-400">
                Si buscas una experiencia más cuidada, privada y alineada con el resto de la marca, este es el punto de entrada correcto.
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-10 py-5 text-lg font-bold text-white shadow-2xl shadow-pink-500/30 transition-all hover:scale-105 hover:opacity-90">
                    Crear mi perfil gratis
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-white/15 px-10 py-5 text-lg font-bold text-white transition-all hover:bg-white/5">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>
@endsection
