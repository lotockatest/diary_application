@extends('layouts.app')

@section('content')
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
        
        <div class="mb-4">
            <label class="block font-medium mb-1">Mood for the day:</label>
            <select id="mood"  name="mood" class="w-full border rounded-lg px-3 py-2">
                <option>Happy</option>
                <option>Sad</option>
                <option>Neutral</option>
                <option>Excited</option>
                <option>Tired</option>
            </select>
        </div>

        
        <div class="mb-4">
            <label class="block font-medium mb-1">Activities:</label>
            <div class="flex flex-wrap gap-2">
                <label><input type="checkbox" name="activities[]" value="Workout" class="mr-1">Workout</label>
                <label><input type="checkbox" name="activities[]" value="Study" class="mr-1">Study</label>
                <label><input type="checkbox" name="activities[]" value="Work" class="mr-1">Work</label>
                <label><input type="checkbox" name="activities[]" value="Read" class="mr-1">Read</label>
            </div>
        </div>

        
        <div class="mb-4">
            <label class="block font-medium mb-1">Routines:</label>
            <div class="flex flex-wrap gap-2">
                <label><input type="checkbox" name="routines[]" value="Morning Routine" class="mr-1">Morning Routine</label>
                <label><input type="checkbox" name="routines[]" value="Evening Routine" class="mr-1">Evening Routine</label>
                <label><input type="checkbox" name="routines[]" value="Meditation" class="mr-1">Meditation</label>
            </div>
        </div>

        
        <div class="mb-4">
            <label class="block font-medium mb-1">Notes:</label>
            <textarea id="notes" name="notes" rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>

        
        <div class="flex justify-end gap-2">
            <button type="button" id="closeModal" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button type="submit" id="saveEntry" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Save Entry</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
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
    modalDate.textContent = `Day: ${day}`;
    entryModal.classList.remove('hidden');
}

closeModalBtn.addEventListener('click', () => {
    entryModal.classList.add('hidden');
});

});
</script>

@endsection
