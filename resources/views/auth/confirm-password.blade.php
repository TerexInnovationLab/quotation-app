@extends('auth.layout')

@section('title', 'Confirm Password')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-fuchsia-700">Security check</p>
        <h2 class="mt-2 text-3xl font-bold">Confirm your password</h2>
        <p class="mt-2 text-sm text-slate-600">Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
        <div>
            <label for="password" class="mb-2 block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" required autofocus autocomplete="current-password"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-fuchsia-500 focus:ring-fuchsia-500">
            @error('password')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
                class="w-full rounded-xl bg-fuchsia-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-fuchsia-700">
            Confirm password
        </button>
    </form>
@endsection
