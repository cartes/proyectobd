@extends('layouts.app')

@section('page-title', 'Mis Fotos')

@section('content')
    <div x-data="photoGallery()" x-init="init()">

        {{-- Mensajes --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="mb-6 bg-gradient-to-r {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-500/90 to-purple-600/90' : 'from-pink-500/90 to-pink-600/90' }} backdrop-blur-lg rounded-2xl p-4 text-white shadow-xl border border-white/20">
                <p class="flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-2 border-red-300 rounded-2xl p-4 shadow-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <p class="font-bold text-red-800 mb-1">Error</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-700 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-black bg-gradient-to-r {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-600 to-indigo-800' : 'from-pink-600 to-fuchsia-800' }} bg-clip-text text-transparent mb-3 uppercase tracking-tighter"
                style="font-family: 'Outfit', sans-serif;">
                📸 Mis Fotos
            </h1>
            <p class="text-gray-600 text-lg">
                Sube hasta {{ \App\Models\ProfilePhoto::MAX_PHOTOS }} fotos para destacar tu perfil
                <span class="text-sm">({{ Auth::user()->remainingPhotosCount() }} disponibles)</span>
            </p>
        </div>

        {{-- Upload Area --}}
        @if (Auth::user()->canUploadMorePhotos())
            <div
                class="bg-white rounded-3xl shadow-lg p-8 mb-6 border-2 border-dashed {{ Auth::user()->user_type === 'sugar_daddy' ? 'border-purple-300' : 'border-pink-300' }}">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-500 to-purple-700' : 'from-pink-500 to-pink-700' }} flex items-center justify-center text-white text-2xl shadow-lg">
                        ⬆️
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Subir Nueva
                            Foto</h2>
                        <p class="text-sm text-gray-500">JPG, PNG o WebP • Máximo 5MB</p>
                    </div>
                </div>

                <form action="{{ route('profile.photos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Drop Zone --}}
                    <div class="relative">
                        <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/webp" class="hidden"
                            @change="previewImage($event)">
                        <input type="hidden" name="potential_nudity" x-model="potentialNudity">

                        <label for="photoInput" class="block cursor-pointer">
                            <div class="border-2 border-dashed {{ Auth::user()->user_type === 'sugar_daddy' ? 'border-purple-300 hover:border-purple-400 bg-purple-50' : 'border-pink-300 hover:border-pink-400 bg-pink-50' }} rounded-2xl p-12 text-center transition-all duration-300 hover:scale-[1.02]"
                                :class="{ 'border-solid {{ Auth::user()->user_type === 'sugar_daddy' ? 'border-purple-500 bg-purple-100' : 'border-pink-500 bg-pink-100' }}': previewUrl }">

                                {{-- Preview --}}
                                <div x-show="previewUrl" class="mb-4">
                                    <img :src="previewUrl" alt="Preview" class="max-h-64 mx-auto rounded-2xl shadow-lg">
                                </div>

                                {{-- Upload Icon --}}
                                <div x-show="!previewUrl">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-lg font-semibold text-gray-700 mb-2">
                                        Click para seleccionar o arrastra una foto aquí
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Formatos: JPG, PNG, WebP • Máximo 5MB
                                    </p>
                                </div>

                                <p x-show="previewUrl && !isAnalyzing" class="text-sm text-gray-600 mt-4">
                                    Click para cambiar la foto
                                </p>

                                {{-- Analyzing State --}}
                                <div x-show="isAnalyzing"
                                    class="mt-4 flex items-center justify-center gap-2 text-amber-600 font-bold animate-pulse">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Analizando imagen...
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-4 mt-6" x-show="previewUrl">
                        <button type="button" @click="clearPreview()"
                            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 border-2 border-gray-300 rounded-xl text-gray-700 font-bold transition-all duration-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isAnalyzing" :class="{'opacity-50 cursor-not-allowed': isAnalyzing}"
                            class="px-8 py-3 bg-gradient-to-r {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800' : 'from-pink-500 to-pink-700 hover:from-pink-600 hover:to-pink-800' }} rounded-xl text-white font-bold transition-all duration-300 shadow-lg hover:scale-105">
                            📤 Subir Foto
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div
                class="bg-gradient-to-r {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-100 to-indigo-100' : 'from-pink-100 to-rose-100' }} rounded-3xl p-8 mb-6 border-2 {{ Auth::user()->user_type === 'sugar_daddy' ? 'border-purple-300' : 'border-pink-300' }}">
                <div class="text-center">
                    <p class="text-2xl mb-2">📸</p>
                    <p class="text-lg font-bold text-gray-800 mb-2">Has alcanzado el límite de fotos</p>
                    <p class="text-gray-600">Tienes {{ Auth::user()->photos()->count() }} fotos subidas (máximo
                        {{ \App\Models\ProfilePhoto::MAX_PHOTOS }})
                    </p>
                    <p class="text-sm text-gray-500 mt-2">Elimina alguna foto para subir una nueva</p>
                </div>
            </div>
        @endif

        {{-- Galería de Fotos --}}
        @if (Auth::user()->photos()->count() > 0)
            <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-2xl shadow-lg">
                            🖼️
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Mi Galería
                            </h2>
                            <p class="text-sm text-gray-500">{{ Auth::user()->photos()->count() }} fotos • Arrastra para
                                reordenar</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-ref="gallery" @dragover.prevent
                    @drop.prevent="handleDrop($event)">

                    @foreach (Auth::user()->photos as $photo)
                        <div class="relative group cursor-move" draggable="true" data-photo-id="{{ $photo->id }}"
                            @dragstart="dragStart($event)" @dragend="dragEnd($event)" @dragover.prevent="dragOver($event)"
                            @dragleave="dragLeave($event)">

                            {{-- Imagen --}}
                            <div
                                class="aspect-square rounded-2xl overflow-hidden bg-gray-100 shadow-lg group-hover:shadow-2xl transition-shadow duration-300 relative">
                                <img src="{{ $photo->url }}" alt="Foto de perfil"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                                {{-- Overlay --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{-- Acciones --}}
                                    <div class="absolute bottom-0 left-0 right-0 p-3 flex items-center justify-between">
                                        {{-- Foto Principal --}}
                                        @if ($photo->is_primary)
                                            <span
                                                class="px-3 py-1 bg-amber-500 rounded-full text-white text-xs font-bold flex items-center gap-1 shadow-lg">
                                                ⭐ Principal
                                            </span>
                                        @else
                                            <form action="{{ route('profile.photos.setPrimary', $photo) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-white/90 hover:bg-amber-500 rounded-full text-gray-800 hover:text-white text-xs font-bold transition-colors shadow-lg">
                                                    ⭐ Hacer Principal
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Eliminar --}}
                                        <form action="{{ route('profile.photos.destroy', $photo) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar esta foto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full text-white flex items-center justify-center transition-colors shadow-lg">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Badge principal (siempre visible) --}}
                                @if ($photo->is_primary)
                                    <div
                                        class="absolute top-2 right-2 w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-xl shadow-lg">
                                        ⭐
                                    </div>
                                @endif

                                {{-- Indicador de orden --}}
                                <div
                                    class="absolute top-2 left-2 w-8 h-8 bg-black/60 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ $loop->iteration }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-lg p-12 border border-gray-100 text-center">
                <div class="text-6xl mb-4">📸</div>
                <h3 class="text-2xl font-black text-gray-900 mb-2" style="font-family: 'Outfit', sans-serif;">
                    Aún no tienes fotos
                </h3>
                <p class="text-gray-600 mb-6">
                    Sube tus primeras fotos para que otros usuarios te conozcan
                </p>
            </div>
        @endif

    </div>

    @push('scripts')
        <!-- NSFWJS CDN (Includes its own TF dependency) -->
        <script src="https://cdn.jsdelivr.net/npm/nsfwjs@latest/dist/browser/nsfwjs.min.js"></script>
        <script>
            function photoGallery() {
                return {
                    previewUrl: null,
                    draggedElement: null,
                    model: null,
                    isAnalyzing: false,
                    potentialNudity: 0,

                    async init() {
                        console.log('Photo gallery initialized');

                        const checkNSFW = async () => {
                            if (window.nsfwjs) {
                                try {
                                    this.model = await window.nsfwjs.load();
                                    console.log('NSFW Model loaded');
                                } catch (e) {
                                    console.error('Error loading NSFW model:', e);
                                }
                            } else {
                                setTimeout(checkNSFW, 200);
                            }
                        };

                        checkNSFW();
                    },

                    async previewImage(event) {
                        const file = event.target.files[0];
                        if (file) {
                            // Reset flags
                            this.potentialNudity = 0;
                            this.isAnalyzing = true;

                            const reader = new FileReader();
                            reader.onload = async (e) => {
                                this.previewUrl = e.target.result;

                                // Analizar la imagen
                                if (this.model) {
                                    const img = new Image();
                                    img.src = e.target.result;
                                    img.onload = async () => {
                                        const predictions = await this.model.classify(img);
                                        console.log('NSFW Predictions:', predictions);

                                        // Las categorías son: Porn, Sexy, Hentai, Neutral, Drawing
                                        // Sumamos las categorías de riesgo
                                        const risky = predictions.filter(p =>
                                            (['Porn', 'Sexy', 'Hentai'].includes(p.className)) && p.probability > 0.4
                                        );

                                        if (risky.length > 0) {
                                            console.warn('Potential nudity detected!');
                                            this.potentialNudity = 1;
                                        }
                                        this.isAnalyzing = false;
                                    };
                                } else {
                                    this.isAnalyzing = false;
                                }
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    clearPreview() {
                        this.previewUrl = null;
                        this.potentialNudity = 0;
                        this.isAnalyzing = false;
                        document.getElementById('photoInput').value = '';
                    },

                    // Drag and Drop para reordenar
                    dragStart(event) {
                        this.draggedElement = event.target;
                        event.target.style.opacity = '0.5';
                    },

                    dragEnd(event) {
                        event.target.style.opacity = '1';
                        this.saveOrder();
                    },

                    dragOver(event) {
                        const target = event.target.closest('[data-photo-id]');
                        if (target && target !== this.draggedElement) {
                            const gallery = this.$refs.gallery;
                            const allItems = Array.from(gallery.querySelectorAll('[data-photo-id]'));
                            const draggedIndex = allItems.indexOf(this.draggedElement);
                            const targetIndex = allItems.indexOf(target);

                            if (draggedIndex < targetIndex) {
                                target.parentNode.insertBefore(this.draggedElement, target.nextSibling);
                            } else {
                                target.parentNode.insertBefore(this.draggedElement, target);
                            }
                        }
                    },

                    dragLeave(event) {
                        // Opcional: agregar efectos visuales
                    },

                    handleDrop(event) {
                        event.preventDefault();
                    },

                    async saveOrder() {
                        const gallery = this.$refs.gallery;
                        const items = gallery.querySelectorAll('[data-photo-id]');
                        const order = Array.from(items).map(item => item.dataset.photoId);

                        try {
                            const response = await fetch('{{ route('profile.photos.reorder') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    order
                                })
                            });

                            if (response.ok) {
                                console.log('Order saved successfully');
                            }
                        } catch (error) {
                            console.error('Error saving order:', error);
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection