<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Sales')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-72 bg-white border-r border-slate-200 hidden lg:flex lg:flex-col">
        <div class="h-16 px-5 flex items-center gap-3 ">
            <div class="h-9 w-9 rounded-xl bg-[#465FFF] text-white grid place-items-center font-semibold">
                A
            </div>
            <div class="leading-tight">
                <div class="font-semibold">AccountYanga</div>
                <div class="text-xs text-slate-500">Sales Module</div>
            </div>
        </div>

        <nav class="px-3 py-4 overflow-y-auto">
            <div class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                Sales
            </div>

            <div class="mt-3 space-y-1">
                @php
                    $links = [
                        ['label' => 'Dashboard', 'route' => 'sales.dashboard', 'icon' => 'M4 10.5h7V4H4v6.5zM13 20h7v-9h-7v9zM13 4h7v5h-7V4zM4 13h7v7H4v-7z'],
                        ['label' => 'Quotations', 'route' => 'sales.quotations.index', 'icon' => 'M7 7h10M7 11h10M7 15h6'],
                        ['label' => 'Invoices', 'route' => 'sales.invoices.index', 'icon' => 'M6 3h8l4 4v14H6zM14 3v4h4M9 11h6M9 15h6'],
                        ['label' => 'Payments Received', 'route' => 'sales.payments.index', 'icon' => 'M3 7h18M5 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7M12 4v8m0 0-3-3m3 3 3-3'],
                    ];
                @endphp

                @foreach($links as $link)
                    @php
                        $isActive = request()->routeIs($link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl border
                              {{ $isActive ? 'bg-[#465FFF] text-white border-[#465FFF]' : 'bg-white text-slate-700 border-transparent hover:bg-slate-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="{{ $link['icon'] }}" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="mt-auto p-4 border-t border-slate-200 text-xs text-slate-500">
            UI only • No DB yet
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
                    <div class="text-sm text-slate-500">@yield('breadcrumb', 'Sales')</div>
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
