{{-- resources/views/admin/users/_form.blade.php --}}
{{-- Campos reutilizables para crear/editar usuario --}}

<div class="space-y-4">
    {{-- Nombre --}}
    <div>
        <label class="block text-xs text-text-muted mb-1">Nombre completo</label>
        <input name="name" value="{{ old('name') }}" required
               class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
               @error('name') aria-invalid="true" @enderror />
        @error('name') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-xs text-text-muted mb-1">Correo electrónico</label>
        <input name="email" type="email" value="{{ old('email') }}" required
               class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
               @error('email') aria-invalid="true" @enderror />
        @error('email') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- Rol --}}
        <div>
            <label class="block text-xs text-text-muted mb-1">Rol</label>
            <select name="role" required class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                    @error('role') aria-invalid="true" @enderror>
                @php $rolesLocal = $roles ?? ['user' => 'Usuario', 'admin' => 'Administrador']; @endphp
                @foreach($rolesLocal as $key => $label)
                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('role') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
        </div>

        {{-- Departamento --}}
        <div>
            <label class="block text-xs text-text-muted mb-1">Departamento</label>
            <input name="department" value="{{ old('department') }}" class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                   @error('department') aria-invalid="true" @enderror />
            @error('department') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Contraseña --}}
    <div>
        <label class="block text-xs text-text-muted mb-1">Contraseña</label>
        <input name="password" type="password" required class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
               @error('password') aria-invalid="true" @enderror />
        @error('password') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
    </div>

    {{-- Confirmar contraseña --}}
    <div>
        <label class="block text-xs text-text-muted mb-1">Confirmar contraseña</label>
        <input name="password_confirmation" type="password" required class="w-full bg-background border border-border rounded px-3 py-2 text-text-main" />
    </div>
</div>