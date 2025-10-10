@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col gap-4 items-center justify-between sm:flex-row">
        {{-- Mobile Pagination Numbers --}}
        <div class="flex flex-wrap justify-center gap-1 sm:hidden">
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-500 bg-white/80 border border-slate-300 cursor-default rounded-md dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 rounded-md hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 active:bg-slate-100 active:text-slate-700 transition dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-slate-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($paginator->links()->elements as $element)
                @if (is_string($element))
                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 cursor-default rounded-md dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 cursor-default rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 rounded-md hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 active:bg-slate-100 transition dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-slate-100">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 rounded-md hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 active:bg-slate-100 transition dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-slate-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-slate-500 bg-white/80 border border-slate-300 cursor-default rounded-md dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-700 leading-5 dark:text-slate-300">
                    {!! __('common.pagination_showing', [
                        'from' => '<span class="font-medium">' . ($paginator->firstItem() ?? 0) . '</span>',
                        'to' => '<span class="font-medium">' . ($paginator->lastItem() ?? 0) . '</span>',
                        'total' => '<span class="font-medium">' . $paginator->total() . '</span>'
                    ]) !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    @foreach ($paginator->links()->elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 cursor-default leading-5 dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-400">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-indigo-600 border border-indigo-600 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-700 bg-white/80 border border-slate-300 leading-5 hover:bg-slate-50 hover:text-slate-900 focus:z-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 active:bg-slate-100 active:text-slate-700 transition dark:bg-slate-800/80 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-slate-100" aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </span>
            </div>
        </div>
    </nav>
@endif

