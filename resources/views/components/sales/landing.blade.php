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
<html lang="en" class="scroll-smooth {{ ($appearance ?? 'system') === 'dark' ? 'dark' : '' }}" data-appearance="{{ $appearance ?? 'system' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Quotation & Invoicing System</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

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

    <style>
        html, body {
            background-color: #f8fafc;
        }
        :root { --brand: {{ $primary['base'] }}; }
        .brand-bg { background-color: var(--brand); }
        .brand-text { color: var(--brand); }
        .brand-ring { --tw-ring-color: var(--brand); }
        .brand-border { border-color: var(--brand); }
        .brand-shadow { box-shadow: 0 20px 40px rgba(70, 95, 255, 0.18); }
    </style>

    <style>
  :root { --brand: {{ $primary['base'] }}; }
  .brand-bg { background-color: var(--brand); }
  .brand-text { color: var(--brand); }
  .brand-ring { --tw-ring-color: var(--brand); }
  .brand-border { border-color: var(--brand); }
  .brand-shadow { box-shadow: 0 20px 40px rgba(70, 95, 255, 0.18); }
  .top-link:hover { color: var(--brand); }
  .card-hover {
    transition: transform .28s cubic-bezier(.22,.61,.36,1), box-shadow .28s ease, border-color .28s ease;
  }
  .card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    border-color: rgba(70,95,255,.35);
  }

  /* Scroll reveal animations */
  [data-reveal]{
    opacity: 1;
    transform: translateY(0);
    transition: opacity .7s ease, transform .7s ease;
    will-change: opacity, transform;
  }
  [data-reveal].will-reveal{
    opacity: 0;
    transform: translateY(14px);
  }
  [data-reveal].is-visible{
    opacity: 1;
    transform: translateY(0);
  }

  /* Small stagger utility */
  [data-reveal][data-delay="100"]{ transition-delay: .10s; }
  [data-reveal][data-delay="200"]{ transition-delay: .20s; }
  [data-reveal][data-delay="300"]{ transition-delay: .30s; }
  [data-reveal][data-delay="400"]{ transition-delay: .40s; }

  /* Respect user preference */
  @media (prefers-reduced-motion: reduce){
    [data-reveal]{
      opacity: 1 !important;
      transform: none !important;
      transition: none !important;
    }
    html { scroll-behavior: auto; }
  }
    </style>

    <style>
        .dark body.landing-page {
            background-color: #0b1220;
            color: #e2e8f0;
        }

        .dark .landing-page .bg-white { background-color: rgba(15, 23, 42, 0.92); }
        .dark .landing-page .bg-slate-50 { background-color: #0f172a; }
        .dark .landing-page .border-slate-200 { border-color: rgba(51, 65, 85, 0.85); }
        .dark .landing-page .text-slate-900 { color: #f1f5f9; }
        .dark .landing-page .text-slate-700 { color: #e2e8f0; }
        .dark .landing-page .text-slate-600 { color: #cbd5f5; }
        .dark .landing-page .text-slate-500 { color: #94a3b8; }

        .dark .landing-page .hover\:bg-slate-50:hover { background-color: rgba(30, 41, 59, 0.6); }
        .dark .landing-page .hover\:text-slate-900:hover { color: #f8fafc; }

        .dark .landing-page .card-hover:hover {
            box-shadow: 0 18px 34px rgba(2, 6, 23, 0.6);
            border-color: rgba(99, 102, 241, 0.45);
        }
    </style>
</head>

<body class="landing-page bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    {{-- Top Nav --}}
    <header
        class="fixed inset-x-0 top-0 z-50 bg-white/5 backdrop-blur-md border-b border-slate-200"
        style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999;"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl brand-bg text-white grid place-items-center font-semibold">
                    A
                </div>
                <div class="leading-tight">
                    <div class="text-xs text-slate-500">Sales • Invoicing • Receipts</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm text-slate-600">
                <a href="#features" class="top-link">Features</a>
                <a href="#how" class="top-link">How it works</a>
                <a href="#security" class="top-link">Security</a>
                <a href="#faq" class="top-link">FAQ</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ url('/login') }}"
                   class="top-link hidden sm:inline-flex px-4 py-2 text-sm rounded-xl border border-slate-200 hover:bg-slate-50">
                    Login
                </a>
                <a href="{{ url('/login') }}"
                   class="inline-flex px-4 py-2 text-sm rounded-xl text-white brand-bg hover:opacity-95 brand-shadow">
                    Get Started
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-24">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full opacity-15 brand-bg blur-3xl"></div>
            <div class="absolute top-24 -left-24 h-72 w-72 rounded-full opacity-10 brand-bg blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div data-reveal>

                    <h1 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tight">
                        Create Quotations, Invoices & Receipts — fast, clean, professional.
                    </h1>

                    <p class="mt-4 text-slate-600 text-lg leading-relaxed">
                        A simple sales system for teams that need structure: quotations, sales orders, invoices,
                        payments received, dashboards, and customer-ready documents — all in one place.
                    </p>

                    <div class="mt-7 flex flex-col sm:flex-row gap-3" data-reveal data-delay="200">
                        <a href="{{ url('/register') }}"
                           class="inline-flex justify-center px-5 py-3 rounded-2xl text-white brand-bg hover:opacity-95 brand-shadow">
                            Sign Up
                        </a>

                        <a href="/sales/quotations"
                           class="inline-flex justify-center px-5 py-3 rounded-2xl border border-slate-200 hover:bg-slate-50">
                            Get Started
                        </a>
                    </div>

                    <div class="mt-7 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div class="card-hover rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs text-slate-500">Speed</div>
                            <div class="font-semibold mt-1">Fewer clicks</div>
                        </div>
                        <div class="card-hover rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs text-slate-500">Clarity</div>
                            <div class="font-semibold mt-1">Clean UI</div>
                        </div>
                        <div class="card-hover rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs text-slate-500">Control</div>
                            <div class="font-semibold mt-1">Track payments</div>
                        </div>
                    </div>
                </div>

                {{-- Mock preview --}}
                <div class="relative" data-reveal data-delay="200">
                    <div class="rounded-3xl border border-slate-200 bg-white brand-shadow overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-rose-400"></div>
                                <div class="h-3 w-3 rounded-full bg-amber-400"></div>
                                <div class="h-3 w-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <div class="text-xs text-slate-500">Sales Dashboard Preview</div>
                        </div>

                        <div class="p-5">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="card-hover rounded-2xl border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500">Total Sales</div>
                                    <div class="font-semibold mt-2">MWK 8,250,000</div>
                                </div>
                                <div class="card-hover rounded-2xl border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500">Outstanding</div>
                                    <div class="font-semibold mt-2">MWK 3,130,000</div>
                                </div>
                                <div class="card-hover rounded-2xl border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500">Payments</div>
                                    <div class="font-semibold mt-2">MWK 5,120,000</div>
                                </div>
                            </div>

                            <div class="mt-5 rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold">Sales Trend</div>
                                    <div class="text-xs brand-text font-medium">Interactive</div>
                                </div>
                                <div class="mt-3 h-32 rounded-xl bg-slate-50 border border-slate-100 relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-70"
                                         style="background: linear-gradient(90deg, rgba(70,95,255,.25), rgba(70,95,255,0));"></div>
                                    <div class="absolute bottom-0 left-0 right-0 h-16"
                                         style="background: linear-gradient(180deg, rgba(70,95,255,.25), rgba(70,95,255,.05));"></div>
                                    <div class="absolute bottom-6 left-6 right-6 h-14 border-l-2 border-b-2 border-slate-200"></div>
                                    <div class="absolute bottom-10 left-10 right-8 h-0.5 bg-slate-200"></div>
                                    <div class="absolute bottom-8 left-10 w-2 h-2 rounded-full brand-bg"></div>
                                    <div class="absolute bottom-12 left-20 w-2 h-2 rounded-full brand-bg"></div>
                                    <div class="absolute bottom-10 left-32 w-2 h-2 rounded-full brand-bg"></div>
                                    <div class="absolute bottom-16 left-44 w-2 h-2 rounded-full brand-bg"></div>
                                    <div class="absolute bottom-14 left-56 w-2 h-2 rounded-full brand-bg"></div>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="card-hover rounded-2xl border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500">Quotations</div>
                                    <div class="font-semibold mt-2">14</div>
                                </div>
                                <div class="card-hover rounded-2xl border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500">Invoices</div>
                                    <div class="font-semibold mt-2">22</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -z-10 -bottom-10 -right-10 h-40 w-40 rounded-full opacity-15 brand-bg blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Logos / trust strip --}}
    <section >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div class="text-center text-xs text-slate-500">Built for teams that need reliable sales tracking</div>
            <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm text-slate-500">
                <div class="card-hover rounded-2xl border border-slate-200 bg-white p-4 text-center">SMEs</div>
                <div class=" card-hover rounded-2xl border border-slate-200 bg-white p-4 text-center">Universities</div>
                <div class="card-hover rounded-2xl border border-slate-200 bg-white p-4 text-center">Consultancies</div>
                <div class="card-hover rounded-2xl border border-slate-200 bg-white p-4 text-center">Projects</div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
        <div class="max-w-2xl" data-reveal>
            <h2 class="text-3xl font-semibold tracking-tight">Everything your sales process needs</h2>
            <p class="mt-3 text-slate-600">
                Create documents, track collections, and keep customers informed — with a clean UI that your team will actually use.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $cards = [
                    ['t'=>'Quotations', 'd'=>'Create professional quotations with line items, notes, discounts, and currency.'],
                    ['t'=>'Invoices', 'd'=>'Issue invoices quickly and track payment status at a glance.'],
                    ['t'=>'Payments Received', 'd'=>'Record receipts, reconcile collections, and keep customer balances accurate.'],
                    ['t'=>'Sales Orders', 'd'=>'Capture commitments and convert orders into invoices smoothly.'],
                    ['t'=>'Dashboard & Trends', 'd'=>'Interactive graphs, outstanding balances, and customer aging summaries.'],
                    ['t'=>'Simple, scalable', 'd'=>'Built on the latest technologies so you can expand into full accounting later.'],
                ];
            @endphp

            @foreach($cards as $i => $c)
                <div class=" rounded-3xl border border-slate-200 p-6 hover:shadow-sm transition" data-reveal data-delay="{{ [0,100,200,300,400,100][$i % 6] }}">
                    <div class="h-10 w-10 rounded-2xl brand-bg/10 grid place-items-center">
                        <div class="h-2.5 w-2.5 rounded-full brand-bg"></div>
                    </div>
                    <div class="mt-4 font-semibold">{{ $c['t'] }}</div>
                    <div class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $c['d'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section id="how" class="bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div>
                    <h2 class="text-3xl font-semibold tracking-tight">A simple flow your team can follow</h2>
                    <p class="mt-3 text-slate-600">
                        Keep everything consistent — from first quotation to final payment.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-3xl bg-white border border-slate-200 p-6 hover:shadow-sm hover:-translate-y-0.5 transition-transform duration-200" data-reveal>
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-2xl text-white grid place-items-center text-sm font-semibold border border-slate-200">1</div>
                                <div>
                                    <div class="font-semibold">Create a quotation</div>
                                    <div class="mt-1 text-sm text-slate-600">Line items, notes, discounts, currency, terms.</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white border border-slate-200 p-6 hover:shadow-sm hover:-translate-y-0.5 transition-transform duration-200" data-reveal>
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-2xl text-white grid place-items-center text-sm font-semibold border border-slate-200">2</div>
                                <div>
                                    <div class="font-semibold">Convert to invoice</div>
                                    <div class="mt-1 text-sm text-slate-600">Track due dates, status, and outstanding balances.</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white border border-slate-200 p-6 hover:shadow-sm hover:-translate-y-0.5 transition-transform duration-200" data-reveal>
                            <div class="flex items-start gap-3">
                                <div class="h-8 w-8 rounded-2xl text-white grid place-items-center text-sm font-semibold border border-slate-200">3</div>
                                <div>
                                    <div class="font-semibold">Record payments</div>
                                    <div class="mt-1 text-sm text-slate-600">Apply payments to invoices, manage credits, confirm receipts.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white border border-slate-200 p-7">
                    <div class="text-sm font-semibold">What you gain</div>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex gap-3">
                            <div class="mt-1 h-2.5 w-2.5 rounded-full brand-bg"></div>
                            Faster document creation, fewer mistakes.
                        </div>
                        <div class="flex gap-3">
                            <div class="mt-1 h-2.5 w-2.5 rounded-full brand-bg"></div>
                            Visibility on what’s paid, due, and overdue.
                        </div>
                        <div class="flex gap-3">
                            <div class="mt-1 h-2.5 w-2.5 rounded-full brand-bg"></div>
                            A single place for sales records and customer history.
                        </div>
                        <div class="flex gap-3">
                            <div class="mt-1 h-2.5 w-2.5 rounded-full brand-bg"></div>
                            Ready for future features (PDFs, email receipts, integrations).
                        </div>
                    </div>

                    <div class="mt-7 flex flex-col sm:flex-row gap-3">

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Security / Compliance --}}
    <section id="security" class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-3xl font-semibold tracking-tight">Built for control and accountability</h2>
                <p class="mt-3 text-slate-600">
                    Whether you’re a small team or a growing organization, you can evolve this system into a full
                    sales and finance workflow.
                </p>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="card-hover rounded-3xl border border-slate-200 p-6">
                        <div class="text-sm font-semibold">Role-based access</div>
                        <div class="mt-2 text-sm text-slate-600">Limit actions by staff role (e.g., view-only for executives).</div>
                    </div>
                    <div class="card-hover rounded-3xl border border-slate-200 p-6">
                        <div class="text-sm font-semibold">Audit-friendly</div>
                        <div class="mt-2 text-sm text-slate-600">Track edits, approvals, and payment records.</div>
                    </div>
                    <div class="card-hover rounded-3xl border border-slate-200 p-6">
                        <div class="text-sm font-semibold">Data integrity</div>
                        <div class="mt-2 text-sm text-slate-600">Standardize invoice numbering, due dates, and currency rules.</div>
                    </div>
                    <div class="card-hover rounded-3xl border border-slate-200 p-6">
                        <div class="text-sm font-semibold">Integrations-ready</div>
                        <div class="mt-2 text-sm text-slate-600">Later: email PDFs, WhatsApp receipts, payment gateways.</div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-7">
                <div class="text-sm font-semibold">Dashboard highlights</div>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="card-hover flex items-center justify-between rounded-2xl bg-white border border-slate-200 p-4">
                        <div>Outstanding by customer</div>
                    </div>
                    <div class="card-hover flex items-center justify-between rounded-2xl bg-white border border-slate-200 p-4">
                        <div>Sales trend over time</div>
                    </div>
                    <div class="card-hover flex items-center justify-between rounded-2xl bg-white border border-slate-200 p-4">
                        <div>Paid vs unpaid invoices</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <div class="max-w-2xl">
            <h2 class="text-3xl font-semibold tracking-tight">FAQ</h2>
            <p class="mt-3 text-slate-600">Quick answers about the system.</p>
        </div>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4">
            @php
                $faqs = [
                    ['q'=>'Can it generate PDFs?', 'a'=>'Yes, we can add PDF generation later (Blade → PDF) plus email sending and branded templates.'],
                    ['q'=>'Does it support multiple currencies?', 'a'=>'Yes. The quotation form already supports a currency selector and formatting; we can expand to exchange rates later.'],
                    ['q'=>'Can we track partial payments?', 'a'=>'Yes. Payments Received can be designed to apply receipts to one or multiple invoices with partial allocation.'],
                    ['q'=>'Can we customize invoice numbering?', 'a'=>'Yes. We can implement customizable invoice/quotation numbering schemes to fit your needs.'],
                    ['q'=>'Is my data secure?', 'a'=>'Yes. The system is built security best practices, including hashed passwords, role-based access, and data validation.'],
                ];
            @endphp

            @foreach($faqs as $f)
                <div class="card-hover rounded-3xl border border-slate-200 p-6">
                    <div class="font-semibold">{{ $f['q'] }}</div>
                    <div class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $f['a'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 flex flex-col items-center gap-6">
            <div class="text-sm text-slate-600 text-center">
                <div class="mt-1">&copy; {{ date('Y') }} &bull; Designed by Terex Innovation Lab &bull; All rights reserved</div>
            </div>

            <div class="flex items-center gap-4 text-sm text-slate-600">
                <a href="#features" class="hover:text-slate-900">Features</a>
                <a href="#how" class="hover:text-slate-900">How it works</a>
                <a href="{{ url('/login') }}" class="hover:text-slate-900">Login</a>
            </div>
        </div>
    </footer>
<script>
  (function () {
    const els = document.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window) || !els.length) {
      els.forEach(el => el.classList.add('is-visible'));
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove('will-reveal');
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });

    const viewportBottom = window.innerHeight * 0.95;
    els.forEach(el => {
      const rect = el.getBoundingClientRect();
      const inInitialView = rect.top < viewportBottom;

      if (inInitialView) {
        el.classList.add('is-visible');
      } else {
        el.classList.add('will-reveal');
        io.observe(el);
      }
    });
  })();
</script>

</body>
</html>

