@extends('layouts.app')
@section('title', 'Event Calendar')
@section('page-title', 'Event Calendar')

@section('styles')
<style>
    /* === CALENDAR CONTAINER === */
    .calendar-container-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #eventCalendar .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0.85rem;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    #eventCalendar .fc-toolbar-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.3px;
    }

    #eventCalendar .fc-button-primary {
        background: linear-gradient(135deg, #484646 0%, #3a3838 100%) !important;
        border: 1px solid #3a3838 !important;
        color: #ffffff !important;
        font-weight: 700;
        text-transform: capitalize;
        border-radius: 8px;
        padding: 5px 14px;
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(58, 56, 56, 0.25);
        transition: all 0.2s ease;
    }

    #eventCalendar .fc-button-primary:hover,
    #eventCalendar .fc-button-primary:focus {
        background: linear-gradient(135deg, #3a3838 0%, #252424 100%) !important;
        border-color: #252424 !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(58, 56, 56, 0.35);
    }

    #eventCalendar .fc-button-primary:disabled {
        background: #e2e8f0 !important;
        border-color: #e2e8f0 !important;
        color: #94a3b8 !important;
        box-shadow: none !important;
    }

    #eventCalendar .fc-daygrid-day.fc-day-today {
        background-color: rgba(72, 70, 70, 0.04) !important;
    }

    #eventCalendar .fc-theme-standard td,
    #eventCalendar .fc-theme-standard th {
        border-color: #f1f5f9;
    }

    #eventCalendar .fc-col-header-cell-cushion {
        padding: 8px 2px;
        font-weight: 700;
        color: #64748b;
        font-size: 11px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #eventCalendar .fc-daygrid-day-number {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        padding: 4px 6px;
        text-decoration: none;
        display: inline-block;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    #eventCalendar .fc-daygrid-day:hover .fc-daygrid-day-number {
        background: #f1f5f9;
        color: #0f172a;
    }

    #eventCalendar .fc-day-today .fc-daygrid-day-number {
        background: #3a3838;
        color: #ffffff !important;
    }

    #eventCalendar .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 2px 0 !important;
        box-shadow: none !important;
        display: flex !important;
        justify-content: center !important;
    }

    .day-count-icon-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 14px;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        line-height: 1.2;
    }

    .day-count-icon-badge:hover {
        transform: translateY(-1px) scale(1.08);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    .day-count-icon-badge i {
        font-size: 11px;
    }

    /* === RIGHT SIDE: MONTH EVENTS PANEL === */
    .month-events-panel {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .month-events-header {
        padding: 16px 20px 14px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .month-events-scrollable {
        padding: 16px;
        overflow-y: auto;
        flex: 1;
        max-height: 570px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }

    .month-events-scrollable::-webkit-scrollbar {
        width: 7px;
    }

    .month-events-scrollable::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 10px;
    }

    .month-events-scrollable::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    .month-events-scrollable::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }

    /* Event Card Item */
    .cal-event-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 15px 18px;
        transition: all 0.2s ease;
        position: relative;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        width: 100%;
    }

    .cal-event-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .cal-event-card.highlighted {
        border-color: #3a3838;
        background: #fafafa;
        box-shadow: 0 0 0 3px rgba(58, 56, 56, 0.15);
    }

    .cal-date-chip {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .status-pill.status-pending { background: #fef3c7; color: #b45309; }
    .status-pill.status-confirmed { background: #dbeafe; color: #1d4ed8; }
    .status-pill.status-completed { background: #d1fae5; color: #047857; }
    .status-pill.status-cancelled { background: #fee2e2; color: #b91c1c; }

    .pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-pending .pill-dot { background: #d97706; }
    .status-confirmed .pill-dot { background: #2563eb; }
    .status-completed .pill-dot { background: #059669; }
    .status-cancelled .pill-dot { background: #dc2626; }

    .filter-reset-pill {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .filter-reset-pill:hover {
        background: #fecaca;
    }

    /* Metric Chips */
    .metric-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    @media (max-width: 767.98px) {
        .calendar-container-card {
            padding: 14px 10px !important;
            border-radius: 12px;
        }

        #eventCalendar .fc-toolbar-title {
            font-size: 1.05rem !important;
        }

        .metric-badge {
            font-size: 11px !important;
            padding: 4px 8px !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Header with Summary Stats (Original Clean Polish) -->
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-dark text-white px-2 py-0.5 rounded-pill" style="font-size: 11px;">Operations</span>
                <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ now()->format('l, F j, Y') }}</span>
            </div>
            <h4 class="fw-bold mb-0" style="color: #0f172a; letter-spacing: -0.4px;">Event Calendar & Schedule</h4>
            <p class="text-muted small mb-0">Browse monthly schedule on the left; scheduled events and customer details update automatically on the right.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="status-pill status-pending"><span class="pill-dot"></span> Pending</span>
            <span class="status-pill status-confirmed"><span class="pill-dot"></span> Confirmed</span>
            <span class="status-pill status-completed"><span class="pill-dot"></span> Completed</span>
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm ms-2 shadow-sm" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-calendar-plus-fill me-1"></i> Register Event
            </a>
        </div>
    </div>

    <!-- Active Month Live Statistics Bar -->
    <div class="d-flex align-items-center gap-2 flex-wrap" id="monthStatsBar">
        <div class="metric-badge">
            <i class="bi bi-calendar3-event text-primary"></i>
            <span>Month Total:</span>
            <strong class="text-dark" id="statTotalEvents">0</strong>
        </div>
        <div class="metric-badge">
            <i class="bi bi-check2-circle text-primary"></i>
            <span>Confirmed:</span>
            <strong class="text-primary" id="statConfirmedEvents">0</strong>
        </div>
        <div class="metric-badge">
            <i class="bi bi-hourglass-split text-warning"></i>
            <span>Pending:</span>
            <strong class="text-warning" id="statPendingEvents">0</strong>
        </div>
        <div class="metric-badge">
            <i class="bi bi-wallet2 text-success"></i>
            <span>Scheduled Value:</span>
            <strong class="text-success" id="statTotalValue">Rs. 0.00</strong>
        </div>
    </div>
</div>

<!-- TWO-COLUMN CALENDAR & EVENTS DISPLAY -->
<div class="row g-3 g-xl-4">
    <!-- Left Column: Interactive FullCalendar -->
    <div class="col-12 col-lg-6">
        <div class="calendar-container-card">
            <div id="eventCalendar" aria-label="Monthly interactive event calendar"></div>
        </div>
    </div>

    <!-- Right Column: Month's Events Grid Panel -->
    <div class="col-12 col-lg-6">
        <div class="month-events-panel">
            <!-- Header -->
            <div class="month-events-header">
                <div class="d-flex align-items-center justify-content-between mb-1 flex-wrap gap-2">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 15px;">
                        <i class="bi bi-calendar-week-fill text-primary"></i>
                        <span id="activeMonthTitle">Current Month</span>
                    </h6>
                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-dark py-0.5 px-2" id="btnFilterToday" onclick="filterToToday()" style="font-size: 11.5px; border-radius: 6px;">
                            <i class="bi bi-clock me-1"></i> Today
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2" id="btnFilterAllMonth" onclick="resetDateFilter()" style="font-size: 11.5px; border-radius: 6px; display: none;">
                            <i class="bi bi-calendar3 me-1"></i> All Month
                        </button>
                        <span class="badge bg-dark text-white rounded-pill px-2.5 py-1" id="monthEventsCountBadge" style="font-size: 11px;">0 Events</span>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1 flex-wrap gap-1">
                    <small class="text-muted" id="activeMonthSubtitle">All scheduled events for this month</small>
                    <div id="activeFilterBadgeContainer" style="display: none;">
                        <span class="filter-reset-pill" onclick="resetDateFilter()" title="Click to show all events in this month">
                            <span id="activeFilterDateText"></span> <i class="bi bi-x-circle-fill ms-1"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search / Filter input -->
            <div class="p-2.5 border-bottom bg-light">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="filterEventsInput" class="form-control border-start-0" placeholder="Filter by event name, client, venue, package...">
                </div>
            </div>

            <!-- Scrollable Events List -->
            <div class="month-events-scrollable" id="monthEventsListContainer">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-secondary mb-2" role="status"></div>
                    <p class="small mb-0">Loading calendar events...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let calendarInstance = null;
    let allLoadedEvents = [];
    let currentFilterDate = null;
    let currentSearchQuery = '';
    let filterIsToday = false;

    document.addEventListener('DOMContentLoaded', () => {
        const calendarEl = document.getElementById('eventCalendar');
        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            aspectRatio: 1.25,
            events: function(fetchInfo, successCallback, failureCallback) {
                const url = '{{ route('api.calendar.events') }}?start=' + encodeURIComponent(fetchInfo.startStr) + '&end=' + encodeURIComponent(fetchInfo.endStr);
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        allLoadedEvents = data;

                        // Group events by day to calculate totals
                        const dayMap = {};
                        data.forEach(item => {
                            const dateKey = item.start ? item.start.split('T')[0] : '';
                            if (!dateKey) return;
                            if (!dayMap[dateKey]) {
                                dayMap[dateKey] = {
                                    count: 0,
                                    statuses: []
                                };
                            }
                            dayMap[dateKey].count++;
                            dayMap[dateKey].statuses.push(item.extendedProps?.status || 'pending');
                        });

                        // Create ONE summary event per date for the calendar cell
                        const summaryEvents = Object.keys(dayMap).map(dateKey => {
                            const info = dayMap[dateKey];
                            const count = info.count;
                            const label = count === 1 ? '1 Event' : `${count} Events`;

                            let bgColor = '#ecfdf5';
                            let textColor = '#047857';
                            let borderColor = '#a7f3d0';
                            let dotColor = '#10b981';

                            if (info.statuses.includes('pending')) {
                                bgColor = '#fffbeb';
                                textColor = '#b45309';
                                borderColor = '#fde68a';
                                dotColor = '#f59e0b';
                            } else if (info.statuses.includes('confirmed')) {
                                bgColor = '#eff6ff';
                                textColor = '#1d4ed8';
                                borderColor = '#bfdbfe';
                                dotColor = '#3b82f6';
                            }

                            return {
                                id: 'summary-' + dateKey,
                                start: dateKey,
                                allDay: true,
                                title: label,
                                extendedProps: {
                                    dateKey: dateKey,
                                    count: count,
                                    bgColor: bgColor,
                                    textColor: textColor,
                                    borderColor: borderColor,
                                    dotColor: dotColor
                                }
                            };
                        });

                        successCallback(summaryEvents);
                        renderFilteredEvents();
                    })
                    .catch(err => {
                        console.error('Error fetching calendar events:', err);
                        failureCallback(err);
                    });
            },
            eventContent: function(arg) {
                const props = arg.event.extendedProps || {};
                const count = props.count || 1;
                const tooltipText = count === 1 ? '1 Event scheduled' : `${count} Events scheduled`;
                const bgColor = props.bgColor || '#eff6ff';
                const textColor = props.textColor || '#1d4ed8';
                const borderColor = props.borderColor || '#bfdbfe';
                const dotColor = props.dotColor || '#3b82f6';

                return {
                    html: `
                        <div class="day-count-icon-badge" title="${tooltipText}" style="background: ${bgColor}; color: ${textColor}; border: 1px solid ${borderColor};">
                            <i class="bi bi-calendar2-event-fill" style="color: ${dotColor};"></i>
                            <span>${count}</span>
                        </div>
                    `
                };
            },
            customButtons: {
                todayBtn: {
                    text: 'Today',
                    click: function() {
                        calendarInstance.today();
                        filterToToday();
                    }
                }
            },
            headerToolbar: {
                left: 'prev,next todayBtn',
                center: 'title',
                right: ''
            },
            datesSet(dateInfo) {
                document.getElementById('activeMonthTitle').innerText = dateInfo.view.title;
                if (!filterIsToday) {
                    currentFilterDate = null;
                    document.getElementById('activeFilterBadgeContainer').style.display = 'none';
                    const allBtn = document.getElementById('btnFilterAllMonth');
                    if (allBtn) allBtn.style.display = 'none';
                }
                setTimeout(renderFilteredEvents, 50);
            },
            eventClick(info) {
                info.jsEvent.preventDefault();
                const props = info.event.extendedProps || {};
                const dateStr = props.dateKey || info.event.startStr.split('T')[0];
                filterBySpecificDate(dateStr, false);
            },
            dateClick(info) {
                filterBySpecificDate(info.dateStr, false);
            }
        });
        calendarInstance.render();

        // Search input event listener
        document.getElementById('filterEventsInput').addEventListener('input', function(e) {
            currentSearchQuery = e.target.value.toLowerCase().trim();
            renderFilteredEvents();
        });
    });

    function getTodayDateString() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function filterToToday() {
        const todayStr = getTodayDateString();
        filterIsToday = true;
        currentFilterDate = todayStr;
        const textEl = document.getElementById('activeFilterDateText');
        if (textEl) textEl.innerText = 'Today: ' + formatFriendlyDate(todayStr);
        const badgeEl = document.getElementById('activeFilterBadgeContainer');
        if (badgeEl) badgeEl.style.display = 'inline-block';
        const allBtn = document.getElementById('btnFilterAllMonth');
        if (allBtn) allBtn.style.display = 'inline-block';
        renderFilteredEvents();
    }

    function filterBySpecificDate(dateStr, isToday = false) {
        filterIsToday = isToday;
        currentFilterDate = dateStr;
        const textEl = document.getElementById('activeFilterDateText');
        if (textEl) textEl.innerText = (isToday ? 'Today: ' : 'Date: ') + formatFriendlyDate(dateStr);
        const badgeEl = document.getElementById('activeFilterBadgeContainer');
        if (badgeEl) badgeEl.style.display = 'inline-block';
        const allBtn = document.getElementById('btnFilterAllMonth');
        if (allBtn) allBtn.style.display = 'inline-block';
        renderFilteredEvents();
    }

    function resetDateFilter() {
        filterIsToday = false;
        currentFilterDate = null;
        const badgeEl = document.getElementById('activeFilterBadgeContainer');
        if (badgeEl) badgeEl.style.display = 'none';
        const allBtn = document.getElementById('btnFilterAllMonth');
        if (allBtn) allBtn.style.display = 'none';
        renderFilteredEvents();
    }

    function renderFilteredEvents() {
        const container = document.getElementById('monthEventsListContainer');
        const countBadge = document.getElementById('monthEventsCountBadge');
        const subtitle = document.getElementById('activeMonthSubtitle');
        
        if (!calendarInstance) return;
        const curDate = calendarInstance.getDate();
        const targetYear = curDate.getFullYear();
        const targetMonth = curDate.getMonth();
        const viewTitle = (calendarInstance.view && calendarInstance.view.title) ? calendarInstance.view.title : curDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        // Filter events strictly belonging to the active calendar month
        const monthEvents = allLoadedEvents.filter(e => {
            const rawDate = e.startStr || e.start || '';
            if (!rawDate) return false;
            const parts = rawDate.split('T')[0].split('-');
            if (parts.length >= 2) {
                const y = parseInt(parts[0], 10);
                const m = parseInt(parts[1], 10) - 1;
                return y === targetYear && m === targetMonth;
            }
            const d = new Date(rawDate);
            return !isNaN(d) && d.getFullYear() === targetYear && d.getMonth() === targetMonth;
        });

        // Update active month summary stats bar
        updateMonthSummaryStats(monthEvents);

        let events = [...monthEvents];

        // Specific Date Click or Today filter
        if (currentFilterDate) {
            events = events.filter(e => {
                const s = e.startStr ? e.startStr.split('T')[0] : (e.start ? e.start.split('T')[0] : '');
                return s === currentFilterDate;
            });
            subtitle.innerText = filterIsToday
                ? `Showing today's scheduled events (${formatFriendlyDate(currentFilterDate)})`
                : `Showing events on ${formatFriendlyDate(currentFilterDate)}`;
        } else {
            subtitle.innerText = `All scheduled events for ${viewTitle}`;
        }

        // Search input filter
        if (currentSearchQuery) {
            events = events.filter(e => {
                const title = (e.title || '').toLowerCase();
                const props = e.extendedProps || {};
                const customer = (props.customer || '').toLowerCase();
                const venue = (props.venue || '').toLowerCase();
                const eventNo = (props.event_no || '').toLowerCase();
                const pkg = (props.package || '').toLowerCase();

                return title.includes(currentSearchQuery) ||
                       customer.includes(currentSearchQuery) ||
                       venue.includes(currentSearchQuery) ||
                       eventNo.includes(currentSearchQuery) ||
                       pkg.includes(currentSearchQuery);
            });
        }

        // Sort events chronologically
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        countBadge.innerText = `${events.length} Event${events.length === 1 ? '' : 's'}`;
        container.innerHTML = '';

        if (events.length === 0) {
            const createUrl = currentFilterDate 
                ? `{{ route('events.create') }}?event_date=${currentFilterDate}` 
                : `{{ route('events.create') }}`;

            const resetLink = currentFilterDate ? `
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" onclick="resetDateFilter()" style="font-size: 11.5px; border-radius: 8px;">
                        <i class="bi bi-calendar3 me-1"></i> Show All Month Events
                    </button>
                </div>
            ` : '';
            
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                    <h6 class="fw-bold mb-1 text-dark">${filterIsToday ? 'No Events Scheduled for Today' : (currentFilterDate ? 'No Events on this Date' : 'No Events Scheduled')}</h6>
                    <p class="small text-muted mb-3">${filterIsToday ? 'There are no events registered for today in this calendar.' : (currentFilterDate ? 'No events scheduled on ' + formatFriendlyDate(currentFilterDate) + '.' : 'No events scheduled for ' + viewTitle + '.')}</p>
                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                        <a href="${createUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Register Event
                        </a>
                        ${resetLink}
                    </div>
                </div>
            `;
            return;
        }

        // Render card list
        const row = document.createElement('div');
        row.className = 'row g-3';

        events.forEach(e => {
            const props = e.extendedProps || {};
            const status = props.status || 'pending';
            const statusClass = status === 'confirmed' ? 'status-confirmed' : (status === 'completed' ? 'status-completed' : (status === 'cancelled' ? 'status-cancelled' : 'status-pending'));
            const rawDate = e.startStr ? e.startStr.split('T')[0] : (e.start ? e.start.split('T')[0] : '');
            const dateStr = formatFriendlyDate(rawDate);
            const amountFormatted = props.total_amount ? 'Rs. ' + parseFloat(props.total_amount).toLocaleString('en-US', { minimumFractionDigits: 2 }) : '';
            const orderUrl = props.order_url || ('/events/' + e.id + '/create-order');

            const col = document.createElement('div');
            col.className = 'col-12';

            col.innerHTML = `
                <div class="cal-event-card w-100" id="event-item-${e.id}">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="cal-date-chip">
                                <i class="bi bi-calendar-event me-1 text-primary"></i> ${dateStr}
                            </span>
                            <span class="badge bg-light text-secondary border" style="font-size: 11px;">${props.event_no || ('EVT-' + e.id)}</span>
                            ${props.package ? `<span class="badge bg-light text-dark border" style="font-size: 11px;"><i class="bi bi-box-seam me-1 text-primary"></i>${props.package}</span>` : ''}
                        </div>
                        <span class="status-pill ${statusClass}">
                            <span class="pill-dot"></span> ${capitalizeFirst(status)}
                        </span>
                    </div>

                    <div class="mb-2">
                        <a href="${e.url}" target="_blank" rel="noopener noreferrer" class="fw-bold text-dark text-decoration-none fs-6 d-inline-block" title="Open event details in new tab">
                            ${props.event_name || e.title} <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 11px;"></i>
                        </a>
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap small text-muted pt-2 border-top mt-2" style="font-size: 12px;">
                        <div><i class="bi bi-person-fill text-muted me-1"></i><strong>Customer:</strong> <span class="text-dark fw-medium">${props.customer || 'Walk-in'}</span></div>
                        <div><i class="bi bi-geo-alt-fill text-danger me-1"></i><strong>Venue:</strong> ${props.venue || 'Venue TBD'}</div>
                        ${amountFormatted ? `<div><i class="bi bi-wallet2 text-success me-1"></i><strong>Total:</strong> <span class="text-success fw-bold">${amountFormatted}</span></div>` : ''}
                    </div>
                    <div class="d-flex align-items-center justify-content-start gap-2 mt-2.5">
                        <a href="${e.url}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View Event
                        </a>
                        <a href="${orderUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">
                            <i class="bi bi-receipt"></i> Order
                        </a>
                    </div>
                </div>
            `;
            row.appendChild(col);
        });

        container.appendChild(row);
    }

    function updateMonthSummaryStats(monthEvents) {
        let confirmed = 0;
        let pending = 0;
        let totalVal = 0;

        monthEvents.forEach(e => {
            const props = e.extendedProps || {};
            const st = (props.status || '').toLowerCase();
            if (st === 'confirmed') confirmed++;
            if (st === 'pending') pending++;
            if (props.total_amount) {
                totalVal += parseFloat(props.total_amount);
            }
        });

        const totalEl = document.getElementById('statTotalEvents');
        const confEl = document.getElementById('statConfirmedEvents');
        const pendEl = document.getElementById('statPendingEvents');
        const valEl = document.getElementById('statTotalValue');

        if (totalEl) totalEl.innerText = monthEvents.length;
        if (confEl) confEl.innerText = confirmed;
        if (pendEl) pendEl.innerText = pending;
        if (valEl) valEl.innerText = 'Rs. ' + totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function highlightEventInPanel(eventId) {
        const item = document.getElementById(`event-item-${eventId}`);
        if (item) {
            document.querySelectorAll('.cal-event-card').forEach(el => el.classList.remove('highlighted'));
            item.classList.add('highlighted');
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            resetDateFilter();
            setTimeout(() => {
                const el = document.getElementById(`event-item-${eventId}`);
                if (el) {
                    el.classList.add('highlighted');
                    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 100);
        }
    }

    function formatFriendlyDate(dateString) {
        if (!dateString) return '';
        const parts = dateString.split('-');
        if (parts.length === 3) {
            const d = new Date(parts[0], parts[1] - 1, parts[2]);
            return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
        }
        return dateString;
    }

    function capitalizeFirst(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>
@endsection