@extends('auth.layout')

@section('title', 'Login')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-700">Welcome back</p>
        <h2 class="mt-2 text-3xl font-bold">Sign in to continue</h2>
        <p class="mt-2 text-sm text-slate-600">Enter your account details to open the dashboard.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="mb-2 block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-teal-500 focus:ring-teal-500">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">Password</label>
            <div x-data="{ showPassword: false }" class="relative">
                <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                       class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 pr-12 focus:border-teal-500 focus:ring-teal-500">
                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 mr-3 grid place-items-center text-slate-600 hover:text-slate-800"
                        aria-label="Toggle password visibility">
                    <svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display: none;">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display: none;">
                        <path d="M3 3l18 18" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.42-4.42" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.88 5.09A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.05 3.82M6.22 6.22C3.73 8.07 2 12 2 12s3.5 7 10 7a9.8 9.8 0 0 0 4.1-.88" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if ($canResetPassword ?? false)
                <div class="mt-2 text-right">
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800">Forgot password?</a>
                </div>
            @endif
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
            Remember me
        </label>

        <button type="submit"
                class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
            Login
        </button>
    </form>

    @if ($canRegister ?? false)
        <p class="mt-6 text-center text-sm text-slate-600">
            New here?
            <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:text-teal-800">Create account</a>
        </p>
    @endif
@endsection
