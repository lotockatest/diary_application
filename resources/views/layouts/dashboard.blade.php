<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My App' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-[120vh] flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="w-full max-w-4xl mx-auto">
        @yield('content')
    </div>
</body>
</html>
