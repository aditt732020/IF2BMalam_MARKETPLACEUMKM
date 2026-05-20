@php
    $colors = [
        'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'red' => 'bg-red-50 text-red-700 border-red-200',
        'gray' => 'bg-gray-50 text-gray-600 border-gray-200',
    ];
    $color = $colors[$order->status_color] ?? $colors['gray'];
@endphp
<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $color }}">
    {{ $order->status_label }}
</span>
