@props([
    'field',
    'sortField',
    'sortDirection',
    'nextDirection',
    'class' => ''
])

@php
    $isCurrentSortField = $field === $sortField;
    $icon = '';
    
    if ($isCurrentSortField) {
        $icon = $sortDirection === 'asc' 
            ? '<svg class="w-3.5 h-3.5 ml-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
              </svg>'
            : '<svg class="w-3.5 h-3.5 ml-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>';
    } else {
        $icon = '<svg class="w-3.5 h-3.5 ml-1.5 flex-shrink-0 opacity-0 group-hover:opacity-50 transition-opacity duration-150" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
              </svg>';
    }
    
    $direction = $isCurrentSortField ? $nextDirection : 'asc';
    $queryParams = array_merge(
        request()->except(['sort', 'direction', 'page']),
        ['sort' => $field, 'direction' => $direction]
    );
    $url = request()->url() . '?' . http_build_query($queryParams);
    
    $baseClasses = 'group flex items-center cursor-pointer select-none';
    $colorClasses = $isCurrentSortField 
        ? 'text-blue-600 dark:text-blue-400' 
        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200';
    $transitionClass = 'transition-all duration-150';
    $hoverClass = 'hover:bg-gray-50 dark:hover:bg-gray-700/50';
    $paddingClass = 'px-3 py-2 -mx-2 rounded-md';
    $focusClass = 'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50';
    
    $classString = implode(' ', array_filter([
        $baseClasses,
        $colorClasses,
        $transitionClass,
        $hoverClass,
        $paddingClass,
        $focusClass,
        $class
    ]));
    
    $wrapperClass = 'flex items-center';
@endphp

<div class="{{ $wrapperClass }}">
    <a href="{{ $url }}" class="{{ $classString }}" wire:navigate>
        <span class="whitespace-nowrap">{{ $slot }}</span>
        {!! $icon !!}
    </a>
</div>
