@extends('layouts.admin')

@section('title', 'Crear Post - Blog')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/compressorjs@1.2.1/dist/compressor.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        <input type="hidden" name="status" id="postStatus" value="draft">

        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-outfit font-bold">Crear Nuevo Post</h2>
                    <p class="text-gray-400 mt-1">Escribe un nuevo artículo para el blog</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.blog.posts.index') }}"
                        class="px-6 py-3 bg-white/5 hover:bg-white/10 text-gray-300 rounded-xl font-semibold transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" name="status_btn" value="draft"
                        class="px-6 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-500 rounded-xl font-semibold transition-colors">
                        Guardar Borrador
                    </button>
                    <button type="submit" name="status_btn" value="published"
                        class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors">
                        Publicar
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content (70%) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Title --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
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
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" readonly
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-400 focus:outline-none focus:border-pink-500 cursor-not-allowed"
                                    placeholder="se-genera-automaticamente">
                            </div>
                            <button type="button" id="editSlugBtn"
                                class="px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-lg font-semibold transition-colors text-sm whitespace-nowrap">
                                Editar
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Se genera automáticamente desde el título.</p>
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Contenido *</label>
                        <textarea name="content" id="content" rows="20"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Excerpt --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Extracto</label>
                        <textarea name="excerpt" rows="3" maxlength="500"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="Breve descripción del artículo (máx. 500 caracteres)">{{ old('excerpt') }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Opcional. Se mostrará en las tarjetas de vista previa.</p>
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
                                    value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                                <p class="text-xs text-gray-500 mt-2">Si es futura, el post se programará automáticamente.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                        <h3 class="text-lg font-semibold mb-4">Imagen Destacada</h3>

                        <div class="space-y-4">
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
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <input type="text" name="meta_title" maxlength="60" value="{{ old('meta_title') }}"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Título para SEO (máx. 60 caracteres)">
                                <p class="text-xs text-gray-500 mt-1">Deja vacío para usar el título del post.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Meta Descripción</label>
                                <textarea name="meta_description" rows="3" maxlength="160"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Descripción para SEO (máx. 160 caracteres)">{{ old('meta_description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Palabras Clave</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
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
                                <span class="text-sm font-semibold text-gray-200" id="readingTime">0 min</span>
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
                </div>
            </div>
        </div>
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
                })
                .catch(error => {
                    console.error(error);
                });

            // Handle Slug Logic
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');
            const editSlugBtn = document.getElementById('editSlugBtn');
            let isSlugManual = false;

            editSlugBtn.addEventListener('click', function() {
                isSlugManual = true;
                slugInput.removeAttribute('readonly');
                slugInput.classList.remove('cursor-not-allowed', 'text-gray-400');
                slugInput.classList.add('text-gray-200');
                slugInput.focus();
                this.style.display = 'none';
            });

            titleInput.addEventListener('input', function() {
                if (!isSlugManual) {
                    const slug = this.value
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                    slugInput.value = slug;
                }
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
                    };
                    reader.readAsDataURL(file);

                    if (file.size > 1024 * 1024) {
                        setCompressingState(true);
                        compressImage(file);
                    } else {
                        clearCompressionMsg();
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
            let isCreated = false;

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
                            if (!isCreated && data.redirect_url) {
                                isCreated = true;
                                postForm.action = data.redirect_url;
                                let methodInput = postForm.querySelector('input[name="_method"]');
                                if (!methodInput) {
                                    methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'PUT';
                                    postForm.appendChild(methodInput);
                                }
                                document.querySelector('h2').textContent = 'Editar Post';
                                window.history.pushState({}, '', data.redirect_url);
                            }
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
