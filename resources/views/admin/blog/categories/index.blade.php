@extends('layouts.admin')

@section('title', 'Categorías - Blog')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-outfit font-bold">Categorías del Blog</h2>
                <p class="text-gray-400 mt-1">Organiza tus posts en categorías</p>
            </div>
            <button onclick="openModal()"
                class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Categoría
            </button>
        </div>

        {{-- Categories Table --}}
        <div class="bg-[#0c111d] rounded-2xl border border-white/5 overflow-hidden">
            @if ($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5 border-b border-white/5">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Slug</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Posts</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-200">{{ $category->name }}</p>
                                        @if ($category->description)
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ Str::limit($category->description, 60) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <code
                                            class="text-sm text-gray-400 bg-white/5 px-2 py-1 rounded">{{ $category->slug }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 bg-blue-500/10 text-blue-500 text-sm font-semibold rounded-full">
                                            {{ $category->posts_count }} posts
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.blog.categories.update', $category) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_active"
                                                value="{{ $category->is_active ? '0' : '1' }}">
                                            <button type="submit"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $category->is_active ? 'bg-pink-500' : 'bg-gray-600' }}">
                                                <span
                                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $category->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->slug }}', '{{ addslashes($category->description ?? '') }}', '{{ $category->meta_title ?? '' }}', '{{ addslashes($category->meta_description ?? '') }}')"
                                                class="p-2 hover:bg-white/10 rounded-lg transition-colors" title="Editar">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            @if ($category->posts_count === 0)
                                                <form action="{{ route('admin.blog.categories.destroy', $category) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 hover:bg-red-500/10 rounded-lg transition-colors"
                                                        title="Eliminar">
                                                        <svg class="w-4 h-4 text-red-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled class="p-2 opacity-50 cursor-not-allowed rounded-lg"
                                                    title="No se puede eliminar (tiene posts)">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay categorías</h3>
                    <p class="text-gray-500 mb-6">Crea categorías para organizar tus posts</p>
                    <button onclick="openModal()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear Primera Categoría
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    <div id="categoryModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-[#0c111d] rounded-2xl border border-white/10 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="p-6 border-b border-white/5">
                    <h3 class="text-2xl font-bold" id="modalTitle">Nueva Categoría</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Nombre *</label>
                        <input type="text" name="name" id="categoryName" required
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="Nombre de la categoría">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Slug</label>
                        <input type="text" name="slug" id="categorySlug"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="se-genera-automaticamente">
                        <p class="text-xs text-gray-500 mt-1">Se genera automáticamente. Puedes editarlo si lo deseas.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Descripción</label>
                        <textarea name="description" id="categoryDescription" rows="3"
                            class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                            placeholder="Breve descripción de la categoría"></textarea>
                    </div>

                    <div class="pt-4 border-t border-white/5">
                        <h4 class="font-semibold mb-3">SEO</h4>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Meta Título</label>
                                <input type="text" name="meta_title" id="categoryMetaTitle" maxlength="60"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Título para SEO">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Meta Descripción</label>
                                <textarea name="meta_description" id="categoryMetaDescription" rows="2" maxlength="160"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500"
                                    placeholder="Descripción para SEO"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-lg font-semibold transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-semibold transition-colors">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');
        const nameInput = document.getElementById('categoryName');
        const slugInput = document.getElementById('categorySlug');
        let manualSlug = false;

        function openModal() {
            modal.classList.remove('hidden');
            form.action = '{{ route('admin.blog.categories.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('modalTitle').textContent = 'Nueva Categoría';
            form.reset();
            manualSlug = false;
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function editCategory(id, name, slug, description, metaTitle, metaDescription) {
            modal.classList.remove('hidden');
            form.action = `/admin/blog/categories/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('modalTitle').textContent = 'Editar Categoría';

            nameInput.value = name;
            slugInput.value = slug;
            document.getElementById('categoryDescription').value = description;
            document.getElementById('categoryMetaTitle').value = metaTitle;
            document.getElementById('categoryMetaDescription').value = metaDescription;

            manualSlug = true;
        }

        // Auto-generate slug
        slugInput.addEventListener('input', function() {
            manualSlug = true;
        });

        nameInput.addEventListener('input', function() {
            if (!manualSlug) {
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

        // Close modal on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
@endpush
