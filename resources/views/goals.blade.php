@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold mb-4 text-purple-600">Goals</h1>

    <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">Back</a>

    <button id="addGoalBtn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 mb-4">Add Goal</button>

    <div id="goalsList" class="space-y-4">
        @if (isset($goals) && count($goals) > 0)
            @foreach ($goals as $goal)
                <div class="p-4 border rounded-lg flex justify-between items-center" data-id="{{ $goal->id }}">
                    <div>
                        <h3 class="font-semibold">{{ $goal->name }}</h3>
                        <p class="text-sm text-gray-500">Actions: {{ $goal->actions }}</p>
                        <p class="text-sm text-gray-500">Target: {{ $goal->target_date }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-purple-600 h-2 rounded-full progress-bar" data-progress="width: {{ $goal->progress ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <button class="editGoalBtn px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</button>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">No goals made yet. Click "Add Goal" to create one.</p>
        @endif
    </div>

    <div id="goalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold text-purple-600 mb-4" id="goalModalTitle">Add Goal</h2>

            <form id="goalForm">
                @csrf
                <input type="hidden" id="goalId">

                <div class="mb-4">
                    <label class="block font-medium mb-1">Goal Name:</label>
                    <input type="text" id="goalName" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorName">Required</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Actions:</label>
                    <input type="text" id="goalActions" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorActions">Required</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Count:</label>
                    <input type="number" id="goalCount" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorCount">Required</p>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Target Date:</label>
                    <input type="date" id="goalDate" class="w-full border rounded-lg px-3 py-2">
                    <p class="text-red-600 text-sm hidden mt-1" id="errorDate">Required</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelGoalBtn" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" id="saveGoalBtn" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">Save</button>
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
    const goalActions = document.getElementById('goalActions');
    const goalCount = document.getElementById('goalCount');
    const goalDate = document.getElementById('goalDate');

    const errorName = document.getElementById('errorName');
    const errorActions = document.getElementById('errorActions');
    const errorCount = document.getElementById('errorCount');
    const errorDate = document.getElementById('errorDate');

    const goalsList = document.getElementById('goalsList');

    addGoalBtn.addEventListener('click', () => {
        goalModalTitle.textContent = 'Add Goal';
        goalId.value = '';
        goalName.value = '';
        goalActions.value = '';
        goalCount.value = '';
        goalDate.value = '';
        hideErrors();
        goalModal.classList.remove('hidden');
    });

    cancelGoalBtn.addEventListener('click', () => {
        if(confirm('Are you sure you want to cancel?')) {
            goalModal.classList.add('hidden');
        }
    });

    goalForm.addEventListener('submit', (e) => {
        e.preventDefault();
        hideErrors();
        let valid = true;

        if(goalName.value.trim() === '') { errorName.classList.remove('hidden'); valid=false; }
        if(goalActions.value.trim() === '') { errorActions.classList.remove('hidden'); valid=false; }
        if(goalCount.value.trim() === '') { errorCount.classList.remove('hidden'); valid=false; }
        if(goalDate.value.trim() === '') { errorDate.classList.remove('hidden'); valid=false; }

        if(!valid) return;

        const id = goalId.value || Date.now();
        const progress = 0;
        const goalHtml = `
            <div class="p-4 border rounded-lg flex justify-between items-center" data-id="${id}">
                <div>
                    <h3 class="font-semibold">${goalName.value}</h3>
                    <p class="text-sm text-gray-500">Actions: ${goalActions.value}</p>
                    <p class="text-sm text-gray-500">Target: ${goalDate.value}</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: ${progress}%"></div>
                    </div>
                </div>
                <button class="editGoalBtn px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</button>
            </div>
        `;

        if(goalId.value) {
            const existingGoal = goalsList.querySelector(`[data-id='${goalId.value}']`);
            existingGoal.outerHTML = goalHtml;
        } else {
            goalsList.insertAdjacentHTML('beforeend', goalHtml);
        }

        goalModal.classList.add('hidden');
        attachEditHandlers();
    });

    function hideErrors() {
        [errorName, errorActions, errorCount, errorDate].forEach(err => err.classList.add('hidden'));
    }

    function attachEditHandlers() {
        goalsList.querySelectorAll('.editGoalBtn').forEach(btn => {
            btn.removeEventListener('click', editGoalHandler);
            btn.addEventListener('click', editGoalHandler);
        });
    }

    function editGoalHandler(e) {
        const goalDiv = e.target.closest('div[data-id]');
        goalId.value = goalDiv.dataset.id;
        goalName.value = goalDiv.querySelector('h3').textContent;
        goalActions.value = goalDiv.querySelector('p:nth-child(2)').textContent.replace('Actions: ','');
        goalDate.value = goalDiv.querySelector('p:nth-child(3)').textContent.replace('Target: ','');
        goalCount.value = goalDiv.querySelector('p:nth-child(4)')?.textContent || 0;

        goalModalTitle.textContent = 'Edit Goal';
        hideErrors();
        goalModal.classList.remove('hidden');
    }

    attachEditHandlers();

});
</script>
@endsection