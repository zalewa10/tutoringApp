<x-layout>
    <div class="h-16 p-4 bg-white border-b border-gray-200 flex items-center">
        <h1>Kalendarz zajęć</h1>
    </div>

    <div class="p-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" style="box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <div id='calendar' class="fc-custom"></div>
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
                            <option value="{{ $s->id }}">{{ $s->name }} {{ $s->surname }}
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

    <!-- Modal: Podgląd lekcji -->
    <div id="lessonPreviewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold" id="previewTitle">Lekcja</h3>
                    <p class="text-sm text-gray-500 mt-1" id="previewStudent">Uczeń</p>
                </div>
                <button id="closePreviewModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="space-y-3 mb-6 text-sm">
                <div>
                    <p class="text-gray-600">Start</p>
                    <p class="font-medium" id="previewStart">—</p>
                </div>
                <div>
                    <p class="text-gray-600">Koniec</p>
                    <p class="font-medium" id="previewEnd">—</p>
                </div>
                <div id="previewNotesContainer" style="display: none;">
                    <p class="text-gray-600">Notatka</p>
                    <p class="text-gray-700 italic" id="previewNotes"></p>
                </div>
            </div>

            <div class="flex gap-2 pt-4 border-t">
                <button id="previewEditBtn"
                    class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                    Edytuj
                </button>
                <button id="previewDeleteBtn"
                    class="flex-1 text-center px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">
                    Usuń
                </button>
                <button id="closePreviewBtn"
                    class="px-3 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 text-sm font-medium">
                    Zamknij
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Edycja lekcji -->
    <div id="lessonEditModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Edytuj lekcję</h3>
                <button id="closeEditModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="lessonEditForm" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Uczeń *</label>
                    <select id="editStudentSelect" name="student_id" required
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">— wybierz ucznia —</option>
                        @foreach ($allStudents ?? collect() as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} {{ $s->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tytuł (opcjonalny)</label>
                    <input type="text" id="editTitle" name="title" placeholder="Np. Matematyka - Równania"
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start *</label>
                        <input type="datetime-local" id="editStart" name="start" required
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Koniec (opcjonalny)</label>
                        <input type="datetime-local" id="editEnd" name="end"
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notatka (opcjonalnie)</label>
                    <textarea id="editNotes" name="notes" rows="3"
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Dodaj notatki do tej lekcji..."></textarea>
                </div>

                <div class="flex gap-2 pt-4 border-t">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium">
                        Zapisz
                    </button>
                    <button type="button" id="cancelEditBtn"
                        class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded font-medium">
                        Anuluj
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // safety: ensure library is available
            if (!window.FullCalendar) {
                console.error(
                    'FullCalendar nie jest załadowany. Upewnij się, że index.global.min.js został dołączony PRZED tym skryptem.'
                );
                return;
            }

            try {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) {
                    console.error('Brak elementu #calendar');
                    return;
                }

                // resources & events (globalne dla debugowania)
                window.resources = [
                    @foreach ($allStudents ?? collect() as $s)
                        {
                            id: "{{ $s->id }}",
                            title: "{{ addslashes(trim(($s->name ?? '') . ' ' . ($s->surname ?? '')) ?: 'Uczestnik') }}",
                            color: "{{ $s->color ?? '#ef4444' }}"
                        },
                    @endforeach
                ];

                // IMPORTANT: convert "YYYY-MM-DD HH:MM:SS" -> "YYYY-MM-DDTHH:MM:SS"
                window.events = [
                    @foreach ($lessons ?? collect() as $lesson)
                        {
                            id: "{{ $lesson->id }}",
                            resourceId: "{{ $lesson->student_id }}",
                            title: "{{ addslashes(trim(optional($lesson->student)->name . ' ' . optional($lesson->student)->surname) ?: 'Lekcja') }}",
                            start: "{{ str_replace(' ', 'T', $lesson->start) }}",
                            end: "{{ $lesson->end ? str_replace(' ', 'T', $lesson->end) : '' }}",
                            notes: "{{ addslashes($lesson->notes ?? '') }}",
                            backgroundColor: "{{ optional($lesson->student)->color ?? '#ef4444' }}",
                            borderColor: "{{ optional($lesson->student)->color ?? '#ef4444' }}"
                        },
                    @endforeach
                ];

                function formatLocalInput(dt) {
                    if (!dt) return null;
                    const pad = n => String(n).padStart(2, '0');
                    return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' + pad(dt
                        .getHours()) + ':' + pad(dt.getMinutes());
                }

                function formatDateYMD(dt) {
                    const pad = n => String(n).padStart(2, '0');
                    return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate());
                }

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

                // preview modal elements
                const previewModal = document.getElementById('lessonPreviewModal');
                const closePreviewBtn = document.getElementById('closePreviewModal');
                const closePreviewBtn2 = document.getElementById('closePreviewBtn');
                const previewEditBtn = document.getElementById('previewEditBtn');
                const previewDeleteBtn = document.getElementById('previewDeleteBtn');

                // edit modal elements
                const editModal = document.getElementById('lessonEditModal');
                const closeEditModalBtn = document.getElementById('closeEditModal');
                const cancelEditBtn = document.getElementById('cancelEditBtn');
                const lessonEditForm = document.getElementById('lessonEditForm');
                let currentLessonId = null;

                function openModal() {
                    if (lessonModal) {
                        lessonModal.classList.remove('hidden');
                        lessonModal.classList.add('flex');
                    }
                }

                function closeModal() {
                    if (lessonModal) {
                        lessonModal.classList.remove('flex');
                        lessonModal.classList.add('hidden');
                    }
                }

                function openPreviewModal() {
                    if (previewModal) {
                        previewModal.classList.remove('hidden');
                        previewModal.classList.add('flex');
                    }
                }

                function closePreviewModal() {
                    if (previewModal) {
                        previewModal.classList.remove('flex');
                        previewModal.classList.add('hidden');
                    }
                }

                function openEditModal() {
                    if (editModal) {
                        editModal.classList.remove('hidden');
                        editModal.classList.add('flex');
                    }
                }

                function closeEditModal() {
                    if (editModal) {
                        editModal.classList.remove('flex');
                        editModal.classList.add('hidden');
                    }
                    currentLessonId = null;
                }

                if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
                if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
                if (closePreviewBtn) closePreviewBtn.addEventListener('click', closePreviewModal);
                if (closePreviewBtn2) closePreviewBtn2.addEventListener('click', closePreviewModal);
                if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
                if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditModal);

                // Edit lesson button
                if (previewEditBtn) {
                    previewEditBtn.addEventListener('click', function() {
                        if (!currentLessonId) return;
                        closePreviewModal();
                        openEditModal();
                    });
                }

                // Delete lesson button
                if (previewDeleteBtn) {
                    previewDeleteBtn.addEventListener('click', function() {
                        if (!currentLessonId) return;
                        if (!confirm('Czy na pewno chcesz usunąć tę lekcję?')) return;

                        fetch(`/lessons/${currentLessonId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        }).then(r => {
                            if (r.ok) {
                                closePreviewModal();
                                location.reload();
                            } else {
                                alert('Błąd przy usuwaniu lekcji');
                            }
                        }).catch(() => {
                            alert('Błąd sieci');
                        });
                    });
                }

                // Edit form submission
                if (lessonEditForm) {
                    lessonEditForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (!currentLessonId) return;

                        const formData = new FormData(this);
                        const data = Object.fromEntries(formData);

                        fetch(`/lessons/${currentLessonId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        }).then(r => {
                            if (r.ok) {
                                closeEditModal();
                                location.reload();
                            } else {
                                alert('Błąd przy aktualizacji lekcji');
                            }
                        }).catch(() => {
                            alert('Błąd sieci');
                        });
                    });
                }

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
                    firstDay: 1,
                    selectable: true,
                    eventResizableFromStart: true,
                    eventDisplay: 'block',
                    eventTextColor: '#0f172a',
                    headerToolbar: {
                        left: 'title',
                        center: '',
                        right: 'prev,next'
                    },
                    height: 'auto',
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit'
                    },

                    dateClick: function(info) {
                        window._openLessonModalForDate && window._openLessonModalForDate(info.date);
                    },

                    eventClick: function(info) {
                        window._openLessonPreviewModal && window._openLessonPreviewModal(info.event);
                    },

                    eventDrop: function(info) {
                        const id = info.event.id;
                        const start = formatLocalInput(info.event.start);
                        const end = formatLocalInput(info.event.end);
                        fetch(`/lessons/${id}/ajax`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                start,
                                end,
                                title: info.event.title
                            })
                        }).then(r => {
                            if (!r.ok) {
                                info.revert();
                                alert('Nie można zaktualizować');
                            }
                        }).catch(() => {
                            info.revert();
                            alert('Błąd sieci');
                        });
                    },

                    eventResize: function(info) {
                        const id = info.event.id;
                        const start = formatLocalInput(info.event.start);
                        const end = formatLocalInput(info.event.end);
                        fetch(`/lessons/${id}/ajax`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                start,
                                end,
                                title: info.event.title
                            })
                        }).then(r => {
                            if (!r.ok) {
                                info.revert();
                                alert('Nie można zaktualizować');
                            }
                        }).catch(() => {
                            info.revert();
                            alert('Błąd sieci');
                        });
                    }
                });

                calendar.render();

                window._openLessonModalForDate = function(dateObj) {
                    modalDate = formatDateYMD(dateObj);
                    if (modalStartTime) modalStartTime.value = '10:00';
                    if (modalEndTime) modalEndTime.value = '11:00';
                    if (modalTitle) modalTitle.value = '';
                    if (modalNotes) modalNotes.value = '';
                    if (modalStudentSelect && modalStudentSelect.options.length > 1) modalStudentSelect
                        .selectedIndex = 1;
                    openModal();
                };

                window._openLessonPreviewModal = function(event) {
                    // Find the lesson data in window.events
                    const lessonData = window.events.find(e => e.id === event.id);
                    if (!lessonData) return;

                    currentLessonId = event.id;

                    // Update preview modal content
                    document.getElementById('previewTitle').textContent = event.title || 'Lekcja';

                    // Find student name
                    const student = window.resources.find(r => r.id === lessonData.resourceId);
                    document.getElementById('previewStudent').textContent = student ?
                        `Uczeń: ${student.title}` : 'Uczeń: —';

                    // Format dates
                    const startDate = new Date(lessonData.start);
                    const pad = n => String(n).padStart(2, '0');
                    const formatDate = (dt) =>
                        `${pad(dt.getDate())}.${pad(dt.getMonth()+1)}.${dt.getFullYear()} ${pad(dt.getHours())}:${pad(dt.getMinutes())}`;

                    document.getElementById('previewStart').textContent = formatDate(startDate);

                    if (lessonData.end) {
                        const endDate = new Date(lessonData.end);
                        document.getElementById('previewEnd').textContent = formatDate(endDate);
                    } else {
                        document.getElementById('previewEnd').textContent = '—';
                    }

                    // Populate edit form fields
                    document.getElementById('editStudentSelect').value = lessonData.resourceId;
                    document.getElementById('editTitle').value = event.title || '';

                    // Convert dates to datetime-local format
                    const formatForInput = (dateStr) => {
                        const dt = new Date(dateStr);
                        const pad = n => String(n).padStart(2, '0');
                        return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
                    };

                    document.getElementById('editStart').value = formatForInput(lessonData.start);
                    if (lessonData.end) {
                        document.getElementById('editEnd').value = formatForInput(lessonData.end);
                    } else {
                        document.getElementById('editEnd').value = '';
                    }

                    document.getElementById('editNotes').value = lessonData.notes || '';

                    openPreviewModal();
                };

                // submit handler
                const lessonForm = document.getElementById('lessonForm');
                if (lessonForm) {
                    lessonForm.addEventListener('submit', function(e) {
                        if (!modalDate) return;
                        const startTime = modalStartTime ? modalStartTime.value : '';
                        const endTime = modalEndTime ? modalEndTime.value : '';
                        if (modalStartHidden) modalStartHidden.value = modalDate + ' ' + (startTime ?
                            startTime + ':00' : '10:00:00');
                        if (modalEndHidden) modalEndHidden.value = endTime ? (modalDate + ' ' + endTime +
                            ':00') : '';
                    });
                }

                // debug quick-check in console
                console.log('FullCalendar ok:', !!window.FullCalendar, 'events:', window.events.length,
                    'resources:', window.resources && window.resources.length);
            } catch (err) {
                // show full stack trace so we can pinpoint the exact line in index.global.min.js if it reappears
                console.error('Błąd podczas inicjalizacji kalendarza:', err);
            }
        });
    </script>
</x-layout>
