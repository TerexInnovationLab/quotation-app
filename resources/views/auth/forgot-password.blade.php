@extends('auth.layout')

@section('title', 'Forgot Password')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">Password reset</p>
        <h2 class="mt-2 text-3xl font-bold">Forgot your password?</h2>
        <p class="mt-2 text-sm text-slate-600">Enter your email and we will send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="mb-2 block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-cyan-500 focus:ring-cyan-500">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
            Email reset link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Back to
        <a href="{{ route('login') }}" class="font-semibold text-cyan-700 hover:text-cyan-800">Login</a>
    </p>
@endsection
