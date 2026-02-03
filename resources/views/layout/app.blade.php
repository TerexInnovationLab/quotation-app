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
@endphp
<!doctype html>
<html lang="en" class="{{ ($appearance ?? 'system') === 'dark' ? 'dark' : '' }}" style="--app-primary: {{ $primary['base'] }}; --app-primary-hover: {{ $primary['hover'] }};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title')</title>
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
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<div class="min-h-screen flex" x-data="{ sidebarExpanded: true }">

    {{-- Sidebar --}}
    <aside class="bg-white border-r border-slate-200 hidden lg:flex lg:flex-col transition-all duration-200 overflow-hidden"
           :class="sidebarExpanded ? 'w-72' : 'w-20'">
        <div class="h-16 px-5 flex items-center gap-3">
            <div class="h-9 w-9 shrink-0 rounded-xl bg-[var(--app-primary)] text-white grid place-items-center font-semibold">
                A
            </div>
            <div class="leading-tight" x-show="sidebarExpanded">
                <div class="font-semibold">AccountYanga</div>
            </div>
            <button type="button"
                    @click="sidebarExpanded = !sidebarExpanded"
                    class="ml-auto h-8 w-8 rounded-lg bg-[#465FFF] text-white grid place-items-center"
                    aria-label="Toggle sidebar">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <nav class="px-3 py-4 overflow-y-auto">

            <div class="mt-3 space-y-1">
                @php
                    $links = [
                        ['label' => 'Dashboard', 'route' => 'sales.dashboard', 'active' => 'sales.dashboard', 'icon' => 'M4 10.5h7V4H4v6.5zM13 20h7v-9h-7v9zM13 4h7v5h-7V4zM4 13h7v7H4v-7z'],
                        ['label' => 'Quotations', 'route' => 'sales.quotations.index', 'active' => 'sales.quotations.*', 'icon' => 'M7 7h10M7 11h10M7 15h6'],
                        ['label' => 'Invoices', 'route' => 'sales.invoices.index', 'active' => 'sales.invoices.*', 'icon' => 'M6 3h8l4 4v14H6zM14 3v4h4M9 11h6M9 15h6'],
                        ['label' => 'Payments Received', 'route' => 'sales.payments.index', 'active' => 'sales.payments.*', 'icon' => 'M3 7h18M5 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7M12 4v8m0 0-3-3m3 3 3-3'],
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
                       class="flex items-center gap-3 px-3 py-2 rounded-xl border
                              {{ $isActive ? 'bg-[var(--app-primary)] text-white border-[var(--app-primary)]' : 'bg-white text-slate-700 border-transparent hover:bg-slate-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="{{ $link['icon'] }}" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium" x-show="sidebarExpanded">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="mt-auto p-4 border-t border-slate-200">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit"
                        class="w-full rounded-xl border border-[var(--app-primary)] bg-white px-4 py-2 text-sm font-medium text-[var(--app-primary)] hover:bg-slate-100">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col">
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="lg:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white grid place-items-center">
                    <span class="text-sm font-semibold">☰</span>
                </div>
                <div>
                    <div class="text-sm text-slate-500">@yield('breadcrumb')</div>
                    <div class="font-semibold text-slate-900">@yield('page_title', 'Dashboard')</div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @yield('primary_action')
                <div class="ml-2 h-9 w-9 rounded-xl bg-slate-100 grid place-items-center text-sm font-semibold">
                    TI
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
