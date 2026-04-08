@extends('layouts.master')
@section('title') Dashboard @endsection
@section('page-title') Dashboard @endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('body') <body> @endsection

@section('content')

{{-- Stats Cards --}}
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Total Prospectos</p>
                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width:56px;height:56px">
                            <i class="bx bx-user font-size-24 text-primary"></i>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <span class="text-success me-1"><i class="bx bx-up-arrow-alt"></i> {{ $stats['new'] }}</span> nuevos hoy
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">En Proceso</p>
                        <h4 class="mb-0">{{ $stats['in_progress'] }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width:56px;height:56px">
                            <i class="bx bx-time font-size-24 text-warning"></i>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <span class="text-warning me-1"><i class="bx bx-chat"></i> {{ $todayMessages }}</span> mensajes hoy
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Calificados</p>
                        <h4 class="mb-0">{{ $stats['qualified'] }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width:56px;height:56px">
                            <i class="bx bx-check-circle font-size-24 text-success"></i>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    @if($stats['total'] > 0)
                        <span class="text-success me-1">{{ round(($stats['qualified'] / $stats['total']) * 100) }}%</span> tasa de calificación
                    @else
                        <span class="text-muted">Sin datos aún</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Convertidos</p>
                        <h4 class="mb-0">{{ $stats['converted'] }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-info-subtle d-flex align-items-center justify-content-center" style="width:56px;height:56px">
                            <i class="bx bx-briefcase font-size-24 text-info"></i>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <span class="text-info me-1"><i class="bx bx-group"></i> {{ $advisors }}</span> asesoras activas
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Gráfico de prospectos por día --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Prospectos - Últimos 7 días</h4>
                <div id="prospectos-chart" class="apex-chart" style="height:280px"></div>
            </div>
        </div>
    </div>

    {{-- Distribución por estado --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Por Estado</h4>
                <div id="status-chart" class="apex-chart" style="height:230px"></div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary">Nuevos</span>
                        <span class="fw-medium">{{ $stats['new'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-warning">En Proceso</span>
                        <span class="fw-medium">{{ $stats['in_progress'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success">Calificados</span>
                        <span class="fw-medium">{{ $stats['qualified'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-danger">Descartados</span>
                        <span class="fw-medium">{{ $stats['disqualified'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-info">Clientes</span>
                        <span class="fw-medium">{{ $stats['converted'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Prospectos recientes --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="card-title mb-0 flex-grow-1">Actividad Reciente</h4>
                    <a href="{{ route('prospects.index') }}" class="btn btn-sm btn-primary">
                        Ver todos <i class="bx bx-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Prospecto</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Asesora</th>
                                <th>Último mensaje</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProspects as $prospect)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                                {{ strtoupper(substr($prospect->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $prospect->name }}</h6>
                                            @if($prospect->case_type)
                                                <small class="text-muted">{{ $prospect->case_type }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $prospect->phone }}</div>
                                    <small class="text-muted">{{ $prospect->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $prospect->statusColor() }}-subtle text-{{ $prospect->statusColor() }} border border-{{ $prospect->statusColor() }}-subtle">
                                        {{ $prospect->statusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    {{ $prospect->advisor?->name ?? '—' }}
                                </td>
                                <td>
                                    {{ $prospect->last_message_at?->diffForHumans() ?? '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('chat.index', ['prospect' => $prospect->id]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-chat"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bx bx-inbox font-size-40 d-block mb-2"></i>
                                    No hay prospectos aún. ¡Comparte el widget para empezar!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
// Gráfico de línea - prospectos por día
var weeklyData = @json($weeklyData);
var options = {
    series: [{
        name: 'Prospectos',
        data: weeklyData.map(d => d.count)
    }],
    chart: { type: 'area', height: 280, toolbar: { show: false } },
    colors: ['#1f58c7'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    xaxis: { categories: weeklyData.map(d => d.date) },
    yaxis: { labels: { formatter: val => Math.round(val) } },
    tooltip: { y: { formatter: val => val + ' prospectos' } },
    grid: { borderColor: '#f0f0f0' }
};
new ApexCharts(document.querySelector("#prospectos-chart"), options).render();

// Gráfico de dona - por estado
var statusOptions = {
    series: [{{ $stats['new'] }}, {{ $stats['in_progress'] }}, {{ $stats['qualified'] }}, {{ $stats['disqualified'] }}, {{ $stats['converted'] }}],
    chart: { type: 'donut', height: 230 },
    labels: ['Nuevos', 'En Proceso', 'Calificados', 'Descartados', 'Clientes'],
    colors: ['#1f58c7', '#f1b44c', '#34c38f', '#f46a6a', '#50a5f1'],
    legend: { show: false },
    dataLabels: { enabled: false },
    plotOptions: { pie: { donut: { size: '65%' } } }
};
new ApexCharts(document.querySelector("#status-chart"), statusOptions).render();
</script>
@endsection
