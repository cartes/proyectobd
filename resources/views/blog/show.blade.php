@extends('layouts.blog')

@section('meta_title', $post->meta_title ?: $post->title . ' - ' . config('app.name'))
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('canonical_url', route('blog.show', $post->slug))

@section('og_type', 'article')
@section('og_title', $post->meta_title ?: $post->title)
@section('og_description', $post->meta_description ?: $post->excerpt)
@section('og_url', route('blog.show', $post->slug))
@section('og_image', $post->og_image ? asset('app-media/' . $post->og_image) : ($post->featured_image ?
    asset('app-media/' . $post->featured_image) : asset('images/og-default.jpg')))

@section('twitter_title', $post->meta_title ?: $post->title)
@section('twitter_description', $post->meta_description ?: $post->excerpt)
@section('twitter_image', $post->og_image ? asset('app-media/' . $post->og_image) : ($post->featured_image ?
    asset('app-media/' . $post->featured_image) : asset('images/og-default.jpg')))

    @push('styles')
        <style>
            /* Dark Theme */
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e293b 100%);
            }

            /* Hero Background */
            .hero-bg {
                background-image: linear-gradient(135deg, rgba(236, 72, 153, 0.3) 0%, rgba(168, 85, 247, 0.3) 50%, rgba(251, 146, 60, 0.3) 100%),
                    url('{{ $post->featured_image ? asset('app-media/' . $post->featured_image) : '' }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }

            /* Article Content Styling */
            .article-content {
                font-size: 1.125rem;
                line-height: 1.8;
                color: #e5e7eb;
            }

            .article-content h2 {
                font-size: 2rem;
                font-weight: 700;
                margin-top: 2.5rem;
                margin-bottom: 1rem;
                background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .article-content h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-top: 2rem;
                margin-bottom: 0.75rem;
                color: #f3f4f6;
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
                color: #ec4899;
                text-decoration: underline;
                transition: color 0.2s;
            }

            .article-content a:hover {
                color: #a855f7;
            }

            .article-content img {
                border-radius: 1rem;
                margin: 2rem 0;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            }

            .article-content blockquote {
                border-left: 4px solid #ec4899;
                padding-left: 1.5rem;
                margin: 2rem 0;
                font-style: italic;
                color: #9ca3af;
                background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
                padding: 1.5rem;
                border-radius: 0.5rem;
            }

            .glass-badge {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .dark-card {
                background: rgba(15, 23, 42, 0.8);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    @endpush

@section('content')
    {{-- Hero Section --}}
    <div class="hero-bg relative min-h-[70vh] flex items-end">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>

        <div class="relative z-10 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            {{-- Back Button & Breadcrumbs --}}
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('blog.index') }}"
                    class="glass-badge flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-xl hover:bg-white/20 transition-all group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al Blog
                </a>

                <nav class="text-sm flex-1">
                    <ol class="flex items-center gap-2 text-white/60">
                        @if ($post->category)
                            <li><a href="{{ route('blog.category', $post->category->slug) }}"
                                    class="hover:text-white transition-colors">{{ $post->category->name }}</a></li>
                        @endif
                    </ol>
                </nav>
            </div>

            {{-- Category Badge --}}
            @if ($post->category)
                <div class="mb-6">
                    <a href="{{ route('blog.category', $post->category->slug) }}"
                        class="glass-badge inline-block px-4 py-2 text-white text-sm font-semibold rounded-full hover:bg-white/20 transition-all">
                        {{ $post->category->name }}
                    </a>
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight"
                style="font-family: 'Outfit', sans-serif;">
                {{ $post->title }}
            </h1>

            {{-- Meta Information --}}
            <div class="flex flex-wrap items-center gap-6 text-white/90">
                {{-- Date --}}
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium">{{ $post->published_at->format('d M Y') }}</span>
                </div>

                {{-- Reading Time --}}
                @if ($post->reading_time)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">{{ $post->reading_time }} min</span>
                    </div>
                @endif

                {{-- Views --}}
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="text-sm font-medium">{{ number_format($post->views) }} vistas</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <article class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Excerpt --}}
            @if ($post->excerpt)
                <div
                    class="text-xl text-gray-200 font-medium mb-12 p-8 dark-card rounded-2xl border-l-4 border-pink-500 shadow-2xl">
                    {{ $post->excerpt }}
                </div>
            @endif

            {{-- Content --}}
            <div class="article-content prose prose-lg max-w-none dark-card p-8 md:p-12 rounded-2xl shadow-2xl">
                {!! $post->content !!}
            </div>

            {{-- Share Buttons --}}
            <div class="mt-12 p-8 dark-card rounded-2xl shadow-2xl">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    Compartir este artículo
                </h3>
                <div class="flex flex-wrap gap-3">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        Facebook
                    </a>

                    {{-- Twitter --}}
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                        Twitter
                    </a>

                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-700 to-blue-800 text-white rounded-xl hover:from-blue-800 hover:to-blue-900 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
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
                <div class="mt-16">
                    <h3 class="text-3xl font-bold text-white mb-8 flex items-center gap-3">
                        <div class="w-1 h-8 bg-gradient-to-b from-pink-500 to-purple-500 rounded-full"></div>
                        Artículos Relacionados
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedPosts as $related)
                            <article
                                class="group dark-card rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1">
                                @if ($related->featured_image)
                                    <a href="{{ route('blog.show', $related->slug) }}">
                                        <div class="aspect-video overflow-hidden relative">
                                            <img src="{{ asset('app-media/' . $related->featured_image) }}"
                                                alt="{{ $related->title }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                <div class="p-5">
                                    <h4
                                        class="font-bold text-white mb-2 line-clamp-2 group-hover:text-pink-400 transition-colors text-lg">
                                        <a href="{{ route('blog.show', $related->slug) }}">
                                            {{ $related->title }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-400 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
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
        {{-- Schema.org JSON-LD for SEO --}}
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BlogPosting",
          "headline": {{ json_encode($post->title) }},
          "description": {{ json_encode($post->excerpt ?? '') }},
          @if($post->featured_image)
          "image": {{ json_encode(asset('app-media/' . $post->featured_image)) }},
          @endif
          "datePublished": {{ json_encode($post->published_at->toIso8601String()) }},
          "dateModified": {{ json_encode($post->updated_at->toIso8601String()) }},
          "author": {
            "@type": "Person",
            "name": {{ json_encode($post->author->name ?? config('app.name')) }}
          },
          "publisher": {
            "@type": "Organization",
            "name": {{ json_encode(config('app.name')) }},
            "logo": {
              "@type": "ImageObject",
              "url": {{ json_encode(asset('favicon.png')) }}
            }
          },
          @if($post->category)
          "articleSection": {{ json_encode($post->category->name) }},
          @endif
          "wordCount": {{ str_word_count(strip_tags($post->content)) }},
          "timeRequired": {{ json_encode('PT' . ($post->reading_time ?? 5) . 'M') }},
          "url": {{ json_encode(route('blog.show', $post->slug)) }},
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": {{ json_encode(route('blog.show', $post->slug)) }}
          }
        }
        </script>

        {{-- Google Analytics Event Tracking --}}
        <script>
            // Track article read event for analytics
            @if (config('services.google_analytics.id'))
                gtag('event', 'article_read', {
                    'article_title': {{ json_encode($post->title) }},
                    'article_category': {{ json_encode($post->category ? $post->category->name : 'Uncategorized') }}
                });
            @endif
        </script>
    @endpush
@endsection
