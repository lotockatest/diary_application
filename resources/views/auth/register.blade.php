@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-center text-purple-600 mb-6">Register</h2>

<form class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Username</label>
        <input type="text" name="username" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
    </div>

    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
    </div>

    <button type="submit"
            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
        Register
    </button>
</form>

<p class="text-sm text-center mt-4">
    Already have an account?
    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
</p>
@endsection
