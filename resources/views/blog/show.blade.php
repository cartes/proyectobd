@extends('layouts.blog')

@section('meta_title', $post->meta_title ?: $post->title . ' - ' . config('app.name'))
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('canonical_url', route('blog.show', $post->slug))

@section('og_type', 'article')
@section('og_title', $post->meta_title ?: $post->title)
@section('og_description', $post->meta_description ?: $post->excerpt)
@section('og_url', route('blog.show', $post->slug))
@section('og_image', $post->og_image ? asset('storage/' . $post->og_image) : ($post->featured_image ? asset('storage/' .
    $post->featured_image) : asset('images/og-default.jpg')))

@section('twitter_title', $post->meta_title ?: $post->title)
@section('twitter_description', $post->meta_description ?: $post->excerpt)
@section('twitter_image', $post->og_image ? asset('storage/' . $post->og_image) : ($post->featured_image ?
    asset('storage/' . $post->featured_image) : asset('images/og-default.jpg')))

@section('author', $post->author->name)

@push('styles')
    <style>
        /* Article Content Styling */
        .article-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #374151;
        }

        .article-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }

        .article-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #111827;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .article-content a {
            color: #f59e0b;
            text-decoration: underline;
        }

        .article-content a:hover {
            color: #d97706;
        }

        .article-content img {
            border-radius: 0.75rem;
            margin: 2rem 0;
        }

        .article-content blockquote {
            border-left: 4px solid #f59e0b;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6b7280;
        }
    </style>
@endpush

@section('content')
    <article class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="mb-8 text-sm">
                <ol class="flex items-center gap-2 text-gray-500">
                    <li><a href="{{ route('blog.index') }}" class="hover:text-amber-600">Blog</a></li>
                    <li>/</li>
                    @if ($post->category)
                        <li><a href="{{ route('blog.category', $post->category->slug) }}"
                                class="hover:text-amber-600">{{ $post->category->name }}</a></li>
                        <li>/</li>
                    @endif
                    <li class="text-gray-900 truncate">{{ $post->title }}</li>
                </ol>
            </nav>

            {{-- Category Badge --}}
            @if ($post->category)
                <div class="mb-4">
                    <a href="{{ route('blog.category', $post->category->slug) }}"
                        class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full hover:bg-amber-200 transition-colors">
                        {{ $post->category->name }}
                    </a>
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">
                {{ $post->title }}
            </h1>

            {{-- Meta Information --}}
            <div class="flex flex-wrap items-center gap-6 mb-8 pb-8 border-b border-gray-200">
                {{-- Author --}}
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-lg font-bold">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $post->author->name }}</p>
                        <p class="text-xs text-gray-500">{{ $post->published_at->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Reading Time --}}
                @if ($post->reading_time)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm">{{ $post->reading_time }} min de lectura</span>
                    </div>
                @endif

                {{-- Views --}}
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="text-sm">{{ number_format($post->views) }} vistas</span>
                </div>
            </div>

            {{-- Featured Image --}}
            @if ($post->featured_image)
                <div class="mb-10 rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                        class="w-full h-auto">
                </div>
            @endif

            {{-- Excerpt --}}
            @if ($post->excerpt)
                <div class="text-xl text-gray-700 font-medium mb-8 p-6 bg-amber-50 rounded-xl border-l-4 border-amber-500">
                    {{ $post->excerpt }}
                </div>
            @endif

            {{-- Content --}}
            <div class="article-content prose prose-lg max-w-none">
                {!! $post->content !!}
            </div>

            {{-- Share Buttons --}}
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Compartir este artículo</h3>
                <div class="flex gap-3">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        Facebook
                    </a>

                    {{-- Twitter --}}
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                        Twitter
                    </a>

                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                        LinkedIn
                    </a>
                </div>
            </div>

            {{-- Related Posts --}}
            @if ($relatedPosts->count() > 0)
                <div class="mt-16 pt-8 border-t border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Artículos Relacionados</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedPosts as $related)
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all">
                                @if ($related->featured_image)
                                    <a href="{{ route('blog.show', $related->slug) }}">
                                        <div class="aspect-video overflow-hidden">
                                            <img src="{{ asset('storage/' . $related->featured_image) }}"
                                                alt="{{ $related->title }}"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        </div>
                                    </a>
                                @endif
                                <div class="p-4">
                                    <h4
                                        class="font-bold text-gray-900 mb-2 line-clamp-2 hover:text-amber-600 transition-colors">
                                        <a href="{{ route('blog.show', $related->slug) }}">
                                            {{ $related->title }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $related->published_at->format('d M Y') }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    @push('scripts')
        <script>
            // Track article read event for analytics
            @if (config('services.google_analytics.id'))
                gtag('event', 'article_read', {
                    'article_title': '{{ $post->title }}',
                    'article_category': '{{ $post->category ? $post->category->name : 'Uncategorized' }}',
                    'article_author': '{{ $post->author->name }}'
                });
            @endif
        </script>
    @endpush
@endsection
