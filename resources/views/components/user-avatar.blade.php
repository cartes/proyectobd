@props(['user', 'size' => 'md'])

@php
    $initials = strtoupper(substr($user->name, 0, 1)) . strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1));
    
    $bgColor = $user->user_type === 'sugar_daddy' 
        ? 'bg-blue-900' 
        : 'bg-pink-500';
    
    $sizeClasses = match($size) {
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-16 h-16 text-2xl',
        'xl' => 'w-24 h-24 text-4xl',
        default => 'w-10 h-10 text-sm',
    };
    
    $hasFoto = !empty($user->primary_photo_url);
@endphp

@if($hasFoto)
    <img src="{{ $user->primary_photo_url }}" 
         alt="{{ $user->name }}"
         class="{{ $sizeClasses }} mr-4 rounded-full object-cover shadow-md border-2 border-gray-200" />
@else
    <div class="{{ $bgColor }} {{ $sizeClasses }} mr-4 rounded-full flex items-center justify-center font-bold text-white shadow-md">
        {{ $initials }}
    </div>
@endif
