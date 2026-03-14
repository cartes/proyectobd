@extends('layouts.public-static')

@section('meta_title', 'Quiénes Somos | Big-dad, comunidad de Sugar Dating en Latinoamérica')
@section('meta_description',
    'Conoce quiénes somos en Big-dad: una plataforma de Sugar Dating pensada para conexiones claras, privadas y exclusivas en Latinoamérica.')
@section('meta_keywords',
    'quienes somos big-dad, sugar dating latinoamerica, plataforma sugar daddy, comunidad sugar baby, citas exclusivas latam, relaciones mutuamente beneficiosas')
@section('canonical_url', route('about.index'))

@section('og_title', 'Quiénes Somos | Big-dad')
@section('og_description',
    'Descubre la visión, valores y propuesta de Big-dad, la comunidad de Sugar Dating enfocada en privacidad, seguridad y experiencias reales en Latinoamérica.')
@section('og_url', route('about.index'))
@section('og_image', asset('images/quienes-somos/experiencia-sugar-dating.jpg'))

@section('twitter_title', 'Quiénes Somos | Big-dad')
@section('twitter_description',
    'Nuestra historia, valores y visión sobre el Sugar Dating moderno en Latinoamérica. Conoce por qué Big-dad apuesta por conexiones auténticas y seguras.')
@section('twitter_image', asset('images/quienes-somos/experiencia-sugar-dating.jpg'))

@section('head_meta')
    <meta name="twitter:image:alt" content="experiencia en sugar dating">
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
@endsection

@section('content')
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

    <section class="bg-slate-900 py-20 text-white">
        <div class="mx-auto max-w-5xl px-6">
            <div class="grid gap-10 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-slate-800/80 p-8 shadow-sm shadow-slate-950/30">
                    <h2 class="text-xl font-extrabold text-white">Nuestra visión</h2>
                    <p class="mt-4 leading-8 text-slate-300">
                        Queremos profesionalizar la experiencia del Sugar Dating en la región, con una plataforma que
                        premie la transparencia, el consentimiento y la afinidad real por encima del caos habitual de
                        las apps masivas.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-800/80 p-8 shadow-sm shadow-slate-950/30">
                    <h2 class="text-xl font-extrabold text-white">Nuestra promesa</h2>
                    <p class="mt-4 leading-8 text-slate-300">
                        Diseñamos un entorno donde la discreción, la calidad de los perfiles y la intención clara
                        importan. No buscamos cantidad, sino una comunidad más cuidada y alineada con un lifestyle
                        premium.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-800/80 p-8 shadow-sm shadow-slate-950/30">
                    <h2 class="text-xl font-extrabold text-white">Nuestro enfoque</h2>
                    <p class="mt-4 leading-8 text-slate-300">
                        Entendemos que cada vínculo es distinto. Por eso priorizamos herramientas que facilitan
                        descubrir perfiles compatibles, conversar con mayor seguridad y generar encuentros con más
                        contexto y menos fricción.
                    </p>
                </div>
            </div>

            <div class="mt-16 space-y-8 text-lg leading-9 text-slate-300">
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

    <section class="bg-slate-950 py-20">
        <div class="mx-auto max-w-5xl px-6">
            <div class="max-w-3xl">
                <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-300">Cómo entendemos la comunidad</span>
                <h2 class="mt-4 text-3xl font-black text-white md:text-4xl">Qué hacemos diferente en Big-dad</h2>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2">
                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-2xl font-bold text-white">Privacidad con criterio</h3>
                    <p class="mt-4 text-lg leading-8 text-slate-300">
                        Sabemos que la discreción es una parte esencial del Sugar Dating. Por eso cuidamos la
                        presentación de los perfiles y promovemos un entorno donde la confianza se construye desde el
                        primer contacto. La privacidad no es un extra; es parte del producto y de la cultura de la
                        comunidad.
                    </p>
                </article>

                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-2xl font-bold text-white">Relaciones con expectativas claras</h3>
                    <p class="mt-4 text-lg leading-8 text-slate-300">
                        Favorecemos conexiones en las que ambas partes puedan expresar qué valoran, qué esperan y cómo
                        imaginan una relación mutuamente beneficiosa. Esa claridad reduce fricciones y mejora la calidad
                        de las conversaciones desde el inicio.
                    </p>
                </article>

                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-2xl font-bold text-white">Curaduría y moderación</h3>
                    <p class="mt-4 text-lg leading-8 text-slate-300">
                        No nos interesa ser una plataforma improvisada. Apostamos por contenido mejor cuidado, perfiles
                        más presentables y una experiencia visual sólida porque eso impacta directamente en la confianza,
                        la seguridad y la percepción de valor del sitio.
                    </p>
                </article>

                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-2xl font-bold text-white">Educación y contexto</h3>
                    <p class="mt-4 text-lg leading-8 text-slate-300">
                        Nos importa que los usuarios comprendan mejor el universo Sugar. Por eso impulsamos contenido de
                        apoyo y recursos informativos en <a href="{{ route('blog.index') }}" class="font-semibold text-pink-400 hover:text-pink-300">nuestro blog</a>,
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

    <section class="bg-slate-900 py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-8 text-lg leading-9 text-slate-300">
                <div>
                    <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-300">Seguridad y experiencia</span>
                    <h2 class="mt-4 text-3xl font-black text-white md:text-4xl">Una plataforma pensada para durar</h2>
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
                    <a href="{{ route('blog.index') }}" class="font-semibold text-pink-400 hover:text-pink-300">el blog de Big-dad</a>.
                    Allí reunimos contenido pensado para responder dudas frecuentes, inspirar conversaciones de calidad y
                    ofrecer contexto antes de dar el siguiente paso dentro de la plataforma.
                </p>
            </div>

            <aside class="rounded-[2rem] border border-white/10 bg-slate-950 p-8 text-white shadow-xl shadow-slate-950/30">
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

    <section class="bg-slate-950 py-20">
        <div class="mx-auto max-w-5xl px-6">
            <div class="max-w-3xl">
                <span class="text-sm font-bold uppercase tracking-[0.3em] text-pink-300">Preguntas frecuentes</span>
                <h2 class="mt-4 text-3xl font-black text-white md:text-4xl">Lo que muchas personas quieren saber</h2>
            </div>

            <div class="mt-12 grid gap-6">
                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-xl font-bold text-white">¿Big-dad es solo para personas con experiencia previa?</h3>
                    <p class="mt-3 text-lg leading-8 text-slate-300">
                        No. También es un punto de entrada para quienes buscan informarse mejor antes de participar. Por
                        eso combinamos la experiencia social con contenido editorial que ayude a entender el contexto y
                        tomar decisiones con mayor seguridad.
                    </p>
                </article>

                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-xl font-bold text-white">¿Qué tipo de relación promueve la plataforma?</h3>
                    <p class="mt-3 text-lg leading-8 text-slate-300">
                        Promovemos relaciones entre adultos con objetivos claros, afinidad y beneficios mutuos. Cada
                        vínculo es distinto, pero la base siempre debe ser el respeto, el consentimiento y la
                        transparencia.
                    </p>
                </article>

                <article class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 shadow-sm shadow-slate-950/30">
                    <h3 class="text-xl font-bold text-white">¿Por qué una página de “Quiénes somos” es importante?</h3>
                    <p class="mt-3 text-lg leading-8 text-slate-300">
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
@endsection
