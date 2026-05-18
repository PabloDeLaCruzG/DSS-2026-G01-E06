@extends('admin.layouts.layout')

@section('title', 'Vendedores Profesionales')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-text-main">Vendedores Profesionales</h1>
        <p class="text-sm text-text-muted mt-1">Gestiona las solicitudes de verificación y los socios activos.</p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- SOLICITUDES PENDIENTES --}}
    <div>
        <h2 class="text-lg font-semibold text-text-main mb-3 flex items-center gap-2">
            🕐 Solicitudes Pendientes
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-400">
                {{ $pending->count() }}
            </span>
        </h2>

        <div class="bg-surface rounded-xl border border-border overflow-x-auto">
            @if($pending->isEmpty())
                <div class="px-5 py-10 text-center text-text-muted text-sm">
                    No hay solicitudes pendientes.
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-xs text-text-muted uppercase tracking-wide">
                            <th class="text-left px-5 py-3">Empresa</th>
                            <th class="text-left px-5 py-3">CIF</th>
                            <th class="text-left px-5 py-3">Usuario</th>
                            <th class="text-left px-5 py-3">Web</th>
                            <th class="text-left px-5 py-3">Documento</th>
                            <th class="text-right px-5 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($pending as $profile)
                        <tr class="hover:bg-background/50 transition">
                            <td class="px-5 py-4 font-semibold text-text-main">{{ $profile->company_name }}</td>
                            <td class="px-5 py-4 font-mono text-text-muted text-xs">{{ $profile->cif }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-text-main text-xs font-medium">{{ $profile->user->name }}</p>
                                        <p class="text-text-muted text-xs">{{ $profile->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                @if($profile->website)
                                    <a href="{{ $profile->website }}" target="_blank"
                                       class="text-accent hover:underline truncate max-w-[140px] block">
                                        {{ $profile->website }}
                                    </a>
                                @else
                                    <span class="text-text-muted">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ Storage::url($profile->verification_docs) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-xs text-primary hover:underline">
                                    📄 Ver PDF
                                </a>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.professionals.verify', $profile) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap">
                                            Verificar
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.professionals.reject', $profile) }}" method="POST"
                                          data-confirm="¿Rechazar y eliminar la solicitud de {{ addslashes($profile->company_name) }}?">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 text-xs font-semibold rounded-lg transition whitespace-nowrap">
                                            Descartar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- SOCIOS VERIFICADOS --}}
    <div>
        <h2 class="text-lg font-semibold text-text-main mb-3 flex items-center gap-2">
            ✔ Socios Verificados
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400">
                {{ $verified->count() }}
            </span>
        </h2>

        <div class="bg-surface rounded-xl border border-border overflow-x-auto">
            @if($verified->isEmpty())
                <div class="px-5 py-10 text-center text-text-muted text-sm">
                    No hay socios verificados todavía.
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border text-xs text-text-muted uppercase tracking-wide">
                            <th class="text-left px-5 py-3">Empresa</th>
                            <th class="text-left px-5 py-3">CIF</th>
                            <th class="text-left px-5 py-3">Usuario</th>
                            <th class="text-left px-5 py-3">Web</th>
                            <th class="text-left px-5 py-3">Verificado</th>
                            <th class="text-right px-5 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($verified as $profile)
                        <tr class="hover:bg-background/50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-text-main">{{ $profile->company_name }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono text-text-muted text-xs">{{ $profile->cif }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-white shrink-0">
                                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-text-main text-xs font-medium">{{ $profile->user->name }}</p>
                                        <p class="text-text-muted text-xs">{{ $profile->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs max-w-[160px]">
                                @if($profile->website)
                                    <a href="{{ $profile->website }}" target="_blank"
                                       class="text-accent hover:underline truncate block">
                                        {{ $profile->website }}
                                    </a>
                                @else
                                    <span class="text-text-muted">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-text-muted">
                                {{ $profile->updated_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 flex justify-end">
                                <form action="{{ route('admin.professionals.reject', $profile) }}" method="POST"
                                      data-confirm="¿Revocar la verificación de {{ addslashes($profile->company_name) }}?">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 text-xs font-semibold rounded-lg transition whitespace-nowrap">
                                        Descartar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection
