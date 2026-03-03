<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'GameLink')</title>
    <!-- CSS / Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-900 text-white">
    
    <!-- Navbar sencilla -->
    <nav class="p-4 bg-gray-800 border-b border-gray-700">
        <div class="container mx-auto">
            <a href="/" class="text-2xl font-bold text-blue-500">GameLink</a>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container mx-auto p-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="p-4 text-center text-gray-500">
        &copy; 2026 GameLink
    </footer>
</body>
</html>