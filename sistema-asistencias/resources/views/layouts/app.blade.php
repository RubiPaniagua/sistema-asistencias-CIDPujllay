<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Asistencias - CID Pujllay')</title>
    <!-- Tailwind CSS desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gradient-to-b from-[#0a203d] via-[#0f3b70] to-[#07172e] text-white font-sans min-h-screen flex flex-col justify-between antialiased">

    <!-- Contenido Dinámico Centrado -->
    <main class="flex-grow flex items-center justify-center p-4">
        @yield('content')
    </main>

    <!-- Footer Discreto -->
    <footer class="text-center py-4 text-xs text-slate-400/60 border-t border-white/5 backdrop-blur-sm">
        &copy; {{ date('Y') }} CID Pujllay. Todos los derechos reservados.
    </footer>

    @stack('scripts')
</body>
</html>