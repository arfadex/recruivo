@extends('layouts.guest')

@section('content')
<div class="mx-auto flex max-w-xl flex-col items-center py-12">
    <div class="w-full space-y-8 rounded-3xl border border-slate-200/70 bg-white/80 p-10 shadow-2xl shadow-indigo-500/10 dark:border-slate-800/60 dark:bg-slate-950/80">
        <div class="space-y-3 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-500/10">
                <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-slate-900 dark:text-white">{{ __('auth.forgot_password_title') }}</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                {{ __('auth.forgot_password_desc') }}
            </p>
        </div>

        @if(session('status'))
            <x-alert type="success">
                {{ session('status') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </x-alert>
        @endif

        <form method="POST" action="{{ localized_route('password.email') }}" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('auth.email') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    placeholder="{{ __('auth.email_placeholder') }}"
                    required
                    value="{{ old('email') }}"
                    class="w-full rounded-2xl border border-slate-200/80 bg-white/80 px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100 dark:focus:border-indigo-500"
                />
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-2 dark:focus:ring-offset-slate-950"
            >
                {{ __('auth.email_reset_link') }}
            </button>
        </form>

        <div class="flex items-center justify-between text-sm">
            <a href="{{ localized_route('login') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                {{ __('auth.back_to_login') }}
            </a>
            <a href="{{ localized_route('register') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                {{ __('auth.create_account') }}
            </a>
        </div>
    </div>
</div>
@endsection

