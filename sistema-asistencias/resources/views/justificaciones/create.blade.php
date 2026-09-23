<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Justificativa de Faltas e Atrasos - CID Pujllay</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#0a203d] via-[#0f3b70] to-[#07172e] text-white antialiased min-h-screen flex items-center justify-center p-4 selection:bg-amber-400 selection:text-slate-900">

    <div class="max-w-lg w-full py-8 space-y-6">
        
        <!-- Voltar para o menu -->
        <div>
            <a href="{{ route('welcome') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-blue-200 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Voltar ao Menu Principal</span>
            </a>
        </div>

        <!-- Cabeçalho do Formulário -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 shadow-xl mb-1">
                <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Formulário de Justificativa</h1>
            <p class="text-xs text-blue-200">Envie suas informações para análise de ausência ou atraso</p>
        </div>

        <!-- Mensagens de Sucesso ou Erro -->
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-200 p-4 rounded-2xl text-xs backdrop-blur-sm text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-2xl text-xs backdrop-blur-sm text-center font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card/Formulário Integrado -->
        <form action="{{ route('justificaciones.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/10 backdrop-blur-md border border-white/15 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl">
            @csrf

            <!-- 1. Identificação / ID ou DNI -->
            <div class="space-y-1.5">
                <label for="identificacion" class="block text-xs font-bold text-amber-300">
                    ID ou DNI do Praticante <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="identificacion" name="identificacion" required placeholder="Digite seu ID ou DNI" value="{{ old('identificacion') }}"
                        class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all">
                </div>
                @error('identificacion')
                    <span class="text-[10px] text-red-300">{{ $message }}</span>
                @enderror
            </div>

            <!-- 2. Tipo de Incidência e Data -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tipo -->
                <div class="space-y-1.5">
                    <label for="tipo" class="block text-xs font-bold text-amber-300">
                        Tipo de Falta <span class="text-red-400">*</span>
                    </label>
                    <select id="tipo" name="tipo" required
                        class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all">
                        <option value="" disabled selected class="bg-slate-900">Selecione...</option>
                        <option value="tardanza" class="bg-slate-900" {{ old('tipo') == 'tardanza' ? 'selected' : '' }}>Atraso / Tardança</option>
                        <option value="inasistencia" class="bg-slate-900" {{ old('tipo') == 'inasistencia' ? 'selected' : '' }}>Ausência / Inexistência</option>
                    </select>
                    @error('tipo')
                        <span class="text-[10px] text-red-300">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Data -->
                <div class="space-y-1.5">
                    <label for="fecha" class="block text-xs font-bold text-amber-300">
                        Data da Ausência/Atraso <span class="text-red-400">*</span>
                    </label>
                    <input type="date" id="fecha" name="fecha" required value="{{ old('fecha', date('Y-m-d')) }}"
                        class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all [color-scheme:dark]">
                    @error('fecha')
                        <span class="text-[10px] text-red-300">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- 3. Motivo / Descrição -->
            <div class="space-y-1.5">
                <label for="motivo" class="block text-xs font-bold text-amber-300">
                    Detalhe da Justificativa <span class="text-red-400">*</span>
                </label>
                <textarea id="motivo" name="motivo" rows="3" required placeholder="Explique brevemente o motivo..."
                    class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all resize-none">{{ old('motivo') }}</textarea>
                @error('motivo')
                    <span class="text-[10px] text-red-300">{{ $message }}</span>
                @enderror
            </div>

            <!-- 4. Anexo / Evidência (Opcional) -->
            <div class="space-y-1.5">
                <label for="evidencia" class="block text-xs font-bold text-amber-300">
                    Comprovante ou Evidência <span class="text-xs font-normal text-slate-300">(Opcional)</span>
                </label>
                <input type="file" id="evidencia" name="evidencia" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-400/20 file:text-amber-300 hover:file:bg-amber-400/30 file:transition-colors file:cursor-pointer border border-white/10 rounded-xl bg-slate-900/60 p-1">
                <p class="text-[10px] text-slate-400">Permitido: Imagens (JPG, PNG) ou arquivos PDF (máx. 5MB)</p>
                @error('evidencia')
                    <span class="text-[10px] text-red-300">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botão de Envio -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-3 px-4 bg-amber-400 hover:bg-amber-300 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 shadow-lg hover:shadow-amber-400/20 active:scale-[0.98]">
                    Enviar Justificativa
                </button>
            </div>

        </form>

        <div class="text-center text-[11px] text-slate-400 font-medium">
            CID Pujllay &copy; {{ date('Y') }}
        </div>

    </div>

</body>
</html>