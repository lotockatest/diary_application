@extends('layouts.app')
@section('content')
<div class="bg-white rounded-2xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-purple-600 mb-4">Profile</h1>
        <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Back</a>
    </div>
    @if(session('success'))
        <div class="bg-blue-100 text-blue-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700">Email</h3>
        <p class="text-gray-600">{{ $user->email }}</p>
    </div>
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700">Username</h3>
        <p class="text-gray-600">{{ $user->username }}</p>
    </div>
    <button onclick="toggleEdit()" 
        class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 mb-4">
        Edit Profile
    </button>
    <hr class="my-6">
    <div id="editForm" class="hidden">
        <h2 class="text-xl font-semibold text-purple-600 mb-4">Edit Profile</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium text-purple-600 mb-1">Username</label>
                <input type="text" name="username" value="{{ $user->username }}" 
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block font-medium text-purple-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" 
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            <button type="submit" 
                class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">
                Save
            </button>
        </form>
    </div>
    <hr class="my-6">
    <form method="POST" action="{{ route('profile.delete') }}">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
            onclick="return confirm('Are you sure you want to delete your profile?')">
            Delete Profile
        </button>
    </form>
</div>
<script>
function toggleEdit() {
    const form = document.getElementById('editForm');
    form.classList.toggle('hidden');
}
</script>
@endsection