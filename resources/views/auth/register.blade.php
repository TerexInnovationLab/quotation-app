@extends('auth.layout')

@section('title', 'Sign Up')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Create account</p>
        <h2 class="mt-2 text-3xl font-bold">Start using Terexlab today</h2>
        <p class="mt-2 text-sm text-slate-600">Create your profile and start managing quotes in minutes.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4"
          x-data="{ showPassword: false, showPasswordConfirm: false }"
          @submit="if ($refs.password.value !== $refs.passwordConfirmation.value) { $event.preventDefault(); Swal.fire({ icon: 'error', title: 'Something went wrong', text: 'Password and confirm password do not match.', confirmButtonColor: '#0d9488' }); }">
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
            <div class="relative">
                <input id="password" name="password" x-ref="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="new-password"
                       class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 pr-12 focus:border-amber-500 focus:ring-amber-500">
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
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirm password</label>
            <div class="relative">
                <input id="password_confirmation" name="password_confirmation" x-ref="passwordConfirmation" x-bind:type="showPasswordConfirm ? 'text' : 'password'" required autocomplete="new-password"
                       class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 pr-12 focus:border-amber-500 focus:ring-amber-500">
                <button type="button"
                        @click="showPasswordConfirm = !showPasswordConfirm"
                        class="absolute inset-y-0 right-0 mr-3 grid place-items-center text-slate-600 hover:text-slate-800"
                        aria-label="Toggle confirm password visibility">
                    <svg x-show="!showPasswordConfirm" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display: none;">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg x-show="showPasswordConfirm" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display: none;">
                        <path d="M3 3l18 18" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.42-4.42" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.88 5.09A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.05 3.82M6.22 6.22C3.73 8.07 2 12 2 12s3.5 7 10 7a9.8 9.8 0 0 0 4.1-.88" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
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
