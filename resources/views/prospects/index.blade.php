@extends('layouts.master')
@section('title') Prospectos @endsection
@section('page-title') Prospectos @endsection
@section('body') <body> @endsection

@section('content')

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('prospects.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" placeholder="Nombre, teléfono o email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="new" @selected(request('status')=='new')>Nuevo</option>
                            <option value="in_progress" @selected(request('status')=='in_progress')>En Proceso</option>
                            <option value="qualified" @selected(request('status')=='qualified')>Calificado</option>
                            <option value="disqualified" @selected(request('status')=='disqualified')>Descartado</option>
                            <option value="converted" @selected(request('status')=='converted')>Cliente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search me-1"></i> Filtrar
                        </button>
                    </div>
                    @if(request()->hasAny(['search','status']))
                    <div class="col-md-2">
                        <a href="{{ route('prospects.index') }}" class="btn btn-outline-secondary w-100">Limpiar</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="card-title mb-0 flex-grow-1">
                        Prospectos
                        <span class="badge bg-primary ms-2">{{ $prospects->total() }}</span>
                    </h4>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Prospecto</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Score IA</th>
                                <th>Asesora</th>
                                <th>Último mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prospects as $prospect)
                            <tr id="row-{{ $prospect->id }}">
                                <td class="text-muted">#{{ $prospect->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                                {{ strtoupper(substr($prospect->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $prospect->name }}</h6>
                                            @if($prospect->ai_summary)
                                                <small class="text-muted" title="{{ $prospect->ai_summary }}">
                                                    {{ Str::limit($prospect->ai_summary, 40) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><i class="bx bx-phone text-muted me-1"></i>{{ $prospect->phone ?? '—' }}</div>
                                    <small class="text-muted"><i class="bx bx-envelope me-1"></i>{{ $prospect->email ?? '—' }}</small>
                                </td>
                                <td>
                                    <div class="dropdown status-dropdown" id="status-{{ $prospect->id }}">
                                        <button class="btn btn-sm badge bg-{{ $prospect->statusColor() }}-subtle text-{{ $prospect->statusColor() }} border border-{{ $prospect->statusColor() }}-subtle dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown">
                                            {{ $prospect->statusLabel() }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach(['new'=>'Nuevo','in_progress'=>'En Proceso','qualified'=>'Calificado','disqualified'=>'Descartado','converted'=>'Cliente'] as $val => $label)
                                            <li>
                                                <a class="dropdown-item status-change"
                                                   href="#"
                                                   data-prospect="{{ $prospect->id }}"
                                                   data-status="{{ $val }}">
                                                    {{ $label }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @if($prospect->ai_score)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px; min-width:60px">
                                                <div class="progress-bar bg-{{ $prospect->ai_score >= 70 ? 'success' : ($prospect->ai_score >= 40 ? 'warning' : 'danger') }}"
                                                     style="width:{{ $prospect->ai_score }}%"></div>
                                            </div>
                                            <span class="fw-medium">{{ $prospect->ai_score }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <select class="form-select form-select-sm assign-select" style="min-width:120px"
                                            data-prospect="{{ $prospect->id }}">
                                        <option value="">Sin asignar</option>
                                        @foreach($advisors as $advisor)
                                            <option value="{{ $advisor->id }}" @selected($prospect->assigned_to == $advisor->id)>
                                                {{ $advisor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-muted">
                                    {{ $prospect->last_message_at?->diffForHumans() ?? '—' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('chat.index', ['prospect' => $prospect->id]) }}"
                                           class="btn btn-sm btn-outline-primary" title="Abrir chat">
                                            <i class="bx bx-chat"></i>
                                        </a>
                                        @if($prospect->status === 'qualified')
                                        <button class="btn btn-sm btn-success convert-btn"
                                                data-prospect="{{ $prospect->id }}"
                                                data-name="{{ $prospect->name }}"
                                                title="Convertir a cliente">
                                            <i class="bx bx-transfer"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bx bx-user-x font-size-40 d-block mb-2"></i>
                                    No hay prospectos con estos filtros.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $prospects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de conversión exitosa --}}
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center py-4">
            <div class="modal-body">
                <div class="mb-3">
                    <i class="bx bx-check-circle text-success" style="font-size:60px"></i>
                </div>
                <h4 id="convertModalMsg" class="mb-2">¡Convertido exitosamente!</h4>
                <p class="text-muted">El prospecto ha sido registrado como cliente en el CRM.</p>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const BASE_URL  = '{{ url("") }}';

// Cambiar estado vía AJAX
document.querySelectorAll('.status-change').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const prospectId = this.dataset.prospect;
        const newStatus  = this.dataset.status;

        fetch(`${BASE_URL}/prospects/${prospectId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ status: newStatus })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Actualizar badge
                const btn = document.querySelector(`#status-${prospectId} .btn`);
                btn.className = `btn btn-sm badge bg-${data.color}-subtle text-${data.color} border border-${data.color}-subtle dropdown-toggle`;
                btn.textContent = data.label + ' ';
            }
        });
    });
});

// Asignar asesora
document.querySelectorAll('.assign-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const prospectId = this.dataset.prospect;
        fetch(`${BASE_URL}/prospects/${prospectId}/assign`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ advisor_id: this.value })
        });
    });
});

// Convertir a cliente
document.querySelectorAll('.convert-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const prospectId = this.dataset.prospect;
        const name       = this.dataset.name;
        fetch(`${BASE_URL}/prospects/${prospectId}/convert`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('convertModalMsg').textContent = data.message;
                new bootstrap.Modal(document.getElementById('convertModal')).show();
                // Actualizar badge en la fila
                const statusBtn = document.querySelector(`#status-${prospectId} .btn`);
                if (statusBtn) {
                    statusBtn.className = 'btn btn-sm badge bg-info-subtle text-info border border-info-subtle dropdown-toggle';
                    statusBtn.textContent = 'Cliente ';
                }
            }
        });
    });
});
</script>
@endsection
