@extends('layout.app')

@section('title','Sales Dashboard')
@section('breadcrumb','Sales')
@section('page_title','Dashboard')

@section('primary_action')

@endsection

@section('content')
@php
    $fmt = fn($n) => number_format($n, 0);
@endphp

<div class="space-y-4"
     x-data="salesDashboard({
        labels12: @js($chart['labels12']),
        sales12:  @js($chart['sales12']),
        
        labels6:  @js($chart['labels6']),
        sales6:   @js($chart['sales6']),
     })"
     x-init="init()"
>
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-xs text-slate-500">Total Sales</div>
            <div class="mt-2 text-2xl font-semibold">MWK {{ $fmt($kpis['total_sales']) }}</div>
            <div class="mt-2 text-xs text-slate-500">Sales across invoices & orders</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-xs text-slate-500">Paid</div>
            <div class="mt-2 text-2xl font-semibold">MWK {{ $fmt($kpis['total_paid']) }}</div>
            <div class="mt-2 text-xs text-slate-500">Collected payments</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-xs text-slate-500">Outstanding</div>
            <div class="mt-2 text-2xl font-semibold">MWK {{ $fmt($kpis['total_outstanding']) }}</div>
            <div class="mt-2 text-xs text-slate-500">Unpaid + overdue invoices</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-xs text-slate-500">Avg Days to Pay</div>
            <div class="mt-2 text-2xl font-semibold">{{ $kpis['avg_days_to_pay'] }} days</div>
            <div class="mt-2 text-xs text-slate-500">Estimate for receivables cycle</div>
        </div>
    </div>

    {{-- Chart + Summary --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Chart --}}
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold">Total Sales Trend</div>
                    <div class="text-xs text-slate-500">Interactive graph</div>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="setRange('6')"
                            :class="range === '6' ? 'bg-[#465FFF] text-white' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm rounded-xl border">
                        Last 6 months
                    </button>
                    <button @click="setRange('12')"
                            :class="range === '12' ? 'bg-[#465FFF] text-white' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm rounded-xl border">
                        Last 12 months
                    </button>
                </div>
            </div>

                <div class="mt-4 h-64">
                    <canvas id="salesChart" class="w-full"></canvas>
                </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="text-xs text-slate-500">Best Month</div>

                <div class="font-semibold"
                    x-text="bestMonthLabel + ' • MWK ' + format(bestMonthValue)">
                </div>

                </div>

                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="text-xs text-slate-500">Average / Month</div>
                    <div class="font-semibold"
                        x-text="'MWK ' + format(avg)">
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="text-xs text-slate-500">Latest Month</div>
                    <div class="font-semibold"
                        x-text="latestLabel + ' • MWK ' + format(latestValue)">
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-sm font-semibold">Sales Summary</div>
            <div class="text-xs text-slate-500">Quick breakdown</div>

            <div class="mt-4 space-y-3 text-sm">
                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <div class="font-medium">Quotations</div>
                        <div class="font-semibold">{{ $summary['quotations']['count'] }}</div>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        Draft: {{ $summary['quotations']['draft'] }},
                        Sent: {{ $summary['quotations']['sent'] }},
                        Accepted: {{ $summary['quotations']['accepted'] }}
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <div class="font-medium">Invoices</div>
                        <div class="font-semibold">{{ $summary['invoices']['count'] }}</div>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        Paid: {{ $summary['invoices']['paid'] }},
                        Unpaid: {{ $summary['invoices']['unpaid'] }},
                        Overdue: {{ $summary['invoices']['overdue'] }}
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <div class="font-medium">Sales Orders</div>
                        <div class="font-semibold">{{ $summary['orders']['count'] }}</div>
                    </div>
                    <div class="mt-2 text-xs text-slate-500">
                        Open: {{ $summary['orders']['open'] }},
                        Closed: {{ $summary['orders']['closed'] }}
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="text-sm font-semibold">Recent Activity</div>
                <div class="mt-3 space-y-2">
                    @foreach($recent as $r)
                        <div class="rounded-xl border border-slate-200 p-3">
                            <div class="flex items-center justify-between">
                                <div class="font-medium">{{ $r['type'] }} • {{ $r['ref'] }}</div>
                                <div class="text-xs text-slate-500">{{ $r['date'] }}</div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">{{ $r['note'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Payments due by customer --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200">
            <div class="text-sm font-semibold">Payments Due by Customer</div>
            <div class="text-xs text-slate-500">Aging summary</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Customer</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600">Current</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600">1–30</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600">31–60</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600">61+</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-600">Total Due</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($dueByCustomer as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $row['customer'] }}</td>
                            <td class="px-4 py-3 text-right">MWK {{ $fmt($row['current']) }}</td>
                            <td class="px-4 py-3 text-right">MWK {{ $fmt($row['days_1_30']) }}</td>
                            <td class="px-4 py-3 text-right">MWK {{ $fmt($row['days_31_60']) }}</td>
                            <td class="px-4 py-3 text-right">MWK {{ $fmt($row['days_61_plus']) }}</td>
                            <td class="px-4 py-3 text-right font-semibold">MWK {{ $fmt($row['total_due']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-slate-200 bg-white text-xs text-slate-500">
            Tip: later we can link rows to customer statements & invoice drill-downs.
        </div>
    </div>
</div>


<script>
function salesDashboard(payload) {
    return {
        range: '12',
        chart: null,

        bestMonthLabel: '',
        bestMonthValue: 0,
        latestLabel: '',
        latestValue: 0,
        avg: 0,

        init() {
            // default = 12 months
            this.renderChart(payload.labels12, payload.sales12);
            this.computeStats(payload.labels12, payload.sales12);
        },

        setRange(r) {
            this.range = r;
            const labels = r === '6' ? payload.labels6 : payload.labels12;
            const sales  = r === '6' ? payload.sales6  : payload.sales12;

            this.updateChart(labels, sales);
            this.computeStats(labels, sales);
        },

        renderChart(labels, data) {
            const ctx = document.getElementById('salesChart');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total Sales',
                        data,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ' MWK ' + this.format(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: (v) => 'MWK ' + this.format(v)
                            },
                            grid: { drawBorder: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        },

        updateChart(labels, data) {
            this.chart.data.labels = labels;
            this.chart.data.datasets[0].data = data;
            this.chart.update();
        },

        computeStats(labels, data) {
            if (!data?.length) return;

            const maxVal = Math.max(...data);
            const maxIdx = data.indexOf(maxVal);
            this.bestMonthLabel = labels[maxIdx];
            this.bestMonthValue = maxVal;

            this.latestLabel = labels[labels.length - 1];
            this.latestValue = data[data.length - 1];

            const sum = data.reduce((a,b) => a + b, 0);
            this.avg = Math.round(sum / data.length);
        },

        format(n) {
            try { return Number(n).toLocaleString(); } catch(e) { return n; }
        }
    }
}
</script>
@endsection
