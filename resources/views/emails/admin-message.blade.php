<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subjectLine }}</title>
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
            padding: 40px 40px 32px;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .logo-text {
            font-size: 32px;
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
            margin-top: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ec4899;
        }

        /* ── Body ── */
        .body {
            padding: 40px 40px 32px;
        }

        .greeting {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .greeting strong {
            color: #f1f5f9;
        }

        .message-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-left: 4px solid #ec4899;
            border-radius: 16px;
            padding: 24px;
            margin: 20px 0 32px;
            font-size: 15px;
            color: #e2e8f0;
            line-height: 1.8;
            white-space: pre-line;
        }

        /* ── CTA Button ── */
        .cta-wrapper {
            text-align: center;
            margin: 32px 0 20px;
        }

        .cta-button {
            display: inline-block;
            padding: 16px 36px;
            background: linear-gradient(135deg, #ec4899, #a855f7);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 100px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            box-shadow: 0 8px 32px rgba(236,72,153,0.35);
        }

        /* ── Footer ── */
        .footer {
            background-color: #0a0f1e;
            padding: 28px 40px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-logo {
            font-size: 16px;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .footer-text {
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            line-height: 1.8;
        }

        .footer-text a {
            color: #ec4899;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .header, .body, .footer { padding-left: 20px !important; padding-right: 20px !important; }
            .cta-button { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>

    <div class="email-wrapper">
        <div class="email-container">

            {{-- ── HEADER ── --}}
            <div class="header">
                <div class="logo-text">Big-Dad<span class="logo-dot"></span></div>
                <p class="tagline">Mensaje de Moderación</p>
            </div>

            {{-- ── BODY ── --}}
            <div class="body">
                <p class="greeting">
                    Hola, <strong>{{ $userName }}</strong>:
                </p>

                <div class="message-box">
                    {!! nl2br(e($messageBody)) !!}
                </div>

                @if(!empty($actionUrl))
                    <div class="cta-wrapper">
                        <a href="{{ $actionUrl }}" class="cta-button" target="_blank">
                            ✨ {{ $actionText ?? 'Ir a Big-Dad' }}
                        </a>
                    </div>
                @endif

                <p style="font-size: 13px; color: #64748b; margin-top: 24px; text-align: center;">
                    Si tienes dudas o consultas sobre este mensaje, puedes responder directamente a este correo.
                </p>
            </div>

            {{-- ── FOOTER ── --}}
            <div class="footer">
                <div class="footer-logo">Big-Dad</div>
                <p class="footer-text">
                    © {{ date('Y') }} Big-Dad. Citas exclusivas y Lifestyle en Latinoamérica.<br>
                    Este es un mensaje administrativo enviado por el equipo de moderación.<br>
                    <a href="{{ url('/') }}">big-dad.com</a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>
