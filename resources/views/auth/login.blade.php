@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login</h2>

<form class="space-y-4" action="{{ route('home') }}" method="get"> <!--remove when making db-->
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
        Login
    </button>
</form>

<p class="text-sm text-center mt-4">
    Don’t have an account?
    <a href="{{ route('register') }}" class="text-purple-600 hover:underline">Register here</a>
</p>
@endsection
