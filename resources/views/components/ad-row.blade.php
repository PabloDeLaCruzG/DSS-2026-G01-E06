@props(['ad'])

<div class="grid grid-cols-12 gap-4 p-4 items-center hover:bg-gray-700/50 transition duration-150">

    <!-- VENDEDOR -->
    <div class="col-span-5 md:col-span-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center font-bold text-blue-200 border border-blue-700">
            {{ substr($ad->user->name, 0, 1) }}
        </div>
        <div>
            <div class="flex items-center gap-1">
                <span class="font-bold text-white text-sm truncate">{{ $ad->user->name }}</span>
                @if($ad->user->isProfessional())
                <span class="text-blue-400" title="Verificado">✓</span>
                @endif
            </div>
            <div class="text-xs text-green-400">98% positivo • {{ rand(10, 500) }} ventas</div>
        </div>
    </div>

    <!-- ESTADO -->
    <div class="hidden md:block md:col-span-2 text-center">
        @if($ad->condition == 'NEW')
        <span class="bg-green-900 text-green-300 text-xs px-2 py-1 rounded font-bold">Nuevo</span>
        @else
        <span class="bg-yellow-900 text-yellow-300 text-xs px-2 py-1 rounded font-bold">Usado</span>
        @endif
    </div>

    <!-- FORMATO -->
    <div class="hidden md:block md:col-span-2 text-center text-sm text-gray-300">
        @if($ad->format == 'DIGITAL_KEY')
        <span class="flex items-center justify-center gap-1">🔑 Código</span>
        @else
        <span class="flex items-center justify-center gap-1">💿 Físico</span>
        @endif
    </div>

    <!-- PRECIO -->
    <div class="col-span-4 md:col-span-2 text-right">
        <div class="text-lg font-bold text-white">{{ $ad->price }}€</div>
        <div class="text-xs text-gray-500">
            @if($ad->format == 'PHYSICAL')
            +2.99€ envío
            @else
            Entrega inmediata
            @endif
        </div>
    </div>

    <!-- BOTÓN -->
    <div class="col-span-3 md:col-span-2 text-right">
        <button class="bg-purple-600 hover:bg-purple-500 text-white p-2 rounded-lg shadow transition">
            🛒
        </button>
    </div>
</div>