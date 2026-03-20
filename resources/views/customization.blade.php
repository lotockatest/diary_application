@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Title -->
    <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center">Customization</h1>
    <!-- Category buttons -->
    <div class="flex justify-center gap-4 mb-6">
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="routines">Routines</button>
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="activities">Activities</button>
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="moods">Moods</button>
        <!-- Back button -->
        <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">Back</a>
    </div>
    <!-- Main container -->
    <div id="categoryListContainer" class="bg-white rounded-2xl shadow p-6">
        <!-- Dynamic title based on chosen category -->
        <h2 id="categoryTitle" class="text-2xl font-semibold mb-4"></h2>
        <!-- Dynamic list filled using JS -->
        <ul id="categoryList" class="list-disc list-inside space-y-2"></ul>
        <!-- Used by JS to create list -->
        <div id="bladeData" class="hidden">
            <!-- Mood list -->
            <ul data-category="moods">
                @foreach($moods as $mood)
                    <li class="flex items-center gap-2">
                        <div class="flex items-center gap-2">
                            <x-dynamic-component :component="'heroicon-s-' . ($mood->icon ?? 'face-smile')" class="w-5 h-5 text-purple-600"/>
                            <span>{{ $mood->name }}</span>
                        </div>
                        <div class="flex gap-1">
                            <!-- Edit button and delete form -->
                            <button type="button" class="edit-btn text-blue-600 hover:text-blue-800" data-id="{{ $mood->id }}" data-name="{{ $mood->name }}" data-icon="{{ $mood->icon ?? 'face-smile' }}"><x-dynamic-component :component="'heroicon-s-pencil-square'" class="w-5 h-5"/></button>
                            <form action="{{ route('customization.mood.destroy', $mood) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold">&times;</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <!-- Same process for routines and activities -->
            <ul data-category="routines">
                @foreach($routines as $routine)
                    <li class="flex items-center gap-2">
                        <div class="flex items-center gap-2">
                            <x-dynamic-component :component="'heroicon-s-' . ($routine->icon ?? 'face-smile')" class="w-5 h-5 text-purple-600"/>
                            <span>{{ $routine->name }}</span>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" class="edit-btn text-blue-600 hover:text-blue-800" data-id="{{ $routine->id }}" data-name="{{ $routine->name }}" data-icon="{{ $routine->icon ?? 'face-smile' }}"><x-dynamic-component :component="'heroicon-s-pencil-square'" class="w-5 h-5"/></button>
                            <form action="{{ route('customization.routine.destroy', $routine) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold">&times;</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <ul data-category="activities">
                @foreach($activities as $activity)
                    <li class="flex items-center gap-2">
                        <div class="flex items-center gap-2">
                            <x-dynamic-component :component="'heroicon-s-' . ($activity->icon ?? 'face-smile')" class="w-5 h-5 text-purple-600"/>
                            <span>{{ $activity->name }}</span>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" class="edit-btn text-blue-600 hover:text-blue-800 font-bold" data-id="{{ $activity->id }}" data-name="{{ $activity->name }}" data-icon="{{ $activity->icon ?? 'face-smile' }}"><x-dynamic-component :component="'heroicon-s-pencil-square'" class="w-5 h-5"/></button>
                            <form action="{{ route('customization.activity.destroy', $activity) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-blue-600 hover:text-blue-800 font-bold">&times;</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

        </div>
        <!-- Button to show the adding new item form -->
        <button id="addNewBtn" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add New</button>
    <!-- The adding new item form -->
    <div id="addFormContainer" class="mt-4 hidden">
        <form id="addForm" method="POST">
            @csrf
            <!-- Name input -->
            <input type="text" name="name" id="newItemInput"
                placeholder="Enter new name"
                class="border rounded-lg px-3 py-2 w-full mb-2">
            <!-- Name error display -->
            @error('name')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror
            <!-- Icon selection -->
            <div class="mb-3">
                <label class="block mb-1 font-medium">Choose Icon</label>
                <!-- Grid with all options -->
                <div class="grid grid-cols-6 gap-3">
                    @php
                        $icons = ['face-smile','face-frown','hand-thumb-down','hand-thumb-up','heart','bolt','academic-cap','home','star','banknotes',
                        'beaker','battery-0','battery-100','beaker','bolt-slash','book-open', 'briefcase',
                        'calculator','camera','chat-bubble-bottom-center-text','chat-bubble-left-right',
                        'cloud','computer-desktop','fire','globe-asia-australia','paint-brush','microphone',
                        'musical-note','puzzle-piece','rocket-launch','sparkles','shopping-bag','trophy',
                        'users','tv','video-camera','wrench-screwdriver'];
                    @endphp

                    @foreach($icons as $icon)
                        <label class="cursor-pointer">
                            <input type="radio" name="icon" value="{{ $icon }}" class="hidden peer" required>
                            <!-- Icon preview -->
                            <div class="flex items-center justify-center p-2 rounded-lg border peer-checked:bg-purple-100 peer-checked:border-purple-600">
                                <x-dynamic-component :component="'heroicon-s-' . $icon" class="w-6 h-6 text-purple-600"/>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            <!-- Form buttons (save and cancel) -->
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save</button>
                <button type="button" id="cancelNewItem" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
            </div>
        </form>
    </div>
    <!-- Edit form (similar to add form) -->
    <div id="editFormContainer" class="mt-4 hidden">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editItemName" class="border rounded-lg px-3 py-2 w-full mb-2" required>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Choose Icon</label>
                    <div class="grid grid-cols-6 gap-3" id="editIconGrid">
                        @foreach($icons as $icon)
                            <label class="cursor-pointer">
                                <input type="radio" name="icon" value="{{ $icon }}" class="hidden peer">
                                <div class="flex items-center justify-center p-2 rounded-lg border
                                            peer-checked:bg-purple-100 peer-checked:border-purple-600">
                                    <x-dynamic-component :component="'heroicon-s-' . $icon" class="w-6 h-6 text-purple-600"/>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save</button>
                    <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    //Store current selected category
    let currentCategory = null;
    //DOM elements
    const categoryTitle = document.getElementById('categoryTitle');
    const categoryList = document.getElementById('categoryList');
    const addNewBtn = document.getElementById('addNewBtn');
    const addFormContainer = document.getElementById('addFormContainer');
    const newItemInput = document.getElementById('newItemInput');
    const inputError = document.getElementById('inputError');
    const cancelNewItem = document.getElementById('cancelNewItem');
    const addForm = document.getElementById('addForm');
    //Render list from the hidden blade
    function renderList() {
    const bladeList = document.querySelector(`#bladeData ul[data-category="${currentCategory}"]`);
    categoryList.innerHTML = bladeList.innerHTML;
    }
    //Used for category switching
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentCategory = btn.dataset.category;
            //Update the title
            categoryTitle.textContent = `Manage ${currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1)}`;

            renderList();
            //Set the correct form action
            if (currentCategory === 'moods') {
                addForm.action = "{{ route('customization.mood.store') }}";
            }

            if (currentCategory === 'routines') {
                addForm.action = "{{ route('customization.routine.store') }}";
            }

            if (currentCategory === 'activities') {
                addForm.action = "{{ route('customization.activity.store') }}";
            }
            //Reset the form
            addFormContainer.classList.add('hidden');
            newItemInput.value = '';
            inputError.classList.add('hidden');
        });
    });
    //Show the add button
    addNewBtn.addEventListener('click', () => {
        if (!currentCategory) return alert('Please select a category first!');
        addFormContainer.classList.remove('hidden');
        newItemInput.focus();
    });
    //Cancel message when cancelling form
    cancelNewItem.addEventListener('click', () => {
        if (confirm('Are you sure you want to cancel?')) {
            addFormContainer.classList.add('hidden');
            newItemInput.value = '';
            inputError.classList.add('hidden');
        }
    });
    //Event deligation for editing
   categoryList.addEventListener('click', function (e) {
        const btn = e.target.closest('.edit-btn');
        if (!btn) return;
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const icon = btn.dataset.icon;
        //Set the correct route for updating
        const editFormContainer = document.getElementById('editFormContainer');
        const editForm = document.getElementById('editForm');
        const editItemName = document.getElementById('editItemName');
        if (currentCategory === 'routines') {
            editForm.action = `/customization/routine/${id}/update`;
        } else if (currentCategory === 'activities') {
            editForm.action = `/customization/activity/${id}/update`;
        } else if (currentCategory === 'moods') {
            editForm.action = `/customization/mood/${id}/update`;
        }
        //Fill the form with existing values
        editItemName.value = name;
        document.querySelectorAll('#editIconGrid input').forEach(input => {
            input.checked = input.value === icon;
        });
        //Show the form
        editFormContainer.classList.remove('hidden');
    });
});
</script>
@endsection