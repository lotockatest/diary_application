@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-purple-600 mb-6 text-center">Customization</h1>

    <div class="flex justify-center gap-4 mb-6">
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="routines">Routines</button>
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="activities">Activities</button>
        <button class="category-btn px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700" data-category="moods">Moods</button>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">Back</a>
    </div>

    <div id="categoryListContainer" class="bg-white rounded-2xl shadow p-6">
        <h2 id="categoryTitle" class="text-2xl font-semibold mb-4"></h2>

        <ul id="categoryList" class="list-disc list-inside space-y-2"></ul>

        <div id="bladeData" class="hidden">

            <ul data-category="moods">
                @foreach($moods as $mood)
                    <li>{{ $mood->name }}</li>
                @endforeach
            </ul>

            <ul data-category="routines">
                @foreach($routines as $routine)
                    <li>{{ $routine->name }}</li>
                @endforeach
            </ul>

            <ul data-category="activities">
                @foreach($activities as $activity)
                    <li>{{ $activity->name }}</li>
                @endforeach
            </ul>

        </div>
<!-- -->
        <button id="addNewBtn" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add New</button>

        <!--<div id="addFormContainer" class="mt-4 hidden">
            <input type="text" id="newItemInput" placeholder="Enter new name" class="border rounded-lg px-3 py-2 w-full mb-2">
            <p id="inputError" class="text-red-600 text-sm hidden mb-2">Name cannot be empty.</p>
            <div class="flex gap-2">
                <button id="saveNewItem" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save</button>
                <button id="cancelNewItem" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
            </div>-->
    <div id="addFormContainer" class="mt-4 hidden">
        <form id="addForm" method="POST">
            @csrf

            <input type="text" name="name" id="newItemInput"
                placeholder="Enter new name"
                class="border rounded-lg px-3 py-2 w-full mb-2">

            @error('name')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Save
                </button>

                <button type="button" id="cancelNewItem"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    //
    //
    let currentCategory = null;

    const categoryTitle = document.getElementById('categoryTitle');
    const categoryList = document.getElementById('categoryList');
    const addNewBtn = document.getElementById('addNewBtn');
    const addFormContainer = document.getElementById('addFormContainer');
    const newItemInput = document.getElementById('newItemInput');
    const inputError = document.getElementById('inputError');
    //const saveNewItem = document.getElementById('saveNewItem');
    const cancelNewItem = document.getElementById('cancelNewItem');
    const addForm = document.getElementById('addForm');

    function renderList() {
    const bladeList = document.querySelector(`#bladeData ul[data-category="${currentCategory}"]`);
    categoryList.innerHTML = bladeList.innerHTML;
    }

    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentCategory = btn.dataset.category;

            categoryTitle.textContent = `Manage ${currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1)}`;

            renderList();

            if (currentCategory === 'moods') {
                addForm.action = "{{ route('customization.mood.store') }}";
            }

            if (currentCategory === 'routines') {
                addForm.action = "{{ route('customization.routine.store') }}";
            }

            if (currentCategory === 'activities') {
                addForm.action = "{{ route('customization.activity.store') }}";
            }

            addFormContainer.classList.add('hidden');
            newItemInput.value = '';
            inputError.classList.add('hidden');
        });
    });

    addNewBtn.addEventListener('click', () => {
        if (!currentCategory) return alert('Please select a category first!');
        addFormContainer.classList.remove('hidden');
        newItemInput.focus();
    });

    /*saveNewItem.addEventListener('click', () => {
        const value = newItemInput.value.trim();
        if (!value) {
            inputError.classList.remove('hidden');
            return;
        }
        const li = document.createElement('li');
        li.textContent = value;
        categoryList.appendChild(li);

        addFormContainer.classList.add('hidden');
        newItemInput.value = '';
        inputError.classList.add('hidden');
    });*/

    cancelNewItem.addEventListener('click', () => {
        if (confirm('Are you sure you want to cancel?')) {
            addFormContainer.classList.add('hidden');
            newItemInput.value = '';
            inputError.classList.add('hidden');
        }
    });
});
</script>
@endsection