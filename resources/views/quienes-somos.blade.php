<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quiénes Somos | Big-dad, comunidad de Sugar Dating en Latinoamérica</title>
    <meta name="description"
        content="Conoce quiénes somos en Big-dad: una plataforma de Sugar Dating pensada para conexiones claras, privadas y exclusivas en Latinoamérica.">
    <meta name="keywords"
        content="quienes somos big-dad, sugar dating latinoamerica, plataforma sugar daddy, comunidad sugar baby, citas exclusivas latam, relaciones mutuamente beneficiosas">
    <meta name="author" content="Big-dad">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('about.index') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Quiénes Somos | Big-dad">
    <meta property="og:description"
        content="Descubre la visión, valores y propuesta de Big-dad, la comunidad de Sugar Dating enfocada en privacidad, seguridad y experiencias reales en Latinoamérica.">
    <meta property="og:url" content="{{ route('about.index') }}">
    <meta property="og:image" content="{{ asset('images/quienes-somos/experiencia-sugar-dating.jpg') }}">
    <meta property="og:locale" content="es_CL">
    <meta property="og:site_name" content="Big-dad">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Quiénes Somos | Big-dad">
    <meta name="twitter:description"
        content="Nuestra historia, valores y visión sobre el Sugar Dating moderno en Latinoamérica. Conoce por qué Big-dad apuesta por conexiones auténticas y seguras.">
    <meta name="twitter:image" content="{{ asset('images/quienes-somos/experiencia-sugar-dating.jpg') }}">
    <meta name="twitter:image:alt" content="experiencia en sugar dating">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900|montserrat:300,400,500,600,700,800&display=swap"
        rel="stylesheet" />

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
                    "name": "Quiénes Somos",
                    "item": "{{ route('about.index') }}"
                }
            ]
        }
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "AboutPage",
            "@@id": "{{ route('about.index') }}",
            "url": "{{ route('about.index') }}",
            "name": "Quiénes Somos | Big-dad",
            "description": "Página sobre la visión, valores y enfoque de Big-dad como plataforma de Sugar Dating en Latinoamérica.",
            "inLanguage": "es-CL",
            "primaryImageOfPage": {
                "@@type": "ImageObject",
                "url": "{{ asset('images/quienes-somos/experiencia-sugar-dating.jpg') }}",
                "caption": "experiencia en sugar dating"
            },
            "about": {
                "@@type": "Organization",
                "name": "Big-dad",
                "url": "{{ url('/') }}"
            },
            "breadcrumb": {
                "@@type": "BreadcrumbList",
                "itemListElement": [
                    { "@@type": "ListItem", "position": 1, "name": "Inicio", "item": "{{ url('/') }}" },
                    { "@@type": "ListItem", "position": 2, "name": "Quiénes Somos", "item": "{{ route('about.index') }}" }
                ]
            }
        }
    </script>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                {
                    "@@type": "Question",
                    "name": "¿Qué es Big-dad?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Big-dad es una plataforma enfocada en Sugar Dating para adultos que buscan conexiones claras, privadas y mutuamente beneficiosas en Latinoamérica."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "¿Qué diferencia a Big-dad de otras plataformas?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Big-dad prioriza la privacidad, la moderación del contenido, la claridad en las expectativas y una experiencia pensada para perfiles que valoran discreción, estilo de vida y conexiones reales."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "¿Qué es un Sugar Daddy?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Un Sugar Daddy suele ser una persona madura, exitosa y financieramente estable que busca una relación transparente y mutuamente beneficiosa basada en compañía, afinidad y acuerdos claros."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "¿Dónde puedo aprender más sobre Sugar Dating?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Puedes visitar el blog de Big-dad para leer guías, consejos y artículos explicativos sobre Sugar Dating, seguridad, privacidad y estilo de vida."
                    }
                }
            ]
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-G035SGF3GT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-G035SGF3GT');
    </script>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <nav class="fixed top-0 left-0 z-50 w-full bg-slate-900/90 py-4 shadow-lg backdrop-blur-md transition-all duration-300">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6">
            <a href="{{ route('welcome') }}" class="text-2xl font-black tracking-tighter transition-transform hover:scale-105">
                BIG-<span class="text-pink-500">DAD</span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-300 md:flex">
                <a href="{{ route('plans.public') }}" class="transition-colors hover:text-pink-400">Planes</a>
                <a href="{{ route('blog.index') }}" class="transition-colors hover:text-pink-400">Blog</a>
                <a href="{{ route('como-funciona') }}" class="transition-colors hover:text-pink-400">Cómo Funciona</a>
                <a href="{{ route('about.index') }}" class="border-b-2 border-pink-500 text-white transition-colors hover:text-pink-400">Quiénes Somos</a>
                <a href="{{ route('welcome') }}#beneficios" class="transition-colors hover:text-pink-400">Beneficios</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-pink-500/30 transition-all hover:shadow-pink-500/50">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full border-2 border-pink-500 px-5 py-2.5 text-sm font-bold text-white transition-all hover:bg-pink-500/10">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-pink-500/30 transition-all hover:shadow-pink-500/50">
                        Únete Ahora
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="h-20"></div>

    <main>
        <section class="relative overflow-hidden bg-slate-950 text-white">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 via-purple-500/5 to-slate-950"></div>
            <div class="relative mx-auto grid max-w-7xl gap-12 px-6 py-20 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <span
                        class="inline-flex rounded-full border border-pink-500/30 bg-pink-500/10 px-4 py-1 text-xs font-bold uppercase tracking-[0.3em] text-pink-300">
                        Comunidad premium en Latinoamérica
                    </span>

                    <h1 class="mt-6 max-w-4xl text-4xl font-black leading-tight md:text-6xl">
                        Quiénes somos en <span class="text-pink-500">Big-dad</span> y por qué creemos en conexiones más claras,
                        privadas y auténticas.
                    </h1>

                    <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-300 md:text-xl">
                        Big-dad nació para ofrecer una experiencia de Sugar Dating mejor pensada: sin ruido, sin promesas
                        vacías y con una visión adulta de las relaciones exclusivas. Reunimos a personas que valoran la
                        honestidad, el estilo de vida, la ambición y la posibilidad de construir vínculos mutuamente
                        beneficiosos con respeto.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-pink-500/25 transition hover:scale-[1.02]">
                            Crear cuenta gratis
                        </a>
                        <a href="{{ route('blog.index') }}"
                            class="rounded-full border border-white/15 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white transition hover:border-pink-400 hover:text-pink-300">
                            Visitar nuestro blog
                        </a>
                    </div>
                </div>

                <figure class="overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 shadow-2xl shadow-slate-950/40">
                    <img src="{{ asset('images/quienes-somos/experiencia-sugar-dating.jpg') }}"
                        alt="experiencia en sugar dating"
                        class="h-full w-full object-cover"
                        width="1920"
                        height="1080"
                        fetchpriority="high">
                    <figcaption class="border-t border-white/10 px-6 py-4 text-sm leading-6 text-slate-300">
                        Una experiencia social premium, adulta y discreta para quienes buscan relaciones con expectativas
                        claras y una conexión genuina.
                    </figcaption>
                </figure>
            </div>
        </section>

        <section class="bg-white py-20 text-slate-800">
            <div class="mx-auto max-w-5xl px-6">
                <div class="grid gap-10 lg:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm">
                        <h2 class="text-xl font-extrabold text-slate-900">Nuestra visión</h2>
                        <p class="mt-4 leading-8 text-slate-600">
                            Queremos profesionalizar la experiencia del Sugar Dating en la región, con una plataforma que
                            premie la transparencia, el consentimiento y la afinidad real por encima del caos habitual de
                            las apps masivas.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm">
                        <h2 class="text-xl font-extrabold text-slate-900">Nuestra promesa</h2>
                        <p class="mt-4 leading-8 text-slate-600">
                            Diseñamos un entorno donde la discreción, la calidad de los perfiles y la intención clara
                            importan. No buscamos cantidad, sino una comunidad más cuidada y alineada con un lifestyle
                            premium.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm">
                        <h2 class="text-xl font-extrabold text-slate-900">Nuestro enfoque</h2>
                        <p class="mt-4 leading-8 text-slate-600">
                            Entendemos que cada vínculo es distinto. Por eso priorizamos herramientas que facilitan
                            descubrir perfiles compatibles, conversar con mayor seguridad y generar encuentros con más
                            contexto y menos fricción.
                        </p>
                    </div>
                </div>

                <div class="mt-16 space-y-8 text-lg leading-9 text-slate-700">
                    <p>
                        En Big-dad creemos que las relaciones modernas requieren plataformas modernas. Muchas personas no se
                        sienten representadas por aplicaciones generalistas donde todo ocurre demasiado rápido, con poca
                        información y casi ninguna compatibilidad real. Nosotros apostamos por una experiencia distinta:
                        una comunidad enfocada en el Sugar Dating, construida para adultos que desean conocer personas con
                        ambición, claridad y un gusto compartido por las experiencias bien vividas.
                    </p>

                    <p>
                        Nuestro proyecto parte de una idea simple: cuando las expectativas están claras, la experiencia es
                        mejor para todos. Por eso hablamos de relaciones transparentes, beneficios mutuos y respeto por los
                        tiempos, límites y objetivos de cada persona. Big-dad no intenta disfrazar el interés, sino ordenar
                        el encuentro entre personas que quieren conversar desde la honestidad, evitando malentendidos y
                        creando un espacio donde la afinidad, la admiración y la discreción puedan crecer con naturalidad.
                    </p>

                    <p>
                        También entendemos algo clave: el lujo hoy no siempre significa extravagancia, sino calidad. Calidad
                        en la conversación, en la selección de perfiles, en la privacidad de la plataforma y en la
                        experiencia general. Por eso trabajamos para que la navegación sea clara, el contenido esté
                        moderado y cada persona pueda construir una presencia digital más alineada con lo que realmente
                        busca. Para nosotros, la exclusividad no es una pose; es el resultado de cuidar mejor cada detalle.
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-slate-100 py-20">
            <div class="mx-auto max-w-5xl px-6">
                <div class="max-w-3xl">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-600">Cómo entendemos la comunidad</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-4xl">Qué hacemos diferente en Big-dad</h2>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-2">
                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-2xl font-bold text-slate-900">Privacidad con criterio</h3>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            Sabemos que la discreción es una parte esencial del Sugar Dating. Por eso cuidamos la
                            presentación de los perfiles y promovemos un entorno donde la confianza se construye desde el
                            primer contacto. La privacidad no es un extra; es parte del producto y de la cultura de la
                            comunidad.
                        </p>
                    </article>

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-2xl font-bold text-slate-900">Relaciones con expectativas claras</h3>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            Favorecemos conexiones en las que ambas partes puedan expresar qué valoran, qué esperan y cómo
                            imaginan una relación mutuamente beneficiosa. Esa claridad reduce fricciones y mejora la calidad
                            de las conversaciones desde el inicio.
                        </p>
                    </article>

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-2xl font-bold text-slate-900">Curaduría y moderación</h3>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            No nos interesa ser una plataforma improvisada. Apostamos por contenido mejor cuidado, perfiles
                            más presentables y una experiencia visual sólida porque eso impacta directamente en la confianza,
                            la seguridad y la percepción de valor del sitio.
                        </p>
                    </article>

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-2xl font-bold text-slate-900">Educación y contexto</h3>
                        <p class="mt-4 text-lg leading-8 text-slate-600">
                            Nos importa que los usuarios comprendan mejor el universo Sugar. Por eso impulsamos contenido de
                            apoyo y recursos informativos en <a href="{{ route('blog.index') }}" class="font-semibold text-pink-600 hover:text-pink-700">nuestro blog</a>,
                            donde compartimos guías, consejos y definiciones para tomar decisiones con mayor criterio.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-slate-950 py-20 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <div class="max-w-4xl">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-300">Glosario esencial</span>
                    <h2 class="mt-4 text-3xl font-black md:text-4xl">Qué es un Sugar Daddy y por qué importa explicarlo bien</h2>
                </div>

                <div class="mt-10 space-y-8 text-lg leading-9 text-slate-300">
                    <p>
                        Una de las preguntas más frecuentes dentro y fuera de nuestra comunidad es qué significa realmente
                        este tipo de relación. Cuando se habla con clichés o prejuicios, se pierde de vista que muchas
                        personas adultas buscan acuerdos emocionales, sociales y de estilo de vida mucho más claros que los
                        que ofrecen otras formas de dating. En términos generales, un Sugar Daddy suele ser una persona
                        madura, estable y exitosa que valora la compañía, la afinidad y la transparencia dentro de una
                        relación mutuamente beneficiosa.
                    </p>

                    <p>
                        Esa definición, sin embargo, debe tratarse con matices. No todas las relaciones son iguales, no
                        todas las motivaciones coinciden y no todos los vínculos giran únicamente alrededor del aspecto
                        económico. En muchos casos también entran en juego la mentoría, la admiración, la compatibilidad,
                        la apertura mental y el deseo de compartir experiencias de alto nivel con alguien que aporte energía,
                        presencia y conexión real. Si quieres profundizar más en el concepto, te recomendamos leer nuestra
                        guía completa sobre
                        <a href="https://big-dad.com/blog/que-es-un-sugar-daddy" class="font-semibold text-pink-400 hover:text-pink-300">
                            qué es un Sugar Daddy
                        </a>.
                    </p>

                    <p>
                        En Big-dad preferimos un lenguaje claro porque sabemos que una comunidad sana se construye desde la
                        información correcta. Cuando las personas entienden mejor el contexto, pueden entrar a la plataforma
                        con expectativas más realistas, una mejor comunicación y mayor capacidad para identificar lo que sí
                        desean y lo que no. Ese criterio es parte de nuestra identidad: no solo conectamos perfiles, también
                        ayudamos a crear conversaciones más conscientes.
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-white py-20">
            <div class="mx-auto grid max-w-6xl gap-12 px-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-8 text-lg leading-9 text-slate-700">
                    <div>
                        <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-600">Seguridad y experiencia</span>
                        <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-4xl">Una plataforma pensada para durar</h2>
                    </div>

                    <p>
                        Sabemos que una buena experiencia digital no depende solo del diseño. Depende de cómo se sienten las
                        personas al entrar, de si encuentran un entorno serio y de si perciben que hay un estándar detrás de
                        cada interacción. Por eso nos importa tanto la moderación del contenido, la estructura de los
                        perfiles y el modo en que se habilitan las conexiones. Queremos que cada paso tenga sentido y que la
                        experiencia completa transmita orden, criterio y confianza.
                    </p>

                    <p>
                        Nuestro objetivo es convertirnos en la referencia del Sugar Dating en Latinoamérica para quienes
                        desean algo más refinado que una app convencional. Eso implica escuchar a la comunidad, mejorar la
                        navegación, reforzar la claridad en los mensajes y publicar recursos útiles para acompañar a quienes
                        están conociendo este mundo por primera vez. Nos interesa crecer con una propuesta sólida, no con
                        atajos.
                    </p>

                    <p>
                        Si quieres conocer mejor nuestra mirada, explorar consejos prácticos y aprender más sobre privacidad,
                        estilo de vida, compatibilidad y encuentros seguros, te invitamos a recorrer
                        <a href="{{ route('blog.index') }}" class="font-semibold text-pink-600 hover:text-pink-700">el blog de Big-dad</a>.
                        Allí reunimos contenido pensado para responder dudas frecuentes, inspirar conversaciones de calidad y
                        ofrecer contexto antes de dar el siguiente paso dentro de la plataforma.
                    </p>
                </div>

                <aside class="rounded-[2rem] bg-slate-950 p-8 text-white shadow-xl shadow-slate-300/30">
                    <h3 class="text-2xl font-black">Nuestros pilares</h3>
                    <ul class="mt-6 space-y-5 text-base leading-7 text-slate-300">
                        <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="block font-bold text-white">Claridad</span>
                            Relaciones más honestas empiezan con expectativas mejor explicadas.
                        </li>
                        <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="block font-bold text-white">Discreción</span>
                            Cuidamos la experiencia para que la privacidad sea parte natural del recorrido.
                        </li>
                        <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="block font-bold text-white">Calidad</span>
                            Apostamos por una plataforma más cuidada, atractiva y confiable.
                        </li>
                        <li class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <span class="block font-bold text-white">Contexto</span>
                            Educamos a la comunidad con contenido útil, SEO sólido y recursos bien escritos.
                        </li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="bg-slate-100 py-20">
            <div class="mx-auto max-w-5xl px-6">
                <div class="max-w-3xl">
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-600">Preguntas frecuentes</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-4xl">Lo que muchas personas quieren saber</h2>
                </div>

                <div class="mt-12 grid gap-6">
                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-xl font-bold text-slate-900">¿Big-dad es solo para personas con experiencia previa?</h3>
                        <p class="mt-3 text-lg leading-8 text-slate-600">
                            No. También es un punto de entrada para quienes buscan informarse mejor antes de participar. Por
                            eso combinamos la experiencia social con contenido editorial que ayude a entender el contexto y
                            tomar decisiones con mayor seguridad.
                        </p>
                    </article>

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-xl font-bold text-slate-900">¿Qué tipo de relación promueve la plataforma?</h3>
                        <p class="mt-3 text-lg leading-8 text-slate-600">
                            Promovemos relaciones entre adultos con objetivos claros, afinidad y beneficios mutuos. Cada
                            vínculo es distinto, pero la base siempre debe ser el respeto, el consentimiento y la
                            transparencia.
                        </p>
                    </article>

                    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-xl font-bold text-slate-900">¿Por qué una página de “Quiénes somos” es importante?</h3>
                        <p class="mt-3 text-lg leading-8 text-slate-600">
                            Porque una marca confiable necesita explicar su propósito. Esta página existe para que entiendas
                            cómo pensamos, qué valores defendemos y qué tipo de experiencia queremos construir para la
                            comunidad.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-gradient-to-r from-pink-600 via-rose-600 to-purple-700 py-20 text-white">
            <div class="mx-auto max-w-5xl px-6 text-center">
                <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-100">Big-dad</span>
                <h2 class="mt-4 text-4xl font-black md:text-5xl">Una comunidad para conectar con más intención</h2>
                <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-pink-50">
                    Si compartes nuestra visión sobre claridad, privacidad y experiencias premium, te invitamos a explorar la
                    plataforma, leer el blog y descubrir cómo Big-dad está redefiniendo el Sugar Dating en Latinoamérica.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-white px-6 py-3 text-sm font-bold uppercase tracking-wider text-pink-600 transition hover:bg-pink-50">
                        Empezar ahora
                    </a>
                    <a href="{{ route('blog.index') }}"
                        class="rounded-full border border-white/40 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white transition hover:bg-white/10">
                        Leer artículos del blog
                    </a>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>

</html>
