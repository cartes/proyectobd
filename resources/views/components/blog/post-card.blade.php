{{-- Blog Post Card Component --}}
@props(['post'])

<article
    {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1']) }}>
    {{-- Featured Image --}}
    @if ($post->featured_image)
        <a href="{{ route('blog.show', $post->slug) }}" class="block">
            <div class="aspect-video overflow-hidden">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            </div>
        </a>
    @else
        <a href="{{ route('blog.show', $post->slug) }}" class="block">
            <div class="aspect-video bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
        </a>
    @endif

    {{-- Content --}}
    <div class="p-6">
        {{-- Category & Date --}}
        <div class="flex items-center justify-between mb-3">
            @if ($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}"
                    class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full hover:bg-amber-200 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif
            <span class="text-xs text-gray-500">
                {{ $post->published_at->format('d M Y') }}
            </span>
        </div>

        {{-- Title --}}
        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-amber-600 transition-colors">
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
