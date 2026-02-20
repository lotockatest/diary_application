@extends('layouts.app')

@section('content')

<div class="flex justify-center gap-4 mb-4">
    <a href="{{ route('customization') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-center">Customization</a>
    <a href="{{ route('statistics') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-center">Statistics</a>
    <a href="{{ route('goal') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-center">Goals</a>
    <form method="POST" action="{{ route('logout') }}"><!--{{ route('logout') }}-->
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center">Logout</button>
    </form>
</div>

<div id="calendar" class="bg-white rounded-2xl shadow-lg p-6">
    
    <div class="flex justify-between items-center mb-4">
        <button id="prevMonth" class="text-blue-600 hover:text-blue-800">&lt;</button>
        <h2 id="monthYear" class="text-xl font-bold text-center text-purple-600"></h2>
        <button id="nextMonth" class="text-blue-600 hover:text-blue-800">&gt;</button>
    </div>
    
    <div class="grid grid-cols-7 text-center text-sm font-semibold text-gray-500 mb-2">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
    </div>

    <div id="days" class="grid grid-cols-7 gap-2 text-center"></div>
</div>

<div id="entryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold text-center text-purple-600 mb-4" id="modalDate"></h2>

        <form method="POST" action="{{ route('entry.store') }}">
            @csrf

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="hidden" name="date" id="date">
        
        <div class="mb-4">
            <label class="block font-medium text-purple-600 mb-1">Mood for the day:</label>
            <select id="mood"  name="mood" class="w-full border rounded-lg px-3 py-2">
                @foreach($moods as $mood)
                    <option value="{{ $mood->name }}">{{ $mood->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-purple-600 mb-1">Activities:</label>
            <div class="flex flex-wrap gap-2">
                @foreach($activities as $activity)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="activities[]" value="{{ $activity->name }}" class="hidden peer">
                        <div class="flex items-center gap-2 border rounded-lg px-3 py-2
                                    peer-checked:bg-purple-100 peer-checked:border-purple-600 transition-colors duration-150">
                            <x-dynamic-component :component="'heroicon-s-' . ($activity->icon ?? 'face-smile')" class="w-5 h-5 text-purple-600"/>
                            <span>{{ $activity->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block font-medium text-purple-600 mb-1">Routines:</label>
            <div class="flex flex-wrap gap-2">
                @foreach($routines as $routine)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="routines[]" value="{{ $routine->name }}" class="hidden peer">
                        <div class="flex items-center gap-2 border rounded-lg px-3 py-2
                                    peer-checked:bg-purple-100 peer-checked:border-purple-600 transition-colors duration-150">
                            <x-dynamic-component :component="'heroicon-s-' . ($routine->icon ?? 'face-smile')" class="w-5 h-5 text-purple-600"/>
                            <span>{{ $routine->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-purple-600 mb-1">Notes:</label>
            <textarea id="notes" name="notes" rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" id="closeModal" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button type="submit" id="saveEntry" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Save Entry</button>
        </div>

        <div id="entryData" data-entries='@json($entries ?? [])'></div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const savedEntries = JSON.parse(document.getElementById('entryData').dataset.entries);
    const monthYear = document.getElementById('monthYear');
    const daysContainer = document.getElementById('days');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();

    function updateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        monthYear.textContent = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

        daysContainer.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();

        
        for (let i = 0; i < firstDay; i++) {
            const blank = document.createElement('div');
            daysContainer.appendChild(blank);
        }

        
        for (let i = 1; i <= lastDate; i++) {
            const day = document.createElement('div');
            day.textContent = i;
            day.className = 'p-2 rounded-lg hover:bg-blue-100 cursor-pointer';
            
            const today = new Date();
            if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                day.className += ' bg-purple-200 font-bold';
            }
            
            day.addEventListener('click', () => openModal(i));
            daysContainer.appendChild(day);
        }
        
    }

    prevMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar();
    });

    nextMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar();
    });

    updateCalendar();

 
const entryModal = document.getElementById('entryModal');
const modalDate = document.getElementById('modalDate');
const closeModalBtn = document.getElementById('closeModal');
const saveEntryBtn = document.getElementById('saveEntry');

function openModal(day) {
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const formattedDay = String(day).padStart(2, '0');

    const fullDate = `${year}-${month}-${formattedDay}`;

    document.getElementById('date').value = fullDate;
    modalDate.textContent = `Day: ${day}`;

    document.querySelector('select[name="mood"]').value = 'Happy';
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelector('textarea[name="notes"]').value = '';

    const existingEntry = savedEntries.find(entry => {
        return entry.date.startsWith(fullDate);
    });

    const moodSelect = document.querySelector('select[name="mood"]');
    const activities = document.querySelectorAll('input[name="activities[]"]');
    const routines = document.querySelectorAll('input[name="routines[]"]');
    const notes = document.querySelector('textarea[name="notes"]');

    moodSelect.value = 'Happy';
    activities.forEach(cb => cb.checked = false);
    routines.forEach(cb => cb.checked = false);
    notes.value = '';

    if (existingEntry) {

        document.querySelector('select[name="mood"]').value = existingEntry.mood;

        existingEntry.activities.forEach(activity => {
            const checkbox = document.querySelector(`input[name="activities[]"][value="${activity}"]`);
            if (checkbox) checkbox.checked = true;
        });

        existingEntry.routines.forEach(routine => {
            const checkbox = document.querySelector(`input[name="routines[]"][value="${routine}"]`);
            if (checkbox) checkbox.checked = true;
        });

        document.querySelector('textarea[name="notes"]').value = existingEntry.notes ?? '';
    }

    const today = new Date();
    const isToday = fullDate === today.toISOString().split('T')[0];

    moodSelect.disabled = !isToday;
    activities.forEach(cb => cb.disabled = !isToday);
    routines.forEach(cb => cb.disabled = !isToday);
    notes.readOnly = !isToday;
    saveEntryBtn.style.display = isToday ? 'inline-block' : 'none';

    entryModal.classList.remove('hidden');
}

closeModalBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to cancel?')) {
            entryModal.classList.add('hidden');
        }
});

});
</script>

@endsection
