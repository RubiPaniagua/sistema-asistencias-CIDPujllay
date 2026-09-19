<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Asistencias - CID Pujllay')</title>
    <!-- Tailwind CSS desde CDN para maquetación rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen flex flex-col">

    <!-- Navegación simple opcional -->
    <header class="bg-indigo-700 text-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">CID Pujllay - Asistencias</h1>
            <span id="reloj-servidor" class="text-sm bg-indigo-800 px-3 py-1 rounded-full"></span>
        </div>
    </header>

    <!-- Contenido Dinámico -->
    <main class="flex-grow container mx-auto p-4 flex flex-col justify-center">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 text-center py-3 text-sm text-gray-500">
        &copy; {{ date('Y') }} CID Pujllay. Todos los derechos reservados.
    </footer>

    @stack('scripts')
</body>
</html>