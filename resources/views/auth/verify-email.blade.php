@extends('auth.layout')

@section('title', 'Verify Email')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-700">Verify account</p>
        <h2 class="mt-2 text-3xl font-bold">Check your inbox</h2>
        <p class="mt-2 text-sm text-slate-600">Before continuing, verify your email address using the link we sent.</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            A new verification link has been sent to your email.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
        @csrf
        <button type="submit"
                class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
            Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
            Logout
        </button>
    </form>
@endsection
