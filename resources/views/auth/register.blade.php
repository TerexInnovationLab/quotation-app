@extends('auth.layout')

@section('title', 'Sign Up')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Create account</p>
        <h2 class="mt-2 text-3xl font-bold">Start using Terexlab today</h2>
        <p class="mt-2 text-sm text-slate-600">Create your profile and start managing quotes in minutes.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-2 block text-sm font-medium">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-amber-500 focus:ring-amber-500">
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-amber-500 focus:ring-amber-500">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-amber-500 focus:ring-amber-500">
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-amber-500 focus:ring-amber-500">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-amber-400">
            Create account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Already registered?
        <a href="{{ route('login') }}" class="font-semibold text-amber-700 hover:text-amber-800">Login</a>
    </p>
@endsection
