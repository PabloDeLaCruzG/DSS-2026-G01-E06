{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.layout')

@section('title', 'Resumen')

@section('content')
@php
    // Valores simulados si las variables no vienen desde el controlador.
    $simulatedFlags = [];

    if (!isset($totalUsers)) {
        $totalUsers = 24102;
        $simulatedFlags['totalUsers'] = true;
    }

    if (!isset($totalRevenue)) {
        $totalRevenue = 1200000;
        $simulatedFlags['totalRevenue'] = true;
    }

    if (!isset($activeListings)) {
        $activeListings = 1500;
        $simulatedFlags['activeListings'] = true;
    }

    if (!isset($openReports)) {
        $openReports = 12;
        $simulatedFlags['openReports'] = true;
    }

    // Chart: etiquetas y datos (ejemplo). Si no vienen, se usan valores de ejemplo.
    if (!isset($chartLabels) || !isset($chartData)) {
        $chartLabels = ['01 May','08 May','15 May','22 May','29 May'];
        $chartData   = [120, 180, 150, 220, 190];
        $simulatedFlags['chart'] = true;
    }
@endphp

<div class="space-y-6">

  {{-- Aviso si hay datos simulados --}}
  @if(!empty($simulatedFlags))
    <div class="p-3 rounded-md bg-yellow-900/10 border border-yellow-800 text-yellow-200 text-sm">
      Nota: algunos valores se muestran como <span class="font-semibold">Simulado</span> porque no se recibieron datos reales desde el controlador.
    </div>
  @endif

  {{-- Tarjetas principales --}}
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-surface border border-border rounded-xl p-5">
      <div class="text-sm text-text-muted">USUARIOS TOTALES</div>
      <div class="mt-2 text-2xl font-semibold">
        {{ number_format($totalUsers) }}
        @if(!empty($simulatedFlags['totalUsers']))
          <span class="ml-2 inline-block text-xs bg-yellow-600 text-black px-2 py-0.5 rounded">Simulado</span>
        @endif
      </div>
      <div class="text-xs text-green-400 mt-1">+12%</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-5">
      <div class="text-sm text-text-muted">INGRESOS TOTALES</div>
      <div class="mt-2 text-2xl font-semibold">
        {{ number_format($totalRevenue, 2, ',', '.') }}€
        @if(!empty($simulatedFlags['totalRevenue']))
          <span class="ml-2 inline-block text-xs bg-yellow-600 text-black px-2 py-0.5 rounded">Simulado</span>
        @endif
      </div>
      <div class="text-xs text-green-400 mt-1">+8.4%</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-5">
      <div class="text-sm text-text-muted">ANUNCIOS ACTIVOS</div>
      <div class="mt-2 text-2xl font-semibold">
        {{ number_format($activeListings) }}
        @if(!empty($simulatedFlags['activeListings']))
          <span class="ml-2 inline-block text-xs bg-yellow-600 text-black px-2 py-0.5 rounded">Simulado</span>
        @endif
      </div>
      <div class="text-xs text-text-muted mt-1">-0%</div>
    </div>

    <div class="bg-surface border border-border rounded-xl p-5">
      <div class="text-sm text-text-muted">DENUNCIAS ABIERTAS</div>
      <div class="mt-2 text-2xl font-semibold">
        {{ number_format($openReports) }}
        @if(!empty($simulatedFlags['openReports']))
          <span class="ml-2 inline-block text-xs bg-yellow-600 text-black px-2 py-0.5 rounded">Simulado</span>
        @endif
      </div>
      <div class="text-xs text-red-500 mt-1">Crítico</div>
    </div>
  </div>

  {{-- Área principal: gráfico + contenido reportado --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-surface border border-border rounded-xl p-6">
      <h3 class="text-lg font-semibold text-text-main mb-4">Crecimiento de ingresos</h3>
      <p class="text-sm text-text-muted mb-4">Rendimiento de ingresos en los últimos 30 días</p>
      <canvas id="salesChart" class="w-full h-64"></canvas>
    </div>

    <aside class="bg-surface border border-border rounded-xl p-4">
      <h4 class="text-sm font-semibold text-text-main mb-3">Contenido reportado</h4>

      <div class="space-y-3">
        @forelse($flagged as $f)
            <div class="flex items-start gap-3">
              <div class="w-12 h-12 bg-background rounded-md flex items-center justify-center text-text-muted overflow-hidden">
                @if($f->gameAd && is_array($f->gameAd->images ?? null) && count($f->gameAd->images))
                  <img src="{{ \Illuminate\Support\Facades\Storage::url($f->gameAd->images[0]) }}" class="w-full h-full object-cover rounded-md" alt="">
                @else
                  <div class="text-xs">Sin imagen</div>
                @endif
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <div class="text-sm font-medium text-text-main">
                    {{ $f->gameAd?->game?->title ?? ('Reporte #' . $f->id) }}
                  </div>
                  <span class="text-xs text-red-500 bg-red-900/20 px-2 py-0.5 rounded-md">Revisión</span>
                </div>
                <div class="text-xs text-text-muted">{{ $f->reason ?? 'Reportado' }}</div>
              </div>
            </div>
        @empty
            <div class="text-sm text-text-muted">No hay contenido reportado</div>
        @endforelse

        <a href="{{ route('admin.reports.index') }}" class="block text-sm text-primary mt-3">VER TODAS LAS DENUNCIAS</a>
      </div>
    </aside>
  </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Si el controlador no ha pasado datos serán los arrays simulados definidos arriba en Blade.
  const labels = {!! json_encode($chartLabels) !!};
  const data = {!! json_encode($chartData) !!};

  const ctx = document.getElementById('salesChart')?.getContext('2d');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Ingresos',
          data,
          fill: true,
          backgroundColor: 'rgba(3, 201, 169, 0.08)',
          borderColor: 'rgba(3, 201, 169, 0.9)',
          tension: 0.4,
          pointRadius: 3
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#9ba4b0' } },
          y: { grid: { color: '#1f2a36' }, ticks: { color: '#9ba4b0' } }
        }
      }
    });
  }
</script>
@endpush
@endsection