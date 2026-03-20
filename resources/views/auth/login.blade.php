@extends('layouts.app')

@section('content')
<!-- Title -->
<h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login</h2>
<!-- Login form -->
<form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
    @csrf
    <!-- Email input -->
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>
    <!-- Password input -->
    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>
    <!-- Error display -->
    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Submit button -->
    <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
        Login
    </button>
</form>
<!-- Route to registration page -->
<p class="text-sm text-center mt-4">
    Don’t have an account?
    <a href="{{ route('register') }}" class="text-purple-600 hover:underline">Register here</a>
</p>
@endsection