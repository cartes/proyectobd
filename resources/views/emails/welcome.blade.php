<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bienvenido a Big-Dad</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #020617;
            color: #e2e8f0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .email-wrapper {
            width: 100%;
            background-color: #020617;
            padding: 40px 16px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #0f172a;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            padding: 48px 40px 40px;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .header::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%; transform: translateX(-50%);
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(236,72,153,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .logo-text {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #f59e0b, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            position: relative;
        }

        .logo-dot {
            display: inline-block;
            width: 8px; height: 8px;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            border-radius: 50%;
            margin-left: 4px;
            vertical-align: middle;
            position: relative;
            top: -4px;
        }

        .tagline {
            margin-top: 10px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
        }

        /* ── Hero ── */
        .hero {
            padding: 48px 40px 36px;
            text-align: center;
        }

        .hero-emoji {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.25;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .hero-title span {
            background: linear-gradient(135deg, #ec4899, #a855f7, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Body ── */
        .body {
            padding: 0 40px 40px;
        }

        .greeting {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: #f1f5f9;
        }

        .intro-text {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.8;
            margin-bottom: 16px;
        }

        /* ── Quote pill ── */
        .quote-pill {
            background: linear-gradient(135deg, rgba(236,72,153,0.12), rgba(168,85,247,0.12));
            border: 1px solid rgba(236,72,153,0.25);
            border-radius: 12px;
            padding: 16px 20px;
            margin: 24px 0;
            text-align: center;
        }

        .quote-pill p {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            font-style: italic;
        }

        .quote-pill span {
            background: linear-gradient(135deg, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── CTA Button ── */
        .cta-wrapper {
            text-align: center;
            margin: 36px 0;
        }

        .cta-button {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 100px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 32px rgba(236,72,153,0.35);
        }

        .cta-subtext {
            margin-top: 12px;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.08), transparent);
            margin: 32px 0;
        }

        /* ── Steps section ── */
        .steps-title {
            font-size: 16px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 20px;
            text-align: center;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }

        .step-icon {
            flex-shrink: 0;
            width: 40px; height: 40px;
            background: linear-gradient(135deg, rgba(236,72,153,0.15), rgba(168,85,247,0.15));
            border: 1px solid rgba(236,72,153,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            text-align: center;
            line-height: 40px;
        }

        .step-content {}

        .step-title {
            font-size: 14px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        /* ── Closing quote ── */
        .closing-quote {
            text-align: center;
            margin: 32px 0 0;
        }

        .closing-quote p {
            font-size: 15px;
            font-style: italic;
            color: #94a3b8;
        }

        .closing-quote .highlight {
            background: linear-gradient(135deg, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* ── Footer ── */
        .footer {
            background-color: #0a0f1e;
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-logo {
            font-size: 18px;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .footer-text {
            font-size: 12px;
            color: rgba(255,255,255,0.2);
            line-height: 1.8;
        }

        .footer-text a {
            color: rgba(236,72,153,0.6);
            text-decoration: none;
        }

        .footer-link-url {
            font-size: 11px;
            color: rgba(255,255,255,0.15);
            word-break: break-all;
            margin-top: 16px;
        }

        /* ── Preheader (hidden) ── */
        .preheader {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            color: transparent;
            height: 0;
            width: 0;
            max-height: 0;
            overflow: hidden;
            mso-hide: all;
        }

        @media only screen and (max-width: 600px) {
            .header, .body, .hero { padding-left: 24px !important; padding-right: 24px !important; }
            .footer { padding-left: 24px !important; padding-right: 24px !important; }
            .hero-title { font-size: 22px !important; }
            .cta-button { padding: 16px 28px !important; font-size: 15px !important; }
            .logo-text { font-size: 28px !important; }
        }
    </style>
</head>
<body>

    {{-- Preheader text (visible in inbox preview, hidden in email body) --}}
    <span class="preheader">Tu nuevo estilo de vida comienza aquí. Solo falta un paso...&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌</span>

    <div class="email-wrapper">
        <div class="email-container">

            {{-- ── HEADER ── --}}
            <div class="header">
                <div class="logo-text">Big-Dad<span class="logo-dot"></span></div>
                <p class="tagline">Lifestyle &amp; Citas Exclusivas</p>
            </div>

            {{-- ── HERO ── --}}
            <div class="hero">
                <span class="hero-emoji">🥂</span>
                <h1 class="hero-title">La Buena Vida es<br><span>Mejor Compartida.</span></h1>
            </div>

            {{-- ── BODY ── --}}
            <div class="body">

                <p class="greeting">
                    Hola, <strong>{{ $userName }}</strong>:
                </p>

                <p class="intro-text">
                    Bienvenido a <strong style="color:#f1f5f9;">Big-Dad</strong>, la comunidad <strong style="color:#f1f5f9;">#1 de Lifestyle y citas exclusivas en Latinoamérica</strong>.
                </p>

                <p class="intro-text">
                    Has dado el primer paso para dejar atrás los juegos y las pérdidas de tiempo. Como bien sabes, en nuestra plataforma nos regimos por una filosofía muy simple:
                </p>

                <div class="quote-pill">
                    <p><span>"Cero Drama, Todo Clarity"</span></p>
                </div>

                <p class="intro-text">
                    Aquí las relaciones son directas, transparentes y de alto nivel. Para mantener nuestra regla de <strong style="color:#f1f5f9;">"Calidad sobre Cantidad"</strong> y asegurar que todos los miembros de nuestra comunidad sean reales, necesitamos que confirmes tu dirección de correo electrónico.
                </p>

                {{-- ── CTA BUTTON ── --}}
                <div class="cta-wrapper">
                    <a href="{{ $verificationUrl }}" class="cta-button" target="_blank">
                        ✨ &nbsp; Verificar mi correo y comenzar
                    </a>
                    <p class="cta-subtext">Este enlace expira en 60 minutos</p>
                </div>

                <div class="divider"></div>

                {{-- ── NEXT STEPS ── --}}
                <p class="steps-title">¿Qué sigue después de verificar tu cuenta?</p>

                <div class="step-item">
                    <div class="step-icon">👑</div>
                    <div class="step-content">
                        <p class="step-title">Crea tu Perfil VIP</p>
                        <p class="step-desc">Define qué buscas con claridad: mentoría, viajes, compañía para eventos. La claridad atrae calidad.</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon">📸</div>
                    <div class="step-content">
                        <p class="step-title">Sube tus mejores fotos</p>
                        <p class="step-desc">Recuerda que tienes <strong style="color:#f1f5f9;">Privacidad Garantizada</strong>. Tú controlas quién ve tu información y puedes mantener tus fotos ocultas si así lo deseas.</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon">🔍</div>
                    <div class="step-content">
                        <p class="step-title">Descubre a la élite</p>
                        <p class="step-desc">Usa nuestros filtros premium para conectar con personas exitosas en tu ciudad o en todo el continente.</p>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="closing-quote">
                    <p><span class="highlight">"La vida es demasiado corta para citas aburridas"</span>.<br>Desbloquea tu nuevo estilo de vida hoy mismo.</p>
                    <p style="margin-top:16px; font-size:14px; color:#64748b;">Atentamente,<br><strong style="color:#94a3b8;">El equipo de Big-Dad</strong></p>
                </div>

            </div>

            {{-- ── FOOTER ── --}}
            <div class="footer">
                <div class="footer-logo">Big-Dad</div>
                <p class="footer-text">
                    © {{ date('Y') }} Big-Dad. Todos los derechos reservados.<br>
                    La comunidad #1 de Lifestyle y citas exclusivas en Latinoamérica.<br><br>
                    Si no creaste esta cuenta, puedes ignorar este correo de forma segura.<br>
                    <a href="{{ url('/') }}">big-dad.com</a>
                </p>
                <p class="footer-link-url">
                    Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                    {{ $verificationUrl }}
                </p>
            </div>

        </div>
    </div>

</body>
</html>
