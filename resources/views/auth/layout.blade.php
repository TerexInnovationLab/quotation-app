<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body {
            font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,#0f766e_0%,#020617_42%)]"></div>
        <div class="absolute -top-24 -right-16 h-80 w-80 rounded-full bg-amber-400/20 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 h-96 w-96 rounded-full bg-teal-400/20 blur-3xl"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-10 sm:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[1.1fr_1fr]">
                <section class="hidden rounded-3xl border border-white/10 bg-white/5 p-10 backdrop-blur-xl lg:block">
                    <div class="mb-16 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-xs tracking-[0.2em] text-teal-200">
                        TEREX QUOTATION APP
                    </div>
                    <h1 class="text-4xl font-semibold leading-tight text-white">
                        Fast quotes. Clean approvals. Better cash flow.
                    </h1>
                    <p class="mt-5 max-w-md text-sm leading-relaxed text-slate-300">
                        Manage quotations, invoices, clients, and payments in one focused workspace built for speed.
                    </p>
                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-2xl font-bold text-amber-300">2x</div>
                            <p class="mt-2 text-xs text-slate-300">Faster quote-to-invoice handoff</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-2xl font-bold text-teal-300">100%</div>
                            <p class="mt-2 text-xs text-slate-300">Centralized client records</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-white/15 bg-white/95 p-6 text-slate-900 shadow-2xl shadow-slate-900/40 sm:p-8">
                    @yield('content')
                </section>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const messages = @json($errors->all());
                const html = '<ul style="text-align:left;margin:0;padding-left:1rem;">'
                    + messages.map(function (m) { return '<li>' + m + '</li>'; }).join('')
                    + '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Please fix the following',
                    html: html,
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#0d9488'
                });
            });
        </script>
    @endif
</body>
</html>
