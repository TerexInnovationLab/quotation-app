@php
    $primaryPalette = [
        'indigo' => ['base' => '#465FFF', 'hover' => '#3d54e6'],
        'emerald' => ['base' => '#10B981', 'hover' => '#059669'],
        'rose' => ['base' => '#F43F5E', 'hover' => '#E11D48'],
        'amber' => ['base' => '#F59E0B', 'hover' => '#D97706'],
        'sky' => ['base' => '#0EA5E9', 'hover' => '#0284C7'],
        'slate' => ['base' => '#475569', 'hover' => '#334155'],
    ];
    $primary = $primaryPalette[$primaryColor ?? 'indigo'] ?? $primaryPalette['indigo'];
    $flashToasts = [];
    if (session('success')) {
        $flashToasts[] = ['message' => session('success'), 'type' => 'success'];
    }
    if (session('error')) {
        $flashToasts[] = ['message' => session('error'), 'type' => 'error'];
    }
    if (session('warning')) {
        $flashToasts[] = ['message' => session('warning'), 'type' => 'warning'];
    }
    if (session('info')) {
        $flashToasts[] = ['message' => session('info'), 'type' => 'info'];
    }
@endphp
<!doctype html>
<html lang="en" class="{{ ($appearance ?? 'system') === 'dark' ? 'dark' : '' }}" style="--app-primary: {{ $primary['base'] }}; --app-primary-hover: {{ $primary['hover'] }};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            const appearance = @json($appearance ?? 'system');
            const root = document.documentElement;

            if (appearance === 'dark') {
                root.classList.add('dark');
                return;
            }

            if (appearance === 'light') {
                root.classList.remove('dark');
                return;
            }

            const media = window.matchMedia('(prefers-color-scheme: dark)');
            const sync = function () {
                root.classList.toggle('dark', media.matches);
            };

            sync();

            if (typeof media.addEventListener === 'function') {
                media.addEventListener('change', sync);
            } else if (typeof media.addListener === 'function') {
                media.addListener(sync);
            }
        })();
    </script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body.app-shell {
            font-family: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
        }

        .app-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at top left, rgba(99, 102, 241, 0.16), transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.18), transparent 45%),
                radial-gradient(circle at 30% 80%, rgba(16, 185, 129, 0.12), transparent 45%);
            opacity: 0.8;
        }

        .dark .app-bg::before {
            background-image: radial-gradient(circle at top left, rgba(99, 102, 241, 0.18), transparent 45%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.15), transparent 50%),
                radial-gradient(circle at 30% 80%, rgba(16, 185, 129, 0.12), transparent 55%);
            opacity: 0.55;
        }

        .app-grid {
            background-image: linear-gradient(rgba(148, 163, 184, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.1) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(circle at 30% 20%, black 0%, transparent 70%);
        }

        .dark .app-grid {
            background-image: linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }

        @keyframes rise {
            0% { opacity: 0; transform: translateY(12px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .app-entrance {
            animation: rise 0.6s ease;
        }

        @media (prefers-reduced-motion: reduce) {
            .app-entrance {
                animation: none;
            }
        }

        .app-surface div.bg-white.border.border-slate-200.rounded-2xl,
        .app-surface div.bg-white.border.border-slate-200.rounded-xl,
        .app-surface div.bg-white.border.border-slate-200.rounded-3xl {
            background: rgba(255, 255, 255, 0.86);
            border-color: rgba(148, 163, 184, 0.45);
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
        }

        .dark .app-surface div.bg-white.border.border-slate-200.rounded-2xl,
        .dark .app-surface div.bg-white.border.border-slate-200.rounded-xl,
        .dark .app-surface div.bg-white.border.border-slate-200.rounded-3xl {
            background: rgba(15, 23, 42, 0.78);
            border-color: rgba(51, 65, 85, 0.8);
            box-shadow: 0 18px 32px rgba(2, 6, 23, 0.5);
        }

        .app-shell input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="color"]):not([type="range"]),
        .app-shell select,
        .app-shell textarea {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .app-shell input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="color"]):not([type="range"]):focus,
        .app-shell select:focus,
        .app-shell textarea:focus {
            box-shadow: 0 0 0 4px rgba(70, 95, 255, 0.15);
            border-color: var(--app-primary);
        }

        .dark .app-shell input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="color"]):not([type="range"]):focus,
        .dark .app-shell select:focus,
        .dark .app-shell textarea:focus {
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.2);
        }

        .app-action-bar a,
        .app-action-bar button {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .app-action-bar a:hover,
        .app-action-bar button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.12);
        }
    </style>
</head>
<body class="app-shell bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 relative">
<div class="app-bg pointer-events-none fixed inset-0 -z-10">
    <div class="absolute inset-0 app-grid opacity-70 dark:opacity-50"></div>
    <div class="absolute -top-24 -right-12 h-72 w-72 rounded-full bg-indigo-400/20 blur-3xl animate-[float-slow_12s_ease-in-out_infinite]"></div>
    <div class="absolute top-1/3 -left-20 h-80 w-80 rounded-full bg-sky-400/15 blur-3xl animate-[float-slow_16s_ease-in-out_infinite]"></div>
    <div class="absolute bottom-10 right-12 h-64 w-64 rounded-full bg-emerald-400/15 blur-3xl animate-[float-slow_18s_ease-in-out_infinite]"></div>
</div>
<div class="h-screen flex overflow-hidden relative"
     x-data="{
        sidebarExpanded: true,
        showLogoutConfirm: false,
        init() {
            const saved = localStorage.getItem('sidebarExpanded');
            if (saved !== null) {
                this.sidebarExpanded = saved === 'true';
            }
            this.$watch('sidebarExpanded', (value) => {
                localStorage.setItem('sidebarExpanded', value ? 'true' : 'false');
            });
        }
     }">

    {{-- Sidebar --}}
    <aside class="bg-white/85 dark:bg-slate-900/85 backdrop-blur border-r border-slate-200/70 dark:border-slate-800 hidden lg:flex lg:flex-col h-screen sticky top-0 shrink-0 transition-all duration-200 overflow-hidden shadow-[0_12px_30px_rgba(15,23,42,0.08)] dark:shadow-[0_12px_30px_rgba(0,0,0,0.4)]"
           :class="sidebarExpanded ? 'w-72' : 'w-20'">
        <div class="h-16 px-5 flex items-center gap-3">
            <div x-show="sidebarExpanded" class="h-9 w-9 shrink-0 rounded-xl bg-[var(--app-primary)] text-white grid place-items-center font-semibold shadow-lg shadow-[var(--app-primary)]/30">
                A
            </div>
            <div class="leading-tight" x-show="sidebarExpanded">
                <div class="font-semibold text-slate-900 dark:text-white">AccountYanga</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400">Sales Workspace</div>
            </div>
            <button type="button"
                    @click="sidebarExpanded = !sidebarExpanded"
                    :class="sidebarExpanded ? 'ml-auto' : 'mx-auto'"
                    class="h-8 w-8 rounded-lg bg-[var(--app-primary)] text-white grid place-items-center shadow-md shadow-[var(--app-primary)]/30"
                    aria-label="Toggle sidebar">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto">

            <div class="mt-3 space-y-1">
                @php
                    $links = [
                        ['label' => 'Dashboard', 'route' => 'sales.dashboard', 'active' => 'sales.dashboard', 'icon' => 'M4 10.5h7V4H4v6.5zM13 20h7v-9h-7v9zM13 4h7v5h-7V4zM4 13h7v7H4v-7z'],
                        ['label' => 'Quotations', 'route' => 'sales.quotations.index', 'active' => 'sales.quotations.*', 'icon' => 'M7 7h10M7 11h10M7 15h6'],
                        ['label' => 'Invoices', 'route' => 'sales.invoices.index', 'active' => 'sales.invoices.*', 'icon' => 'M6 3h8l4 4v14H6zM14 3v4h4M9 11h6M9 15h6'],
                        ['label' => 'Receipts', 'route' => 'sales.receipts.index', 'active' => 'sales.receipts.*', 'icon' => 'M5 4h14v16H5zM7 8h10M7 12h10M7 16h6'],
                        ['label' => 'Letters', 'route' => 'sales.letters.index', 'active' => 'sales.letters.*', 'icon' => 'M4 6h16v12H4zM4 6l8 6 8-6'],
                        // ['label' => 'Payments Received', 'route' => 'sales.payments.index', 'active' => 'sales.payments.*', 'icon' => 'M3 7h18M5 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7M12 4v8m0 0-3-3m3 3 3-3'],
                        ['label' => 'Clients / Customers', 'route' => 'sales.clients.index', 'active' => 'sales.clients.*', 'icon' => 'M16 11c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4zM8 11c1.657 0 3-1.79 3-4S9.657 3 8 3 5 4.79 5 7s1.343 4 3 4zM8 13c-2.761 0-5 1.79-5 4v1h10v-1c0-2.21-2.239-4-5-4zM16 13c-.602 0-1.18.086-1.724.245 1.623.93 2.724 2.43 2.724 4.255v.5H21v-.5c0-2.21-2.239-4-5-4z'],
                        ['label' => 'Products / Services', 'route' => 'sales.products.index', 'active' => 'sales.products.*', 'icon' => 'M4 7h16M7 3h10a1 1 0 0 1 1 1v3H6V4a1 1 0 0 1 1-1zM6 11h12v9a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-9zM10 15h4'],
                        ['label' => 'Settings', 'route' => 'sales.settings.index', 'active' => 'sales.settings.*', 'icon' => 'M10.325 4.317a1 1 0 0 1 1.35-.936l.144.054 1.21.55a1 1 0 0 0 .82 0l1.21-.55a1 1 0 0 1 1.35.936l.09 1.325a1 1 0 0 0 .518.819l1.125.648a1 1 0 0 1 .364 1.364l-.628 1.17a1 1 0 0 0 0 .94l.628 1.17a1 1 0 0 1-.364 1.364l-1.125.648a1 1 0 0 0-.518.819l-.09 1.325a1 1 0 0 1-1.35.936l-1.21-.55a1 1 0 0 0-.82 0l-1.21.55a1 1 0 0 1-1.35-.936l-.09-1.325a1 1 0 0 0-.518-.819l-1.125-.648a1 1 0 0 1-.364-1.364l.628-1.17a1 1 0 0 0 0-.94l-.628-1.17a1 1 0 0 1 .364-1.364l1.125-.648a1 1 0 0 0 .518-.819l.09-1.325zM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'],
                    ];
                @endphp

                @foreach($links as $link)
                    @php
                        $isActive = request()->routeIs($link['active'] ?? $link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl border whitespace-nowrap transition
                              {{ $isActive ? 'bg-[var(--app-primary)] text-white border-[var(--app-primary)] shadow-lg shadow-[var(--app-primary)]/25' : 'bg-white/70 text-slate-700 border-transparent hover:bg-white hover:text-slate-900 dark:bg-slate-900/60 dark:text-slate-200 dark:hover:bg-slate-800/80 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-80 transition group-hover:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="{{ $link['icon'] }}" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium" x-show="sidebarExpanded">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="shrink-0 p-4 border-t border-slate-200/70 dark:border-slate-800/80">
            <form method="POST" action="{{ route('logout') }}" x-ref="logoutForm" @submit.prevent="showLogoutConfirm = true">
                @csrf
                <button type="submit"
                        class="w-full rounded-xl border border-[var(--app-primary)] bg-white/80 dark:bg-slate-900/70 px-3 py-2 text-sm font-medium text-[var(--app-primary)] hover:bg-[var(--app-primary)]/10 dark:hover:bg-[var(--app-primary)]/20 flex items-center justify-center gap-2 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 opacity-80 transition group-hover:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5m0 0-5-5m5 5H9" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-show="sidebarExpanded">Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-0">
        {{-- Topbar --}}
        <header class="h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200/70 dark:border-slate-800/80 px-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" class="lg:hidden h-10 w-10 rounded-xl border border-slate-200/70 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/80 grid place-items-center text-slate-700 dark:text-slate-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div>
                    <div class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">@yield('breadcrumb')</div>
                    <div class="font-semibold text-slate-900 dark:text-white">@yield('page_title', 'Dashboard')</div>
                </div>
            </div>

            <div class="flex items-center gap-2 app-action-bar" x-data="{ userMenuOpen: false }">
                @yield('primary_action')
                @php $user = auth()->user(); @endphp
                <div class="relative ml-2">
                    <button type="button"
                            @click="userMenuOpen = !userMenuOpen"
                            class="h-9 w-9 rounded-xl bg-gradient-to-br from-white to-slate-100 dark:from-slate-800 dark:to-slate-900 border border-slate-200/70 dark:border-slate-700/70 grid place-items-center text-slate-700 dark:text-slate-200 shadow-sm hover:shadow-md transition"
                            aria-label="Open user menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M20 21a8 8 0 1 0-16 0" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div x-show="userMenuOpen"
                         @click.outside="userMenuOpen = false"
                         x-transition.opacity
                         class="absolute right-0 mt-3 w-64 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-slate-900/95 shadow-xl p-4 z-20"
                         style="display: none;">
                        <div class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Signed in as</div>
                        <div class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $user?->name ?? $user?->email ?? 'User' }}
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-300">
                            {{ $user?->email ?? 'user@example.com' }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 app-surface app-entrance">
            @yield('content')
        </main>
    </div>

    <div x-data="toastStack()"
         x-init="init(@js($flashToasts))"
         class="fixed top-20 right-4 sm:right-6 z-[60] space-y-3 pointer-events-none"
         aria-live="polite">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition.opacity
                 x-transition.duration.200ms
                 class="pointer-events-auto w-[22rem] max-w-[calc(100vw-2rem)] rounded-2xl border px-4 py-3 shadow-xl backdrop-blur"
                 :class="toast.tone.body"
                 role="status">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 h-8 w-8 rounded-full grid place-items-center" :class="toast.tone.iconBg">
                        <svg class="h-4 w-4" :class="toast.tone.icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path x-show="toast.type === 'success'" d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="toast.type === 'error'" d="M6 6l12 12M6 18L18 6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="toast.type === 'warning'" d="M12 3l9 16H3l9-16z" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="toast.type === 'warning'" d="M12 9v4m0 4h.01" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle x-show="toast.type === 'info'" cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="toast.type === 'info'" d="M12 10v6m0-8h.01" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs uppercase tracking-[0.18em] opacity-70" x-text="toast.label"></div>
                        <div class="text-sm font-semibold leading-relaxed" x-text="toast.title ? toast.title : toast.message"></div>
                        <div x-show="toast.title" class="mt-1 text-sm text-slate-600 dark:text-slate-300" x-text="toast.message"></div>
                    </div>
                    <button type="button"
                            class="mt-1 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200"
                            @click="dismiss(toast.id)"
                            aria-label="Dismiss notification">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M6 6l12 12M6 18L18 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <div x-show="showLogoutConfirm"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4"
         style="display: none;">
        <div @click.outside="showLogoutConfirm = false" class="w-full max-w-md rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Confirm logout</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Are you sure you want to log out of your account?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        @click="showLogoutConfirm = false"
                        class="rounded-lg border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    Cancel
                </button>
                <button type="button"
                        @click="$refs.logoutForm.submit()"
                        class="rounded-lg bg-[var(--app-primary)] px-4 py-2 text-sm font-medium text-white hover:bg-[var(--app-primary-hover)]">
                    Logout
                </button>
            </div>
        </div>
    </div>

</div>
</body>
</html>
