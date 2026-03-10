<!-- Footer SEO -->
<footer class="bg-slate-950 py-16 border-t border-white/5 text-sm text-slate-400">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
        <div>
            <a href="/" class="text-2xl font-black text-white block mb-6">
                BIG-<span class="text-pink-500">DAD</span>
            </a>
            <p class="leading-relaxed mb-6">
                La plataforma líder de Sugar Dating en Latinoamérica. Conectando ambición con éxito desde 2024.
            </p>
            <div class="flex gap-4">
                <a href="https://www.instagram.com/big_dad.app/" target="_blank" rel="noopener"
                    class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all"
                    aria-label="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <a href="mailto:hola@big-dad.com"
                    class="text-gray-400 hover:text-pink-500 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    hola@big-dad.com
                </a>
            </div>
        </div>

        <div>
            <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Descubrir</h4>
            <ul class="space-y-3">
                <li><a href="#" class="hover:text-pink-500 transition-colors">Sugar Babies Premium</a></li>
                <li><a href="#" class="hover:text-pink-500 transition-colors">Sugar Daddies Verificados</a></li>
                <li><a href="#" class="hover:text-pink-500 transition-colors">Elite Dating Internacional</a></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-pink-500 transition-colors">Blog de Estilo de Vida</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Legal</h4>
            <ul class="space-y-3">
                <li><a href="{{ route('legal.terms') }}" class="hover:text-pink-500 transition-colors">Términos y Condiciones</a></li>
                <li><a href="{{ route('legal.privacy') }}" class="hover:text-pink-500 transition-colors">Política de Privacidad</a></li>
                <li><a href="{{ route('legal.rules') }}" class="hover:text-pink-500 transition-colors">Reglas de la Comunidad</a></li>
                <li><a href="{{ route('legal.safety') }}" class="hover:text-pink-500 transition-colors">Seguridad</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Ayuda</h4>
            <ul class="space-y-3">
                <li><a href="#" class="hover:text-pink-500 transition-colors">Soporte 24/7</a></li>
                <li><a href="#" class="hover:text-pink-500 transition-colors">Preguntas Frecuentes</a></li>
            </ul>

            <h4 class="text-white font-bold mt-8 mb-6 uppercase tracking-wider">Sitios Amigos</h4>
            <ul class="space-y-3">
                <li>
                    <a href="https://tecnopatitas.com" target="_blank" rel="noopener"
                        class="hover:text-pink-500 transition-colors flex items-center gap-2">
                        tecnopatitas.com
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="https://bandoleras.cl" target="_blank" rel="noopener"
                        class="hover:text-pink-500 transition-colors flex items-center gap-2">
                        bandoleras.cl
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container mx-auto px-6 mt-16 pt-8 border-t border-white/5 text-center text-xs">
        <p>&copy; {{ date('Y') }} Big-dad Latinoamérica. Todos los derechos reservados. Hecho con ❤️ para toda LATAM.</p>
    </div>
</footer>
