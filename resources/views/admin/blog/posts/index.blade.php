@extends('layouts.admin')

@section('title', 'Gestión de Posts - Blog')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-outfit font-bold">Posts del Blog</h2>
                <p class="text-gray-400 mt-1">Gestiona todos los artículos del blog</p>
            </div>
            <a href="{{ route('admin.blog.posts.create') }}"
                class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Post
            </a>
        </div>

        {{-- Filters --}}
        <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
            <form method="GET" action="{{ route('admin.blog.posts.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar posts..."
                        class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 placeholder-gray-500 focus:outline-none focus:border-pink-500">
                </div>

                {{-- Status Filter --}}
                <div>
                    <select name="status"
                        class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                        <option value="">Todos los estados</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado
                        </option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Programado
                        </option>
                    </select>
                </div>

                {{-- Category Filter --}}
                <div>
                    <select name="category"
                        class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-gray-200 focus:outline-none focus:border-pink-500">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-semibold transition-colors">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.blog.posts.index') }}"
                        class="px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-lg font-semibold transition-colors">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Posts</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Publicados</p>
                        <p class="text-3xl font-bold mt-1 text-green-500">{{ $stats['published'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Borradores</p>
                        <p class="text-3xl font-bold mt-1 text-yellow-500">{{ $stats['draft'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-[#0c111d] rounded-2xl p-6 border border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Vistas</p>
                        <p class="text-3xl font-bold mt-1 text-purple-500">{{ number_format($stats['views']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Posts Table --}}
        <div class="bg-[#0c111d] rounded-2xl border border-white/5 overflow-hidden">
            @if ($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5 border-b border-white/5">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Título</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Autor</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Categoría</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Fecha</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Vistas</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($post->featured_image)
                                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                    alt="{{ $post->title }}" class="w-12 h-12 rounded-lg object-cover">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.blog.posts.edit', $post) }}"
                                                    class="font-semibold text-gray-200 hover:text-pink-500 transition-colors">
                                                    {{ Str::limit($post->title, 50) }}
                                                </a>
                                                <p class="text-sm text-gray-500">{{ $post->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-300">{{ $post->author->name }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($post->category)
                                            <span class="px-2 py-1 bg-amber-500/10 text-amber-500 text-xs rounded-full">
                                                {{ $post->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-sm">Sin categoría</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($post->status === 'published')
                                            <span
                                                class="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-semibold rounded-full">
                                                Publicado
                                            </span>
                                        @elseif($post->status === 'draft')
                                            <span
                                                class="px-3 py-1 bg-yellow-500/10 text-yellow-500 text-xs font-semibold rounded-full">
                                                Borrador
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 bg-blue-500/10 text-blue-500 text-xs font-semibold rounded-full">
                                                Programado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-300">
                                            {{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-300">{{ number_format($post->views) }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($post->status === 'published')
                                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                                    class="p-2 hover:bg-white/10 rounded-lg transition-colors"
                                                    title="Ver">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.blog.posts.edit', $post) }}"
                                                class="p-2 hover:bg-white/10 rounded-lg transition-colors" title="Editar">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.blog.posts.duplicate', $post) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 hover:bg-white/10 rounded-lg transition-colors"
                                                    title="Duplicar">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este post?')">
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $posts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay posts</h3>
                    <p class="text-gray-500 mb-6">Comienza creando tu primer artículo</p>
                    <a href="{{ route('admin.blog.posts.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-xl font-semibold transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear Primer Post
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
