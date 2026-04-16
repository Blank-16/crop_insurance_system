@props(['title', 'value', 'color' => 'indigo'])

<div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-{{ $color }}-500 flex flex-col justify-center transition hover:shadow-md">
    <div class="text-gray-500 text-sm font-medium mb-1">{{ $title }}</div>
    <div class="text-3xl font-bold text-gray-800">{{ $value }}</div>
</div>
