@extends('auth.layout')

@section('title', 'Two Factor Challenge')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Two-factor auth</p>
        <h2 class="mt-2 text-3xl font-bold">Enter your authentication code</h2>
        <p class="mt-2 text-sm text-slate-600">Use a code from your authenticator app or a recovery code.</p>
    </div>

    <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-4">
        @csrf
        <div>
            <label for="code" class="mb-2 block text-sm font-medium">Authentication code</label>
            <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-sky-500 focus:ring-sky-500">
        </div>

        <div>
            <label for="recovery_code" class="mb-2 block text-sm font-medium">Recovery code</label>
            <input id="recovery_code" name="recovery_code" type="text" autocomplete="one-time-code"
                   class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 focus:border-sky-500 focus:ring-sky-500">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
            Continue
        </button>
    </form>
@endsection
