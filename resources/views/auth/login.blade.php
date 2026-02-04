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
            @error('email')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label for="password" class="block text-sm font-medium">Password</label>
                @if ($canResetPassword ?? false)
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800">Forgot password?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-teal-500 focus:ring-teal-500">
            @error('password')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
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
