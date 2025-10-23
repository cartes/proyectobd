@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border-gray-300 focus:border-purple-500 mb-6 focus:ring-purple-500 rounded-lg shadow-sm transition-colors duration-200',
]) !!}>