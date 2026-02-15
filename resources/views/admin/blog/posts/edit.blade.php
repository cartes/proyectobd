@extends('layouts.admin')

@section('title', 'Editar Post - Blog')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/compressorjs@1.2.1/dist/compressor.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    <form action="{{ route('admin.blog.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="postStatus" value="{{ $post->status }}">

        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-outfit font-bold">Editar Post</h2>
                        @if ($post->status === 'published')
                            <span
                                class="px-3 py-1 bg-green-500/10 text-green-500 text-sm font-semibold rounded-full border border-green-500/20">Publicado</span>
                        @elseif($post->status === 'draft')
                            <span
                                class="px-3 py-1 bg-yellow-500/10 text-yellow-500 text-sm font-semibold rounded-full border border-yellow-500/20">Borrador</span>
                        @else
                            <span
                                class="px-3 py-1 bg-blue-500/10 text-blue-500 text-sm font-semibold rounded-full border border-blue-500/20">Programado</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-gray-400">
                        <p>{{ $post->title }}</p>
                        <span class="text-gray-600">•</span>
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                            class="text-pink-500 hover:text-pink-400 flex items-center gap-1 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Ver Entrada
                        </a>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.blog.posts.index') }}"
                        class="px-6 py-3 bg-white/5 hover:bg-white/10 text-gray-300 rounded-xl font-semibold transition-colors">
                        Volver
                    </a>
                    <button type="submit" name="status_btn" value="draft"
                        class="px-6 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-500 rounded-xl font-semibold transition-colors">
                        Guardar Borrador
                    </button>
                    <button type="submit" name="status_btn" value="published"
                        class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors">
                        {{ $post->status === 'published' ? 'Actualizar' : 'Publicar' }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content (70%) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Title --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                            required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500 text-xl font-semibold"
                            placeholder="Escribe un título atractivo...">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Slug (URL)</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                                    readonly
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-400 focus:outline-none focus:border-pink-500 cursor-not-allowed"
                                    placeholder="se-genera-automaticamente">
                            </div>
                            <button type="button" id="editSlugBtn"
                                class="px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-lg font-semibold transition-colors text-sm whitespace-nowrap">
                                Editar
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">URL: {{ url('/blog') }}/{{ $post->slug }}</p>
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Contenido *</label>
                        <textarea name="content" id="content" rows="20"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Excerpt --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Extracto</label>
                        <textarea name="excerpt" rows="3" maxlength="500"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="Breve descripción del artículo (máx. 500 caracteres)">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Sidebar (30%) --}}
                <div class="space-y-6">
                    {{-- Publish Settings --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">Publicación</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Fecha de Publicación</label>
                                <input type="datetime-local" name="published_at"
                                    value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                            </div>

                            <div class="pt-4 border-t border-white/5">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Creado:</span>
                                    <span class="text-gray-200">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-400">Actualizado:</span>
                                    <span class="text-gray-200">{{ $post->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-400">Vistas:</span>
                                    <span class="text-gray-200">{{ number_format($post->views) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">Imagen Destacada</h3>

                        <div class="space-y-4">
                            @if ($post->featured_image)
                                <div id="currentImage"
                                    class="aspect-video rounded-lg overflow-hidden bg-white/5 border border-white/10 relative group">
                                    <img src="{{ asset('app-media/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover">
                                    <button type="button"
                                        onclick="document.getElementById('currentImage').remove(); document.getElementById('removeImage').value = '1';"
                                        class="absolute top-2 right-2 p-2 bg-red-500 hover:bg-red-600 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="removeImage" value="0">
                            @endif

                            <div id="imagePreview"
                                class="hidden aspect-video rounded-lg overflow-hidden bg-white/5 border border-white/10">
                                <img id="previewImg" src="" alt="Preview" class="w-full h-full object-cover">
                            </div>

                            <label class="block">
                                <span class="sr-only">Seleccionar imagen</span>
                                <input type="file" name="featured_image" id="featuredImage" accept="image/*"
                                    class="block w-full text-sm text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-pink-500 file:text-white
                                          hover:file:bg-pink-600
                                          file:cursor-pointer cursor-pointer">
                            </label>
                            <p class="text-xs text-gray-500">JPG, PNG o GIF. Máx. 2MB.</p>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">Categoría</h3>

                        <select name="category_id"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                            <option value="">Sin categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <a href="{{ route('admin.blog.categories.index') }}"
                            class="text-sm text-pink-500 hover:text-pink-400 mt-2 inline-block">
                            + Crear nueva categoría
                        </a>
                    </div>

                    {{-- SEO Settings --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">SEO</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Meta Título</label>
                                <input type="text" name="meta_title" maxlength="60"
                                    value="{{ old('meta_title', $post->meta_title) }}"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Título para SEO (máx. 60 caracteres)">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Meta Descripción</label>
                                <textarea name="meta_description" rows="3" maxlength="160"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Descripción para SEO (máx. 160 caracteres)">{{ old('meta_description', $post->meta_description) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Palabras Clave</label>
                                <input type="text" name="meta_keywords"
                                    value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="palabra1, palabra2, palabra3">
                            </div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">Estadísticas</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Tiempo de lectura</span>
                                <span class="text-sm font-semibold text-gray-200"
                                    id="readingTime">{{ $post->reading_time ?? 0 }} min</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Palabras</span>
                                <span class="text-sm font-semibold text-gray-200" id="wordCount">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Caracteres</span>
                                <span class="text-sm font-semibold text-gray-200" id="charCount">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Delete --}}
                    <div class="bg-red-500/10 rounded-2xl p-6 border border-red-500/20">
                        <h3 class="text-lg font-semibold mb-2 text-red-500">Zona de Peligro</h3>
                        <p class="text-sm text-gray-400 mb-4">Esta acción no se puede deshacer.</p>

                        <button type="button"
                            onclick="if(confirm('¿Estás seguro de eliminar este post?')) document.getElementById('deletePostForm').submit();"
                            class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition-colors">
                            Eliminar Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="deletePostForm" action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    {{-- CKEditor 5 Styles for Dark Theme --}}
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-editor__main>.ck-editor__editable {
            background: #1f2937 !important;
        }

        .ck.ck-toolbar {
            background: #111827 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-toolbar__separator {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-button {
            color: #9ca3af !important;
            cursor: pointer !important;
        }

        .ck.ck-button:not(.ck-disabled):hover,
        .ck.ck-button.ck-on {
            background: #374151 !important;
            color: #fff !important;
        }

        .ck.ck-list {
            background: #1f2937 !important;
        }

        .ck.ck-list__item .ck-button:hover:not(.ck-disabled) {
            background: #374151 !important;
        }

        .ck.ck-dropdown__panel {
            background: #1f2937 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor 5
            let editorInstance;
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Párrafo',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Título 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Título 3',
                                class: 'ck-heading_heading3'
                            }
                        ]
                    }
                })
                .then(editor => {
                    editorInstance = editor;
                    // Update textarea whenever editor content changes
                    editor.model.document.on('change:data', () => {
                        document.querySelector('#content').value = editor.getData();
                        updateStats();
                    });
                    // Force update stats on load
                    setTimeout(updateStats, 500);
                })
                .catch(error => {
                    console.error(error);
                });

            // Handle Slug Logic
            const slugInput = document.getElementById('slug');
            const editSlugBtn = document.getElementById('editSlugBtn');

            editSlugBtn.addEventListener('click', function() {
                slugInput.removeAttribute('readonly');
                slugInput.classList.remove('cursor-not-allowed', 'text-gray-400');
                slugInput.classList.add('text-gray-200');
                slugInput.focus();
                this.style.display = 'none';
            });

            // Image preview & Compression
            const featuredImage = document.getElementById('featuredImage');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const submitButtons = document.querySelectorAll('button[type="submit"]');

            featuredImage.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        if (document.getElementById('currentImage')) {
                            document.getElementById('currentImage').classList.add('opacity-50');
                        }
                    };
                    reader.readAsDataURL(file);

                    if (file.size > 1024 * 1024) {
                        setCompressingState(true);
                        compressImage(file);
                    }
                }
            });

            function setCompressingState(isCompressing) {
                submitButtons.forEach(btn => {
                    btn.disabled = isCompressing;
                    if (isCompressing) {
                        btn.dataset.originalText = btn.innerText;
                        btn.innerText = 'Optimizando...';
                    } else {
                        btn.innerText = btn.dataset.originalText || btn.innerText;
                    }
                });
            }

            function compressImage(file) {
                new Compressor(file, {
                    quality: 0.8,
                    maxWidth: 1920,
                    success(result) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(result);
                        featuredImage.files = dataTransfer.files;
                        setCompressingState(false);
                    },
                    error(err) {
                        console.error(err);
                        setCompressingState(false);
                    }
                });
            }

            // Stats Update
            function updateStats() {
                if (!editorInstance) return;
                const content = editorInstance.getData().replace(/<[^>]*>/g, '');
                const words = content.trim().split(/\s+/).filter(w => w.length > 0).length;
                const chars = content.length;
                const readingTime = Math.ceil(words / 200);

                document.getElementById('wordCount').textContent = words.toLocaleString();
                document.getElementById('charCount').textContent = chars.toLocaleString();
                document.getElementById('readingTime').textContent = readingTime + ' min';
            }

            // AJAX Submission
            const postForm = document.getElementById('postForm');

            postForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Set status based on which button was clicked
                const clickedButton = e.submitter;
                if (clickedButton && clickedButton.name === 'status_btn') {
                    document.getElementById('postStatus').value = clickedButton.value;
                }

                const formData = new FormData(postForm);

                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.dataset.originalText = btn.innerText;
                    btn.innerText = 'Guardando...';
                });

                fetch(postForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (response.ok) {
                            return data;
                        } else {
                            if (response.status === 422) {
                                console.error('Validation errors:', data.errors);
                            }
                            throw new Error(data.message || 'Error del servidor');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification(error.message || 'Error al guardar el post', 'error');
                    })
                    .finally(() => {
                        submitButtons.forEach(btn => {
                            btn.disabled = false;
                            btn.innerText = btn.dataset.originalText || btn.innerText;
                        });
                    });
            });

            function showNotification(message, type) {
                const toast = document.createElement('div');
                toast.className =
                    `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
                toast.innerText = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        });
    </script>
@endpush
