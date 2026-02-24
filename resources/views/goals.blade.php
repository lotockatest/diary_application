@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-purple-600">Goals</h1>

    <button id="addGoalBtn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 mb-4">Add Goal</button>
    <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">Back</a>

    <div id="goalsList" class="space-y-4">
        @forelse ($goals ?? [] as $goal)
            <div class="p-4 border rounded-lg flex justify-between items-center" data-id="{{ $goal->id }}" data-name="{{ $goal->name }}" data-count="{{ $goal->target_count }}" data-date="{{ $goal->target_date->format('Y-m-d') }}" data-type="{{ $goal->type }}" data-related="{{ $goal->related_id }}">
                <div>
                    <h3 class="font-semibold">{{ $goal->name }}</h3>
                    <p class="text-sm text-gray-500">Type: {{ $goal->routine->name ?? $goal->activity->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Target: {{ \Carbon\Carbon::parse($goal->target_date)->format('Y-m-d') }}</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-purple-600 h-2 rounded-full progress-bar" data-percentage="{{ $goal->percentage }}"></div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="editGoalBtn px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</button>
                    <form method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 deleteGoalBtn">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No goals made yet. Click "Add Goal" to create one.</p>
        @endforelse
    </div>

    <div id="goalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold text-purple-600 mb-4" id="goalModalTitle">Add Goal</h2>

            <form id="goalForm" method="POST" action="{{ route('goals.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="goal_id" id="goalId">

                <div class="mb-4">
                    <label class="block font-medium mb-1">Goal Name:</label>
                    <input type="text" name="name" id="goalName" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorName">Goal name is required!</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Count:</label>
                    <input type="number" name="target_count" id="goalCount" min="1" value="1" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorCount">Count is required!</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Target Date:</label>
                    <input type="date" name="target_date" id="goalDate" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorDate">Target date is required!</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Goal Type:</label>
                    <select name="type" id="goalType" class="w-full border rounded-lg px-3 py-2">
                        <option value="routine">Routine</option>
                        <option value="activity">Activity</option>
                    </select>
                    <p class="text-red-600 text-sm hidden mt-1" id="errorType">Choosing a goal type is required!</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Select Action:</label>
                    <select name="related_id" id="goalRelated" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Select --</option>
                        @foreach(auth()->user()?->routines ?? [] as $routine)
                            <option value="{{ $routine->id }}" data-type="routine">{{ $routine->name }}</option>
                        @endforeach
                        @foreach(auth()->user()?->activities ?? [] as $activity)
                            <option value="{{ $activity->id }}" data-type="activity">{{ $activity->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-600 text-sm hidden mt-1" id="errorRelated">Selecting an action is required!</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelGoalBtn" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addGoalBtn = document.getElementById('addGoalBtn');
    const goalModal = document.getElementById('goalModal');
    const cancelGoalBtn = document.getElementById('cancelGoalBtn');
    const goalForm = document.getElementById('goalForm');
    const goalModalTitle = document.getElementById('goalModalTitle');
    const goalId = document.getElementById('goalId');
    const goalName = document.getElementById('goalName');
    const goalCount = document.getElementById('goalCount');
    const goalDate = document.getElementById('goalDate');
    const errorName = document.getElementById('errorName');
    const errorCount = document.getElementById('errorCount');
    const errorDate = document.getElementById('errorDate');
    const goalType = document.getElementById('goalType');
    const goalRelated = document.getElementById('goalRelated');
    const errorType = document.getElementById('errorType');
    const errorRelated = document.getElementById('errorRelated');

    addGoalBtn.addEventListener('click', () => {
        goalModalTitle.textContent = 'Add Goal';
        goalId.value = '';
        goalName.value = '';
        goalCount.value = '';
        goalDate.value = '';
        hideErrors();
        goalModal.classList.remove('hidden');
    });

    cancelGoalBtn.addEventListener('click', () => {
        goalModal.classList.add('hidden');
    });

    goalForm.addEventListener('submit', (e) => {
        e.preventDefault();
        hideErrors();
        let valid = true;
        const today = new Date();
        const targetDateValue = new Date(goalDate.value);
        const diffTime = targetDateValue - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const countValue = parseInt(goalCount.value, 10);

        if(goalName.value.trim() === '') { 
        errorName.classList.remove('hidden'); 
        valid=false; 
        }

        if(isNaN(countValue) || countValue < 1) { 
            errorCount.textContent = 'Count must be at least 1!';
            errorCount.classList.remove('hidden'); 
            valid = false; 
        } else if(countValue > diffDays) {
            errorCount.textContent = 'Count cannot exceed days until target!';
            errorCount.classList.remove('hidden'); 
            valid = false; 
        }

        if(goalDate.value.trim() === '') { 
            errorDate.classList.remove('hidden'); 
            valid=false; 
        }

        if(goalType.value === '') {
            errorType.classList.remove('hidden');
            valid = false;
        }

        if(goalRelated.value === '') {
            errorRelated.classList.remove('hidden');
            valid = false;
        }

        if(!valid) return;

        //if(goalName.value.trim() === '') { errorName.classList.remove('hidden'); valid=false; }
        //if(goalCount.value.trim() === '') { errorCount.classList.remove('hidden'); valid=false; }
        //if(goalDate.value.trim() === '') { errorDate.classList.remove('hidden'); valid=false; }
        //if(!valid) return;
        goalForm.submit();
    });

    function hideErrors() {
        [errorName, errorCount, errorDate].forEach(err => err.classList.add('hidden'));
    }

    cancelGoalBtn.addEventListener('click', () => { 
        if(confirm('Are you sure you want to cancel?')) { 
            goalModal.classList.add('hidden'); 
        } 
    });

    document.querySelectorAll('.deleteGoalBtn').forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete the goal?')) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('.progress-bar').forEach(bar => {
        const percent = bar.dataset.percentage || 0;
        bar.style.width = percent + '%';
    });

    goalType.addEventListener('change', () => {
        const selectedType = goalType.value;
        Array.from(goalRelated.options).forEach(option => {
            if (!option.dataset.type) return;
            if (option.dataset.type === selectedType) {
                option.hidden = false;
            } else {
                option.hidden = true;
            }
        });
        goalRelated.value = "";
    });

    goalType.dispatchEvent(new Event('change'));

    document.querySelectorAll('.editGoalBtn').forEach(button => {
        button.addEventListener('click', function () {

            const goalCard = this.closest('[data-id]');
            const id = goalCard.dataset.id;
            const name = goalCard.dataset.name;
            const count = goalCard.dataset.count;
            const date = goalCard.dataset.date;
            const type = goalCard.dataset.type;
            const related = goalCard.dataset.related;

            goalModalTitle.textContent = 'Edit Goal';

            goalForm.action = `/goals/${id}`;
            document.getElementById('formMethod').value = 'PUT';

            goalId.value = id;
            goalName.value = name;
            goalCount.value = count;
            goalDate.value = date;
            goalType.value = type;
            goalType.dispatchEvent(new Event('change'));
            goalRelated.value = related;
            goalModal.classList.remove('hidden');
        });
    });
});
</script>
@endsection