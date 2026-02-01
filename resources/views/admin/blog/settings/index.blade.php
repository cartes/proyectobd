@extends('layouts.admin')

@section('title', 'Configuración - Blog')

@section('content')
    <form action="{{ route('admin.blog.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-outfit font-bold">Configuración del Blog</h2>
                    <p class="text-gray-400 mt-1">Configura SEO, Analytics y opciones generales</p>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors">
                    Guardar Cambios
                </button>
            </div>

            {{-- General Settings --}}
            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Configuración General
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Posts por Página</label>
                        <input type="number" name="posts_per_page"
                            value="{{ old('posts_per_page', $settings['posts_per_page'] ?? 12) }}" min="1"
                            max="50"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                        <p class="text-xs text-gray-500 mt-1">Número de posts a mostrar por página en el blog público.</p>
                    </div>
                </div>
            </div>

            {{-- SEO Settings --}}
            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    SEO
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Template de Meta Título</label>
                        <input type="text" name="meta_title_template"
                            value="{{ old('meta_title_template', $settings['meta_title_template'] ?? '{title} - {site_name}') }}"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="{title} - {site_name}">
                        <p class="text-xs text-gray-500 mt-1">Variables disponibles: {title}, {site_name}, {category}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Template de Meta Descripción</label>
                        <textarea name="meta_description_template" rows="2"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="{excerpt}">{{ old('meta_description_template', $settings['meta_description_template'] ?? '{excerpt}') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Variables disponibles: {excerpt}, {title}, {category}</p>
                    </div>
                </div>
            </div>

            {{-- Analytics Settings --}}
            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id"
                            value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="G-XXXXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">ID de Google Analytics 4 (GA4)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Google Tag Manager ID</label>
                        <input type="text" name="google_tag_manager_id"
                            value="{{ old('google_tag_manager_id', $settings['google_tag_manager_id'] ?? '') }}"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="GTM-XXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">ID de Google Tag Manager</p>
                    </div>
                </div>
            </div>

            {{-- Custom Scripts --}}
            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    Scripts Personalizados
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Scripts en el Header</label>
                        <textarea name="header_scripts" rows="6"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500 font-mono text-sm"
                            placeholder="<script>
                                // Tu código aquí
                            </script>">{{ old('header_scripts', $settings['header_scripts'] ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Se insertará en el &lt;head&gt; del blog. Útil para
                            verificación de dominio, pixels de tracking, etc.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Scripts en el Footer</label>
                        <textarea name="footer_scripts" rows="6"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500 font-mono text-sm"
                            placeholder="<script>
                                // Tu código aquí
                            </script>">{{ old('footer_scripts', $settings['footer_scripts'] ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Se insertará antes del cierre del &lt;/body&gt;. Útil para
                            scripts de chat, analytics adicionales, etc.</p>
                    </div>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-blue-500 font-semibold mb-2">Información Importante</h4>
                        <ul class="text-sm text-gray-400 space-y-1">
                            <li>• Los cambios en SEO y Analytics se aplicarán inmediatamente en el blog público.</li>
                            <li>• Los scripts personalizados se ejecutarán en todas las páginas del blog.</li>
                            <li>• Asegúrate de probar los scripts antes de guardarlos para evitar errores.</li>
                            <li>• El sitemap XML se genera automáticamente en <code
                                    class="bg-white/5 px-2 py-0.5 rounded">/sitemap-blog.xml</code></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Save Button (Bottom) --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-8 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Configuración
                </button>
            </div>
        </div>
    </form>
@endsection
