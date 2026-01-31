{{-- Blog Category Badge Component --}}
@props(['category', 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-xs',
        'lg' => 'px-4 py-1.5 text-sm',
    ];
    $class = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<a href="{{ route('blog.category', $category->slug) }}"
    {{ $attributes->merge(['class' => "inline-block bg-amber-100 text-amber-700 font-semibold rounded-full hover:bg-amber-200 transition-colors {$class}"]) }}>
    {{ $category->name }}
</a>
