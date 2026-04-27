@extends('user.layout')

@section('title', 'Solicitar Cuenta Profesional')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold">Solicitar Cuenta de Vendedor Profesional</h1>
        <p class="text-text-muted text-sm mt-1">
            Como vendedor profesional verificado podrás publicar claves digitales con stock múltiple.
            Un administrador revisará tu documentación antes de activar tu cuenta.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-500/20 border border-red-500/30 text-red-400 p-4 rounded-lg text-sm">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('professional.store') }}" enctype="multipart/form-data"
          class="bg-surface rounded-xl border border-border p-6 space-y-5">
        @csrf

        <div>
            <label class="text-xs text-text-muted block mb-1">CIF / NIF de la empresa <span class="text-red-400">*</span></label>
            <input type="text" name="cif" value="{{ old('cif') }}"
                placeholder="B12345678"
                class="w-full bg-background border border-border px-3 py-2 rounded-lg text-sm">
        </div>

        <div>
            <label class="text-xs text-text-muted block mb-1">Nombre de la empresa <span class="text-red-400">*</span></label>
            <input type="text" name="company_name" value="{{ old('company_name') }}"
                placeholder="GameDistrib S.L."
                class="w-full bg-background border border-border px-3 py-2 rounded-lg text-sm">
        </div>

        <div>
            <label class="text-xs text-text-muted block mb-1">Sitio web <span class="text-text-muted">(opcional)</span></label>
            <input type="url" name="website" value="{{ old('website') }}"
                placeholder="https://miempresa.com"
                class="w-full bg-background border border-border px-3 py-2 rounded-lg text-sm">
        </div>

        <div>
            <label class="text-xs text-text-muted block mb-1">Documentación de verificación (PDF) <span class="text-red-400">*</span></label>
            <p class="text-xs text-text-muted mb-2">Sube el certificado de empresa, escrituras o cualquier documento oficial que acredite tu actividad.</p>
            <input type="file" name="verification_docs" accept=".pdf"
                class="w-full bg-background border border-border px-3 py-2 rounded-lg text-sm text-text-muted file:mr-3 file:bg-primary file:text-white file:border-0 file:rounded file:px-3 file:py-1 file:text-xs file:cursor-pointer">
        </div>

        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('profile.index') }}"
               class="px-4 py-2 border border-border rounded-lg text-sm text-text-muted hover:text-text-main">
                Cancelar
            </a>
            <button type="submit"
                class="bg-primary hover:bg-primary-hover px-5 py-2 rounded-lg text-white font-medium text-sm transition">
                Enviar solicitud
            </button>
        </div>

    </form>

</div>

@endsection
