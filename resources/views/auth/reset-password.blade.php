@extends('auth.layout')

@section('title', 'Reset Password')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Set new password</p>
        <h2 class="mt-2 text-3xl font-bold">Reset your password</h2>
        <p class="mt-2 text-sm text-slate-600">Choose a strong password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">New password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Reset password
        </button>
    </form>
@endsection
