@extends('user.layout')

@section('title', 'Mi Perfil')

@section('content')

<h1 class="text-xl font-bold mb-6">Mi Perfil</h1>

<div class="bg-surface p-6 rounded-xl max-w-md">

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf

        <div>
            <label>Nombre</label>
            <input type="text" name="name"
                   value="{{ auth()->user()->name }}"
                   class="w-full bg-background p-2 rounded">
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email"
                   value="{{ auth()->user()->email }}"
                   class="w-full bg-background p-2 rounded">
        </div>

        <button class="bg-primary px-4 py-2 rounded">
            Guardar Cambios
        </button>

    </form>

</div>

@endsection