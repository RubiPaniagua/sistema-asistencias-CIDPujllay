<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CID Pujllay - Control de Asistencia') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#0a203d] via-[#0f3b70] to-[#07172e] text-white antialiased min-h-screen flex items-center justify-center p-4 selection:bg-amber-400 selection:text-slate-900">

    <!-- Modulo Principal Integrado -->
    <div class="max-w-md w-full space-y-8 py-6">
        
        <!-- Logotipo y Membrete Central -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 shadow-xl">
                <span class="text-amber-400 font-black text-2xl tracking-wider">CID</span>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-white drop-shadow-sm">Centro de Investigación y Desarrollo</h1>
                <p class="text-xs text-amber-400 font-semibold tracking-wide mt-1">Control de Asistencia • Practicantes</p>
            </div>
        </div>

        <!-- Alerta de error -->
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3.5 rounded-xl text-xs font-medium backdrop-blur-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        @php
            $ipEmpresa = in_array(request()->ip(), ['192.168.1.100', '127.0.0.1']);
            $esAdmin = Auth::check() && Auth::user()->is_admin;
            $puedeMarcarPresencial = $esAdmin && $ipEmpresa;
        @endphp

        <!-- Opciones Principales de Asistencia -->
        <div class="space-y-4">
            
            <!-- Opción 1: Asistencia Presencial -->
            @if($puedeMarcarPresencial)
                <a href="{{ route('asistencia.presencial') }}" 
                   class="group flex items-center justify-between p-4 bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl hover:bg-white/20 hover:border-white/30 transition-all duration-200 shadow-lg">
                    <div class="flex items-center space-x-3.5">
                        <div class="p-2.5 bg-blue-600/80 text-white rounded-xl shadow-md group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-white">Asistencia Presencial</h2>
                            <p class="text-[11px] text-blue-200">Módulo central para marcado en oficina</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-blue-200 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <div class="flex items-center justify-between p-4 bg-slate-900/40 border border-white/5 rounded-2xl opacity-60 backdrop-blur-sm">
                    <div class="flex items-center space-x-3.5">
                        <div class="p-2.5 bg-slate-800 text-slate-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h2 class="text-sm font-bold text-slate-300">Asistencia Presencial</h2>
                                <span class="text-[9px] bg-amber-400/20 text-amber-300 border border-amber-400/30 px-2 py-0.5 rounded-full font-semibold">Restringido</span>
                            </div>
                            <p class="text-[11px] text-slate-400">Solo accesible desde la red de la empresa</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Opción 2: Asistencia Remota -->
            <a href="{{ route('asistencia.remota') }}" 
               class="group flex items-center justify-between p-4 bg-emerald-500/15 backdrop-blur-md border border-emerald-400/30 rounded-2xl hover:bg-emerald-500/25 hover:border-emerald-400/50 transition-all duration-200 shadow-lg">
                <div class="flex items-center space-x-3.5">
                    <div class="p-2.5 bg-emerald-500 text-slate-950 rounded-xl shadow-md group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white">Asistencia Remota</h2>
                        <p class="text-[11px] text-emerald-200">Marcado individual para personal en modalidad remota</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-emerald-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

        </div>

        <!-- Separador Discreto -->
        <div class="relative py-1">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
            <div class="relative flex justify-center text-[10px] uppercase tracking-widest"><span class="bg-[#0f3b70] px-3 text-slate-300 font-bold">Trámites</span></div>
        </div>

        <!-- Botón Secundario: Justificación -->
        <div>
            <a href="{{ route('justificaciones.create') }}" 
               class="w-full flex items-center justify-center space-x-2 py-3 px-4 bg-amber-400/10 hover:bg-amber-400/20 border border-amber-400/30 text-amber-300 rounded-2xl text-xs font-bold transition-all duration-150 backdrop-blur-sm">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Justificar Inasistencia o Tardanza</span>
            </a>
        </div>

        <!-- Pie de Página -->
        <div class="text-center text-[11px] text-slate-400 font-medium pt-2">
            CID Pujllay &copy; {{ date('Y') }}
        </div>

    </div>

</body>
</html>