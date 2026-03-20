@extends('layouts.app')

@section('content')
<!-- Title -->
<h2 class="text-2xl font-bold text-center text-purple-600 mb-6">Register</h2>
<!-- Registration form -->
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <!-- Username input (with error display) -->
    <div>
        <label class="block text-sm font-medium">Username</label>
        <input type="text" name="username" required
               value="{{ old('username') }}"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        @error('username')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <!-- Email input (with error display) -->
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" required
               value="{{ old('email') }}"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <!-- Password input (with error display) -->
    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <!-- Submit button -->
    <button type="submit"
            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
        Register
    </button>
</form>
<!-- Route to login -->
<p class="text-sm text-center mt-4">
    Already have an account?
    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
</p>
@endsection