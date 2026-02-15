@extends('layouts.blog')

@section('meta_title', $category->meta_title ?: $category->name . ' - Blog - ' . config('app.name'))
@section('meta_description', $category->meta_description ?: $category->description)

@section('content')
    <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-pink-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="mb-8 text-sm">
                <ol class="flex items-center gap-2 text-gray-500">
                    <li><a href="{{ route('blog.index') }}" class="hover:text-amber-600">Blog</a></li>
                    <li>/</li>
                    <li class="text-gray-900">{{ $category->name }}</li>
                </ol>
            </nav>

            {{-- Category Header --}}
            <div class="text-center mb-12">
                <div class="inline-block px-6 py-2 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full mb-4">
                    Categoría
                </div>
                <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-4"
                    style="font-family: 'Playfair Display', serif;">
                    {{ $category->name }}
                </h1>
                @if ($category->description)
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        {{ $category->description }}
                    </p>
                @endif
                <p class="text-sm text-gray-500 mt-4">
                    {{ $posts->total() }} {{ $posts->total() === 1 ? 'artículo' : 'artículos' }}
                </p>
            </div>

            {{-- Posts Grid --}}
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <article
                            class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            {{-- Featured Image --}}
                            @if ($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ asset('app-media/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                    <div
                                        class="aspect-video bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                </a>
                            @endif

                            {{-- Content --}}
                            <div class="p-6">
                                {{-- Date --}}
                                <div class="mb-3">
                                    <span class="text-xs text-gray-500">
                                        {{ $post->published_at->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h2
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-amber-600 transition-colors">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                {{-- Excerpt --}}
                                @if ($post->excerpt)
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                {{-- Meta Info --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $post->author->name }}</span>
                                    </div>
                                    @if ($post->reading_time)
                                        <span class="text-xs text-gray-500">
                                            ⏱️ {{ $post->reading_time }} min
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay posts en esta categoría</h3>
                    <p class="text-gray-600 mb-6">Vuelve pronto para descubrir nuevo contenido.</p>
                    <a href="{{ route('blog.index') }}"
                        class="inline-block px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-md">
                        Ver todos los artículos
                    </a>
                </div>
            @endif

            {{-- Other Categories --}}
            @php
                $otherCategories = \App\Models\BlogCategory::active()
                    ->where('id', '!=', $category->id)
                    ->withCount('posts')
                    ->having('posts_count', '>', 0)
                    ->take(5)
                    ->get();
            @endphp

            @if ($otherCategories->count() > 0)
                <div class="mt-16 pt-8 border-t border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Explora Otras Categorías</h3>
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach ($otherCategories as $otherCategory)
                            <a href="{{ route('blog.category', $otherCategory->slug) }}"
                                class="px-6 py-3 bg-white rounded-full shadow-md hover:shadow-lg transition-all border-2 border-gray-100 hover:border-amber-500 group">
                                <span class="font-semibold text-gray-700 group-hover:text-amber-600">
                                    {{ $otherCategory->name }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500">
                                    ({{ $otherCategory->posts_count }})
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
