@php
    $colors = [
        'success' => 'bg-green-100 text-green-600',
        'warning' => 'bg-yellow-100 text-yellow-600',
        'danger' => 'bg-red-100 text-red-600',
        'info' => 'bg-blue-100 text-blue-600',
        'primary' => 'bg-indigo-100 text-indigo-600',
        'secondary' => 'bg-gray-100 text-gray-600',
    ];
    
    $colorClass = $colors[$status] ?? $colors['secondary'];
@endphp

<span class="{{ $colorClass }} px-3 py-1 rounded-full text-xs font-medium">
    {{ $text }}
</span>