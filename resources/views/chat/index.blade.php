@extends('layouts.master')
@section('title') Chat en Vivo @endsection
@section('page-title') Chat en Vivo @endsection
@section('css')
<style>
.chat-wrapper { display: flex; height: calc(100vh - 200px); min-height: 500px; overflow: hidden; }
.chat-sidebar { width: 300px; flex-shrink: 0; border-right: 1px solid #e9ecef; overflow-y: auto; }
.chat-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.chat-messages { flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa; }
.chat-input-area { border-top: 1px solid #e9ecef; padding: 16px; background: #fff; }
.prospect-item { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background .15s; }
.prospect-item:hover, .prospect-item.active { background: #e8f0fe; }
.prospect-item .name { font-weight: 600; font-size: 14px; }
.prospect-item .preview { font-size: 12px; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.message-bubble { max-width: 70%; margin-bottom: 16px; }
.message-bubble.client { align-self: flex-start; }
.message-bubble.advisor, .message-bubble.bot { align-self: flex-end; }
.bubble-content { padding: 10px 14px; border-radius: 12px; font-size: 14px; line-height: 1.5; }
.client .bubble-content { background: #fff; border: 1px solid #e9ecef; border-radius: 12px 12px 12px 2px; }
.advisor .bubble-content { background: #1f58c7; color: #fff; border-radius: 12px 12px 2px 12px; }
.bot .bubble-content { background: #34c38f; color: #fff; border-radius: 12px 12px 2px 12px; }
.bubble-time { font-size: 11px; color: #adb5bd; margin-top: 4px; }
.unread-badge { min-width: 18px; height: 18px; }
.chat-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #adb5bd; }
</style>
@endsection
@section('body') <body> @endsection

@section('content')
<div class="card p-0" style="overflow:hidden">
    <div class="chat-wrapper">
        {{-- Sidebar: lista de prospectos --}}
        <div class="chat-sidebar">
            <div class="p-3 border-bottom bg-white">
                <h6 class="mb-2 fw-bold">Conversaciones</h6>
                <input type="text" id="search-prospects" class="form-control form-control-sm" placeholder="Buscar prospecto...">
            </div>
            <div id="prospect-list">
                @forelse($prospects as $p)
                @php $lastMsg = $p->messages->first(); $unread = $p->unreadMessages()->count(); @endphp
                <div class="prospect-item {{ $activeProspect?->id == $p->id ? 'active' : '' }}"
                     onclick="window.location='{{ route('chat.index', ['prospect' => $p->id]) }}'">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-xs flex-shrink-0">
                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold" style="font-size:12px">
                                {{ strtoupper(substr($p->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="name">{{ $p->name }}</span>
                                <span class="badge bg-{{ $p->statusColor() }}" style="font-size:9px">{{ $p->statusLabel() }}</span>
                            </div>
                            <div class="preview">{{ $lastMsg?->content ?? 'Sin mensajes' }}</div>
                        </div>
                        @if($unread > 0)
                        <span class="badge rounded-pill bg-danger unread-badge">{{ $unread }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted p-4">
                    <i class="bx bx-inbox font-size-30 d-block mb-2"></i>
                    No hay conversaciones
                </div>
                @endforelse
            </div>
        </div>

        {{-- Panel principal de chat --}}
        <div class="chat-main">
            @if($activeProspect)
            {{-- Header del chat --}}
            <div class="p-3 border-bottom bg-white d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold font-size-16">
                        {{ strtoupper(substr($activeProspect->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold">{{ $activeProspect->name }}</h6>
                    <small class="text-muted">
                        {{ $activeProspect->phone }}
                        @if($activeProspect->bot_active)
                            &nbsp;·&nbsp;<span class="text-success"><i class="bx bx-bot"></i> Bot activo</span>
                        @else
                            &nbsp;·&nbsp;<span class="text-primary"><i class="bx bx-user"></i> Asesora</span>
                        @endif
                    </small>
                </div>
                <div class="d-flex gap-2">
                    {{-- Cambiar estado --}}
                    <div class="dropdown">
                        <button class="btn btn-sm bg-{{ $activeProspect->statusColor() }}-subtle text-{{ $activeProspect->statusColor() }} border dropdown-toggle"
                                data-bs-toggle="dropdown">
                            {{ $activeProspect->statusLabel() }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach(['new'=>'Nuevo','in_progress'=>'En Proceso','qualified'=>'Calificado','disqualified'=>'Descartado'] as $val => $label)
                            <li>
                                <a class="dropdown-item status-change" href="#"
                                   data-prospect="{{ $activeProspect->id }}" data-status="{{ $val }}">
                                    {{ $label }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($activeProspect->status === 'qualified')
                    <button class="btn btn-sm btn-success convert-btn"
                            data-prospect="{{ $activeProspect->id }}"
                            data-name="{{ $activeProspect->name }}">
                        <i class="bx bx-transfer me-1"></i> Convertir a Cliente
                    </button>
                    @endif
                    <a href="{{ route('prospects.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-list-ul"></i>
                    </a>
                </div>
            </div>

            @if($activeProspect->ai_summary)
            <div class="alert alert-info mb-0 rounded-0 py-2 px-3 d-flex align-items-center gap-2" style="font-size:13px">
                <i class="bx bx-brain"></i>
                <strong>IA:</strong> {{ $activeProspect->ai_summary }}
                @if($activeProspect->ai_score)
                    &nbsp;· Score: <strong>{{ $activeProspect->ai_score }}/100</strong>
                @endif
            </div>
            @endif

            {{-- Mensajes --}}
            <div class="chat-messages d-flex flex-column" id="chat-messages">
                @forelse($messages as $msg)
                <div class="message-bubble {{ $msg->sender_type }}">
                    @if($msg->sender_type !== 'advisor')
                    <div class="bubble-label text-muted mb-1" style="font-size:11px">
                        @if($msg->sender_type === 'bot') <i class="bx bx-bot"></i> Bot
                        @else <i class="bx bx-user"></i> {{ $activeProspect->name }}
                        @endif
                    </div>
                    @endif
                    <div class="bubble-content">{{ $msg->content }}</div>
                    <div class="bubble-time {{ $msg->sender_type === 'advisor' ? 'text-end' : '' }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
                @empty
                <div class="chat-empty">
                    <i class="bx bx-message-dots font-size-48 mb-3"></i>
                    <p>Sin mensajes aún</p>
                </div>
                @endforelse
            </div>

            {{-- Input --}}
            <div class="chat-input-area">
                <div class="d-flex gap-2">
                    <input type="text" id="message-input" class="form-control"
                           placeholder="Escribe un mensaje..." autocomplete="off">
                    <button id="send-btn" class="btn btn-primary px-4">
                        <i class="bx bx-send"></i>
                    </button>
                </div>
                <div class="mt-2 d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-secondary quick-reply" data-msg="Hola, soy tu asesora asignada. Con mucho gusto te ayudaré con tu trámite migratorio.">Saludo</button>
                    <button class="btn btn-sm btn-outline-secondary quick-reply" data-msg="¿Podrías compartirme más detalles sobre tu situación migratoria actual?">Pedir detalles</button>
                    <button class="btn btn-sm btn-outline-secondary quick-reply" data-msg="Voy a revisar tu caso con nuestro equipo especializado. Te contactaremos muy pronto.">Cierre</button>
                </div>
            </div>

            @else
            {{-- Estado vacío --}}
            <div class="chat-empty flex-grow-1">
                <i class="bx bx-message font-size-48 mb-3"></i>
                <h5>Selecciona una conversación</h5>
                <p class="text-muted">Elige un prospecto de la lista para ver el chat</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal de conversión --}}
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center py-4">
            <div class="modal-body">
                <i class="bx bx-check-circle text-success" style="font-size:60px"></i>
                <h4 id="convertModalMsg" class="mt-3 mb-2">¡Convertido!</h4>
                <p class="text-muted">El prospecto ha sido registrado como cliente.</p>
                <button class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if($activeProspect)
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
const prospectId = {{ $activeProspect->id }};
const csrfToken  = '{{ csrf_token() }}';
const BASE_URL   = '{{ url("") }}';
const messagesEl = document.getElementById('chat-messages');
const inputEl    = document.getElementById('message-input');

// Scroll to bottom
function scrollBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
}
scrollBottom();

// Append message to chat
function appendMessage(senderType, content, time) {
    const wrapper = document.createElement('div');
    wrapper.className = `message-bubble ${senderType}`;

    const label = senderType !== 'advisor'
        ? `<div class="bubble-label text-muted mb-1" style="font-size:11px">
            ${senderType === 'bot' ? '<i class="bx bx-bot"></i> Bot' : '<i class="bx bx-user"></i> Cliente'}
           </div>`
        : '';

    wrapper.innerHTML = `
        ${label}
        <div class="bubble-content">${content}</div>
        <div class="bubble-time ${senderType === 'advisor' ? 'text-end' : ''}">${time}</div>
    `;

    // Remove empty state if exists
    const empty = messagesEl.querySelector('.chat-empty');
    if (empty) empty.remove();

    messagesEl.appendChild(wrapper);
    scrollBottom();
}

// Connect to Reverb WebSocket
const pusher = new Pusher('{{ config("reverb.apps.apps.0.key", env("REVERB_APP_KEY")) }}', {
    wsHost:   '{{ env("REVERB_HOST", "127.0.0.1") }}',
    wsPort:   {{ env("REVERB_PORT", 8080) }},
    forceTLS: false,
    enabledTransports: ['ws'],
    cluster: 'mt1',
});

const channel = pusher.subscribe(`prospect.${prospectId}`);
channel.bind('new-message', function(data) {
    if (data.sender_type !== 'advisor') {
        const time = new Date(data.created_at).toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
        appendMessage(data.sender_type, data.content, time);
    }
});

// Send message
function sendMessage() {
    const content = inputEl.value.trim();
    if (!content) return;
    inputEl.value = '';

    const now = new Date().toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
    appendMessage('advisor', content, now);

    fetch(`${BASE_URL}/chat/${prospectId}/send`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ content }),
    }).catch(err => console.error('Error al enviar:', err));
}

document.getElementById('send-btn').addEventListener('click', sendMessage);
inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

// Quick replies
document.querySelectorAll('.quick-reply').forEach(btn => {
    btn.addEventListener('click', () => { inputEl.value = btn.dataset.msg; inputEl.focus(); });
});

// Cambiar estado
document.querySelectorAll('.status-change').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        fetch(`${BASE_URL}/prospects/${this.dataset.prospect}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ status: this.dataset.status }),
        }).then(() => location.reload());
    });
});

// Convertir a cliente
document.querySelector('.convert-btn')?.addEventListener('click', function() {
    fetch(`${BASE_URL}/prospects/${this.dataset.prospect}/convert`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('convertModalMsg').textContent = data.message;
        new bootstrap.Modal(document.getElementById('convertModal')).show();
    });
});

// Filter prospects in sidebar
document.getElementById('search-prospects').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.prospect-item').forEach(item => {
        item.style.display = item.querySelector('.name').textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endif
@endsection
