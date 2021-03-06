@props(['active','hover_color'=>null])

@php
$hover_color = $hover_color?$hover_color:'green';
$classes = ($active ?? false)
            ? "inline-flex items-center px-1 pt-1 border-b-2 border-green-700 text-sm font-medium leading-5 text-$hover_color-900 focus:outline-none focus:border-gray-700 transition duration-150 ease-in-out"
            : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-900 hover:border-$hover_color-700 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }} 
</a>
                        
