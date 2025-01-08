<div class="p-6">
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-4">Event Calendar</h2>
            <div id="calendar" class="min-h-[600px]"></div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: {
                url: '{{ route("api.events.calendar") }}',
                type: 'GET',
                error: function() {
                    alert('Error fetching events');
                }
            },
            eventClick: function(event) {
                window.location.href = '/events/' + event.id;
            }
        });
    });
</script>
@endpush
