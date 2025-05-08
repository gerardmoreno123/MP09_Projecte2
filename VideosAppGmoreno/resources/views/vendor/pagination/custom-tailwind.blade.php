@php
    $previousIcon = '<i class="fas fa-chevron-left"></i>';
    $nextIcon = '<i class="fas fa-chevron-right"></i>';
@endphp

<div class="flex items-center justify-center space-x-2">
    <!-- Previous Button -->
    @if ($paginator->onFirstPage())
        <span class="btn btn-primary btn-sm opacity-50 cursor-not-allowed">
            {!! $previousIcon !!}
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-primary btn-sm">
            {!! $previousIcon !!}
        </a>
    @endif

    <!-- Page Numbers -->
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="text-slate-400 px-2">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="btn btn-primary btn-sm bg-blue-800 text-white">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-primary btn-sm">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    <!-- Next Button -->
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-primary btn-sm">
            {!! $nextIcon !!}
        </a>
    @else
        <span class="btn btn-primary btn-sm opacity-50 cursor-not-allowed">
            {!! $nextIcon !!}
        </span>
    @endif
</div>
