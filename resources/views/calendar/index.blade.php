@extends('layouts.app')
@section('title', 'Event calendar')
@section('page-title', 'Event calendar')
@section('styles')
<style>#eventCalendar{background:#fff;padding:18px;border-radius:8px}.fc .fc-toolbar-title{font-size:1.2rem;font-weight:700}.fc .fc-button{background:#0f766e;border-color:#0f766e;text-transform:capitalize}.fc .fc-button:hover,.fc .fc-button:focus{background:#115e59;border-color:#115e59;box-shadow:none}.fc .fc-daygrid-event{padding:3px 5px;border:0}</style>
@endsection
@section('content')
<div class="page-header"><div><h4>Event calendar</h4><p class="text-muted mb-0">Review scheduled events by date.</p></div><a href="{{ route('events.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Register event</a></div>
<div id="eventCalendar" aria-label="Events calendar"></div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const calendar = new FullCalendar.Calendar(document.getElementById('eventCalendar'), {
            initialView: 'dayGridMonth', height: 'auto', events: @json(route('api.calendar.events')),
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
            eventClick(info) { if (info.event.url) { info.jsEvent.preventDefault(); window.location.href = info.event.url; } }
        });
        calendar.render();
    });
</script>
@endsection