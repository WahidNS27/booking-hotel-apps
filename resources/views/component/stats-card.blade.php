<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center">
            <i class="{{ $icon }} {{ $iconColor }} text-xl"></i>
        </div>
        @if($trend)
            @php
                $bgColor = str_contains($trendColor, 'green') ? 'green' : 'orange';
            @endphp
            <span class="text-sm font-semibold {{ $trendColor }} bg-{{ $bgColor }}-100 px-3 py-1 rounded-full">
                <i class="fas fa-{{ $trendIcon }} text-xs"></i> {{ $trend }}
            </span>
        @endif
    </div>
    <h3 class="text-gray-600 text-sm">{{ $title }}</h3>
    <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
    @if($subtext)
        <p class="text-xs text-gray-500 mt-2">{{ $subtext }}</p>
    @endif
</div>