@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
            {{ __('jobs.find_opportunity') }}
        </h1>
        <p class="mt-2 text-slate-600 dark:text-slate-400">
            {{ __('jobs.discover_jobs') }}
        </p>
    </div>

    <!-- Results -->
    <div>
        @if(count($jobs) > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($jobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-slate-200/60 bg-white/60 p-12 text-center backdrop-blur dark:border-slate-700/60 dark:bg-slate-900/40">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('jobs.no_jobs_found') }}</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-400">
                    {{ __('jobs.check_back_later') }}
                </p>
                <div class="mt-4">
                    <a
                        href="{{ localized_route('home') }}"
                        class="inline-flex items-center justify-center rounded-full border border-indigo-200 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-400 hover:text-indigo-500 dark:border-indigo-500/40 dark:text-indigo-300 dark:hover:border-indigo-400/60"
                    >
                        {{ __('jobs.back_to_home') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if($jobs->hasPages())
            <div class="mt-8">
                <x-pagination :paginator="$jobs" />
            </div>
        @endif
    </div>
</div>
@endsection

