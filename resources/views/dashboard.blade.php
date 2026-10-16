@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('styles')
<style>
    #dashCalendar { font-size: .75rem; }
    #dashCalendar a { text-decoration: none; color: inherit; }
    #dashCalendar .fc-toolbar { margin-bottom: .6rem !important; }
    #dashCalendar .fc-toolbar-title { font-size: .95rem; font-weight: 700; }
    #dashCalendar .fc-button { background:#0f766e; border-color:#0f766e; text-transform:capitalize; padding:.2rem .5rem; font-size:.72rem; border-radius:8px; }
    #dashCalendar .fc-button:hover, #dashCalendar .fc-button:focus { background:#115e59; border-color:#115e59; box-shadow:none; }

    /* remove heavy grid borders */
    #dashCalendar .fc-theme-standard td,
    #dashCalendar .fc-theme-standard th,
    #dashCalendar .fc-theme-standard .fc-scrollgrid { border-color: transparent; }

    #dashCalendar .fc-col-header-cell-cushion { font-size:.7rem; font-weight:600; color:#9ca3af; padding:4px 0; text-transform:uppercase; }

    /* center the day number */
    #dashCalendar .fc-daygrid-day-frame { min-height: 0 !important; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2px 0; }
    #dashCalendar .fc-daygrid-day-top { flex-direction:row; justify-content:center; width:100%; }
    #dashCalendar .fc-daygrid-day-number { font-size:.8rem; font-weight:500; color:#374151; padding:0; width:28px; height:28px; display:flex; align-items:center; justify-content:center; border-radius:50%; }
    #dashCalendar .fc-day-other .fc-daygrid-day-number { color:#d1d5db; }

    /* today: round teal circle instead of yellow cell */
    #dashCalendar .fc-day-today { background: transparent !important; }
    #dashCalendar .fc-day-today .fc-daygrid-day-number { background:#0f766e; color:#fff; font-weight:700; }

    /* events: small centered pill under the number */
    #dashCalendar .fc-daygrid-day-events { width:100%; min-height:0 !important; margin:0; }
    #dashCalendar .fc-daygrid-event-harness { margin:0 2px; }
    #dashCalendar .fc-daygrid-event { padding:0 3px; border:0; border-radius:4px; font-size:.58rem; text-align:center; }

    /* hover effect */
    #dashCalendar .fc-daygrid-day:hover .fc-daygrid-day-number { background:#e6f4f2; }
    #dashCalendar .fc-day-today:hover .fc-daygrid-day-number { background:#115e59; }
</style>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}</h4>
        <p class="text-muted mb-0">Your event business at a glance.</p>
    </div>
    <a href="{{ route('events.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Register event</a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Customers', $totalCustomers, 'bi-people', 'blue', 'customers.index'],
        ['Events', $totalEvents, 'bi-calendar-event', 'purple', 'events.index'],
        ['Orders', $totalOrders, 'bi-receipt', 'green', 'orders.index'],
        ['Outstanding', number_format($pendingPayments, 2), 'bi-wallet2', 'amber', 'reports.customer-credit'],
    ] as [$label, $value, $icon, $color, $routeName])
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="stat-card h-100" href="{{ route($routeName) }}">
                <div class="stat-icon {{ $color }}"><i class="bi {{ $icon }}"></i></div>
                <div><p class="stat-label">{{ $label }}</p><p class="stat-value">{{ $value }}</p></div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Revenue and expenses</h6><span class="small text-muted">Last six months</span>
            </div>
            <div class="card-body"><div style="height: 380px"><canvas id="financeChart" aria-label="Revenue and expenses for the last six months"></canvas></div></div>
        </div>
    </div>

    <div class="col-12 col-xl-5 d-flex flex-column gap-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Event calendar</h6>
                <a href="{{ route('calendar') }}" class="small">Full calendar</a>
            </div>
            <div class="card-body p-2"><div id="dashCalendar" aria-label="Events calendar"></div></div>
        </div>

        <div class="card">
            <div class="card-header"><h6>This month</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6"><p class="stat-label">Payments received</p><p class="stat-value mb-0">{{ number_format($monthlyRevenue, 2) }}</p></div>
                    <div class="col-6"><p class="stat-label">Expenses recorded</p><p class="stat-value mb-0">{{ number_format($monthlyExpenses, 2) }}</p></div>
                </div>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-primary btn-sm mt-3">View payments <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center"><h6>Upcoming events</h6><a href="{{ route('events.index') }}" class="small">All events</a></div>
            <div class="table-responsive">
                <table class="table mb-0"><thead><tr><th>Event</th><th>Customer</th><th>Date</th><th>Status</th></tr></thead><tbody>
                    @forelse($upcomingEvents as $event)
                        <tr><td><a href="{{ route('events.show', $event) }}">{{ $event->event_name }}</a><div class="small text-muted">{{ $event->event_no }}</div></td><td>{{ $event->customer?->name }}</td><td>{{ $event->event_date?->format('M j, Y') }}</td><td><span class="badge-status badge-{{ $event->status }}">{{ ucfirst($event->status) }}</span></td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No upcoming events in the next 30 days.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center"><h6>Recent payments</h6><a href="{{ route('payments.index') }}" class="small">All payments</a></div>
            <div class="table-responsive">
                <table class="table mb-0"><thead><tr><th>Customer</th><th>Date</th><th class="text-end">Amount</th></tr></thead><tbody>
                    @forelse($recentPayments as $payment)
                        <tr><td>{{ $payment->customer?->name }}<div class="small text-muted">{{ $payment->payment_no }}</div></td><td>{{ $payment->payment_date?->format('M j') }}</td><td class="text-end">{{ number_format($payment->amount, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const financeData = @json($chartData);
    new Chart(document.getElementById('financeChart'), {
        type: 'line',
        data: {
            labels: financeData.map(item => item.month),
            datasets: [
                { label: 'Revenue', data: financeData.map(item => item.revenue), borderColor: '#0f766e', backgroundColor: 'rgba(15,118,110,.1)', fill: true, tension: .35 },
                { label: 'Expenses', data: financeData.map(item => item.expenses), borderColor: '#e07a3f', backgroundColor: 'rgba(224,122,63,.08)', fill: true, tension: .35 }
            ]
        },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
    });

    document.addEventListener('DOMContentLoaded', () => {
        new FullCalendar.Calendar(document.getElementById('dashCalendar'), {
            initialView: 'dayGridMonth',
            height: 'auto',
            aspectRatio: 2.4,
            dayMaxEvents: 1,
            fixedWeekCount: false,
            events: @json(route('api.calendar.events')),
            headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
            eventClick(info) {
                if (info.event.url) { info.jsEvent.preventDefault(); window.location.href = info.event.url; }
            }
        }).render();
    });
</script>
@endsection