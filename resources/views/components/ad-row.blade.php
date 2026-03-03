@props(['ad'])

<div class="flex justify-between items-center bg-gray-800 p-4 rounded-lg mb-2 border border-gray-700">
    <div class="flex items-center gap-4">
        <!-- Avatar del vendedor -->
        <div class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center font-bold">
            {{ substr($ad->user->name, 0, 1) }}
        </div>
        <div>
            <h4 class="font-bold">{{ $ad->user->name }}</h4>
            <p class="text-sm text-gray-400">
                {{ $ad->condition }} • 
                @if($ad->format === 'DIGITAL_KEY') ⚡ Entrega Inmediata @else 📦 Envío Físico @endif
            </p>
        </div>
    </div>

    <div class="text-right">
        <span class="block text-xl font-bold text-green-400">{{ $ad->price }} €</span>
        <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-1 rounded mt-1 text-sm">
            Comprar
        </button>
    </div>
</div>