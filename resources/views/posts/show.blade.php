@extends('layouts.app')

@section('content')
<article class="mx-auto max-w-4xl">
    <div class="mb-8">
        <a href="{{ localized_route('posts.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            {{ __('Back to Blog') }}
        </a>
    </div>
    
    <header class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white">{{ $post->title }}</h1>
        
        <div class="mt-4 flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
            <time>{{ $post->published_at->format('M d, Y') }}</time>
            <span>•</span>
            <span>{{ __('By') }} {{ $post->user->name }}</span>
        </div>
    </header>
    
    @if($post->featured_image)
        <div class="mb-8">
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full rounded-xl">
        </div>
    @endif
    
    <div class="prose prose-slate max-w-none dark:prose-invert">
        {!! nl2br(e($post->content)) !!}
    </div>
    
    <footer class="mt-12 border-t border-slate-200 pt-8 dark:border-slate-700">
        <div class="flex items-center justify-between">
            <a href="{{ localized_route('posts.index') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                ← {{ __('All Posts') }}
            </a>
            
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Share') }}:</span>
                <div class="flex gap-2">
                    @foreach(['en', 'fr', 'ar'] as $locale)
                        <a href="{{ localized_route('posts.show', $post->getTranslation('slug', $locale), $locale) }}" 
                           class="rounded-lg px-3 py-1 text-sm font-medium transition {{ app()->getLocale() === $locale ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                            {{ strtoupper($locale) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
</article>
@endsection

