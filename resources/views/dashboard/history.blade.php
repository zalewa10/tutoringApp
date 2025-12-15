<x-layout>
    <div class="h-16 p-4 bg-white border-b border-gray-200 flex items-center">
        <h1>Historia lekcji i finanse</h1>
    </div>

    <div class="p-4">
        <!-- Filtry -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status płatności</label>
                    <select id="filterStatus" class="w-full border border-gray-300 rounded p-2 text-sm">
                        <option value="">— Wszystkie statusy —</option>
                        <option value="zapłacone">Zapłacone</option>
                        <option value="oczekuje">Oczekujące</option>
                    </select>
                </div>

                <!-- Date from -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Od daty</label>
                    <input type="date" id="filterDateFrom" class="w-full border border-gray-300 rounded p-2 text-sm">
                </div>

                <!-- Date to -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Do daty</label>
                    <input type="date" id="filterDateTo" class="w-full border border-gray-300 rounded p-2 text-sm">
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button id="filterBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                    Filtruj
                </button>
                <button id="resetBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium">
                    Resetuj
                </button>
            </div>
        </div>

        <!-- Tabela -->
        <form id="bulkPaymentForm" method="POST" action="{{ route('payments.bulkMarkPaid') }}">
            @csrf
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="selectAll" class="text-sm font-medium text-gray-700">Zaznacz wszystkie</label>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium" id="markPaidBtn" disabled>
                        Oznacz jako zapłacone (<span id="selectedCount">0</span>)
                    </button>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800 w-12"></th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Uczeń</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Lekcja</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Data</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Czas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kwota</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                        </tr>
                    </thead>
                    <tbody id="lessonsTable">
                        @forelse ($lessons as $lesson)
                            @php
                                $p = $lesson->payment;
                                $statusClass = $p ? 
                                    ($p->status === 'zapłacone' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700')
                                    : 'bg-gray-100 text-gray-700';
                                $statusLabel = $p ? ucfirst($p->status) : 'Brak płatności';
                            @endphp
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition" data-status="{{ $p?->status ?? 'none' }}" data-date="{{ $lesson->start }}" data-amount="{{ $p ? $p->amount : 0 }}">
                                <td class="px-6 py-4">
                                    @if ($p && $p->status === 'oczekuje')
                                        <input type="checkbox" name="payment_ids[]" value="{{ $p->id }}" class="payment-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-800">
                                    <a href="{{ route('dashboard.show', $lesson->student->id) }}" class="hover:text-blue-600">
                                        {{ $lesson->student->name }} {{ $lesson->student->surname }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-800">{{ $lesson->title ?? 'Lekcja' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($lesson->start)->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($lesson->start)->format('H:i') }}
                                    @if ($lesson->end)
                                        — {{ \Carbon\Carbon::parse($lesson->end)->format('H:i') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    @if ($p)
                                        {{ number_format($p->amount, 2) }} PLN
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Brak historii lekcji.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr class="font-semibold">
                            <td colspan="5" class="px-6 py-4 text-right text-gray-800">Suma otrzymana:</td>
                            <td class="px-6 py-4 text-green-600" id="totalPaidAmount">{{ number_format($totalPaid, 2) }} PLN</td>
                            <td></td>
                        </tr>
                        <tr class="font-semibold">
                            <td colspan="5" class="px-6 py-4 text-right text-gray-800">Oczekujące płatności:</td>
                            <td class="px-6 py-4 text-yellow-600" id="totalAwaitingAmount">{{ number_format($totalAwaiting, 2) }} PLN</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filterBtn');
            const resetBtn = document.getElementById('resetBtn');
            const filterStatus = document.getElementById('filterStatus');
            const filterDateFrom = document.getElementById('filterDateFrom');
            const filterDateTo = document.getElementById('filterDateTo');
            const lessonsTable = document.getElementById('lessonsTable');
            const rows = lessonsTable.querySelectorAll('tr[data-status]');

            function applyFilters() {
                const status = filterStatus.value;
                const dateFrom = filterDateFrom.value;
                const dateTo = filterDateTo.value;

                let totalPaid = 0;
                let totalAwaiting = 0;

                rows.forEach(row => {
                    let show = true;

                    // Filter by status
                    if (status && row.dataset.status !== status && row.dataset.status !== 'none') {
                        show = false;
                    }

                    // Filter by date range
                    const rowDate = row.dataset.date.split(' ')[0];
                    if (dateFrom && rowDate < dateFrom) {
                        show = false;
                    }
                    if (dateTo && rowDate > dateTo) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';

                    // Calculate totals for visible rows
                    if (show) {
                        const amount = parseFloat(row.dataset.amount) || 0;
                        const rowStatus = row.dataset.status;
                        if (rowStatus === 'zapłacone') {
                            totalPaid += amount;
                        } else if (rowStatus === 'oczekuje') {
                            totalAwaiting += amount;
                        }
                    }
                });

                // Update totals in footer
                document.getElementById('totalPaidAmount').textContent = totalPaid.toFixed(2) + ' PLN';
                document.getElementById('totalAwaitingAmount').textContent = totalAwaiting.toFixed(2) + ' PLN';
            }

            function resetFilters() {
                filterStatus.value = '';
                filterDateFrom.value = '';
                filterDateTo.value = '';
                rows.forEach(row => row.style.display = '');
                applyFilters();
            }

            filterBtn.addEventListener('click', applyFilters);
            resetBtn.addEventListener('click', resetFilters);

            // Checkbox handling
            const selectAllCheckbox = document.getElementById('selectAll');
            const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
            const markPaidBtn = document.getElementById('markPaidBtn');
            const selectedCount = document.getElementById('selectedCount');

            function updateSelectedCount() {
                const checked = document.querySelectorAll('.payment-checkbox:checked').length;
                selectedCount.textContent = checked;
                markPaidBtn.disabled = checked === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                paymentCheckboxes.forEach(checkbox => {
                    if (checkbox.closest('tr').style.display !== 'none') {
                        checkbox.checked = this.checked;
                    }
                });
                updateSelectedCount();
            });

            paymentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });
        });
    </script>

</x-layout>
