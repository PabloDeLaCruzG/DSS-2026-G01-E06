@extends('user.layout')

@section('title', 'Mis Pedidos')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mis pedidos</h1>

<div class="space-y-4">
    @forelse($orders as $order)
        <div class="bg-surface border border-border rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold">Pedido #{{ $order->id }}</p>
                <p class="text-sm text-text-muted">{{ $order->status->value ?? $order->status }}</p>
            </div>

            <div class="space-y-2 text-sm">
                @forelse($order->orderItems as $item)
                    <div class="flex justify-between border-b border-border pb-2">
                        <span>{{ $item->gameAd?->game?->title ?? 'Artículo' }}</span>
                        <span>{{ number_format($item->unit_price, 2) }} €</span>
                    </div>
                @empty
                    <p class="text-text-muted">Sin artículos.</p>
                @endforelse
            </div>

            <div class="mt-3 text-right font-semibold">
                Total: {{ number_format($order->total_amount, 2) }} €
            </div>
        </div>
    @empty
        <div class="bg-surface border border-border rounded-xl p-8 text-center text-text-muted">
            Aún no tienes pedidos.
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
