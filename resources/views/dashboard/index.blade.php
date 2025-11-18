<x-layout>
    <h1 class="text-2xl font-bold mb-4">Kalendarz zajęć</h1>


    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-4">
            <div id='calendar'></div>
        </div>
    </div>

    <!-- Modal: Dodaj lekcję -->
    <div id="lessonModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Dodaj lekcję</h3>
                <button id="closeModal" class="btn">Zamknij</button>
            </div>

            <form id="lessonForm" action="{{ route('lessons.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="text-sm text-gray-600">Uczestnik</label>
                    <select name="student_id" id="modalStudentSelect" required class="border p-2 w-full">
                        <option value="">— wybierz ucznia —</option>
                        @foreach ($allStudents as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} {{ $s->surname }} @if ($s->tel)
                                    — {{ $s->tel }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Tytuł</label>
                    <input name="title" id="modalTitle" type="text" class="border p-2 w-full"
                        placeholder="Opcjonalny tytuł">
                </div>

                <div>
                    <p class="text-sm text-gray-500">Wybierz godzinę (data już jest ustawiona z kalendarza)</p>
                    <label class="text-sm text-gray-600">Start (godzina)</label>
                    <input name="start_time" id="modalStartTime" type="time" class="border p-2 w-full" required>
                </div>

                <div>
                    <label class="text-sm text-gray-600">End (godzina)</label>
                    <input name="end_time" id="modalEndTime" type="time" class="border p-2 w-full">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Notatka</label>
                    <textarea name="notes" id="modalNotes" class="border p-2 w-full" placeholder="Notatka (opcjonalna)"></textarea>
                </div>

                <input type="hidden" name="start" id="modalStartHidden">
                <input type="hidden" name="end" id="modalEndHidden">

                <div class="flex gap-2">
                    <button type="submit" class="btn">Dodaj lekcję</button>
                    <button type="button" id="cancelModal" class="btn">Anuluj</button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // safety: ensure library is available
  if (!window.FullCalendar) {
    console.error('FullCalendar nie jest załadowany. Upewnij się, że index.global.min.js został dołączony PRZED tym skryptem.');
    return;
  }

  try {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) { console.error('Brak elementu #calendar'); return; }

    // resources & events (globalne dla debugowania)
    window.resources = [
      @foreach ($allStudents ?? collect() as $s)
        { id: "{{ $s->id }}", title: "{{ addslashes(trim(($s->name ?? '') . ' ' . ($s->surname ?? '')) ?: 'Uczestnik') }}" },
      @endforeach
    ];

    // IMPORTANT: convert "YYYY-MM-DD HH:MM:SS" -> "YYYY-MM-DDTHH:MM:SS"
    window.events = [
      @foreach ($lessons ?? collect() as $lesson)
        {
          id: "{{ $lesson->id }}",
          resourceId: "{{ $lesson->student_id }}",
          title: "{{ addslashes($lesson->title ?? (trim(optional($lesson->student)->name . ' ' . optional($lesson->student)->surname) ?: 'Lekcja')) }}",
          start: "{{ str_replace(' ', 'T', $lesson->start) }}",
          end: "{{ $lesson->end ? str_replace(' ', 'T', $lesson->end) : '' }}",
          url: "{{ optional($lesson->student) ? route('dashboard.show', $lesson->student->id) : '' }}"
        },
      @endforeach
    ];

    function formatLocalInput(dt){ if(!dt) return null; const pad=n=>String(n).padStart(2,'0'); return dt.getFullYear()+'-'+pad(dt.getMonth()+1)+'-'+pad(dt.getDate())+'T'+pad(dt.getHours())+':'+pad(dt.getMinutes()); }
    function formatDateYMD(dt){ const pad=n=>String(n).padStart(2,'0'); return dt.getFullYear()+'-'+pad(dt.getMonth()+1)+'-'+pad(dt.getDate()); }

    // modal elements
    const lessonModal = document.getElementById('lessonModal');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelModalBtn = document.getElementById('cancelModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalNotes = document.getElementById('modalNotes');
    const modalStudentSelect = document.getElementById('modalStudentSelect');
    const modalStartTime = document.getElementById('modalStartTime');
    const modalEndTime = document.getElementById('modalEndTime');
    const modalStartHidden = document.getElementById('modalStartHidden');
    const modalEndHidden = document.getElementById('modalEndHidden');
    let modalDate = null;

    function openModal(){ if(lessonModal){ lessonModal.classList.remove('hidden'); lessonModal.classList.add('flex'); } }
    function closeModal(){ if(lessonModal){ lessonModal.classList.remove('flex'); lessonModal.classList.add('hidden'); } }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

    // create calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'pl',
        //   plugins: [
        //     FullCalendar.dayGridPlugin,
        //     FullCalendar.interactionPlugin
        //   ],
      initialView: 'dayGridMonth',
      events: window.events,
      editable: true,
      selectable: true,
      eventResizableFromStart: true,
      eventColor: '#ef4444',
      headerToolbar: { left: 'title', center: '', right: 'prev,next today' },
      height: 'auto',
      eventTimeFormat: { hour: '2-digit', minute: '2-digit' },

      dateClick: function(info){ window._openLessonModalForDate && window._openLessonModalForDate(info.date); },

      eventDrop: function(info){
        const id = info.event.id;
        const start = formatLocalInput(info.event.start);
        const end = formatLocalInput(info.event.end);
        fetch(`/lessons/${id}/ajax`, {
          method: 'PUT',
          headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token, 'Accept':'application/json' },
          body: JSON.stringify({ start, end, title: info.event.title })
        }).then(r => { if (!r.ok) { info.revert(); alert('Nie można zaktualizować'); } }).catch(()=>{ info.revert(); alert('Błąd sieci'); });
      },

      eventResize: function(info){
        const id = info.event.id;
        const start = formatLocalInput(info.event.start);
        const end = formatLocalInput(info.event.end);
        fetch(`/lessons/${id}/ajax`, {
          method: 'PUT',
          headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token, 'Accept':'application/json' },
          body: JSON.stringify({ start, end, title: info.event.title })
        }).then(r => { if (!r.ok) { info.revert(); alert('Nie można zaktualizować'); } }).catch(()=>{ info.revert(); alert('Błąd sieci'); });
      }
    });

    calendar.render();

    window._openLessonModalForDate = function(dateObj){
      modalDate = formatDateYMD(dateObj);
      if (modalStartTime) modalStartTime.value = '10:00';
      if (modalEndTime) modalEndTime.value = '11:00';
      if (modalTitle) modalTitle.value = '';
      if (modalNotes) modalNotes.value = '';
      if (modalStudentSelect && modalStudentSelect.options.length > 1) modalStudentSelect.selectedIndex = 1;
      openModal();
    };

    // submit handler
    const lessonForm = document.getElementById('lessonForm');
    if (lessonForm) {
      lessonForm.addEventListener('submit', function(e){
        if (!modalDate) return;
        const startTime = modalStartTime ? modalStartTime.value : '';
        const endTime = modalEndTime ? modalEndTime.value : '';
        if (modalStartHidden) modalStartHidden.value = modalDate + ' ' + (startTime ? startTime + ':00' : '10:00:00');
        if (modalEndHidden) modalEndHidden.value = endTime ? (modalDate + ' ' + endTime + ':00') : '';
      });
    }

    // debug quick-check in console
    console.log('FullCalendar ok:', !!window.FullCalendar, 'events:', window.events.length, 'resources:', window.resources && window.resources.length);
  }
  catch (err) {
    // show full stack trace so we can pinpoint the exact line in index.global.min.js if it reappears
    console.error('Błąd podczas inicjalizacji kalendarza:', err);
  }
});
</script>


</x-layout>
