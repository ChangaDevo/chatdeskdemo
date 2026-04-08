<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Widget - ChatDESK</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }

        /* Fake Law Firm Website */
        .site-header {
            background: #69209d;
            color: white;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .site-logo { font-size: 22px; font-weight: 700; }
        .site-logo span { color: #c9a84c; }
        .site-nav a { color: rgba(255,255,255,.7); text-decoration: none; margin-left: 24px; font-size: 14px; }
        .hero {
            background: linear-gradient(135deg, #69209d 0%, #69209d 100%);
            color: white;
            padding: 80px 40px;
            text-align: center;
        }
        .hero h1 { font-size: 42px; font-weight: 700; margin-bottom: 16px; }
        .hero p { font-size: 18px; opacity: .8; max-width: 600px; margin: 0 auto 32px; }
        .hero-btn {
            background: #c9a84c; color: white;
            padding: 14px 32px; border-radius: 6px;
            text-decoration: none; font-weight: 600; font-size: 16px;
            display: inline-block;
        }
        .services {
            max-width: 1000px; margin: 60px auto; padding: 0 40px;
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
        }
        .service-card {
            background: white; border-radius: 10px; padding: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            text-align: center;
        }
        .service-icon { font-size: 36px; margin-bottom: 12px; }
        .service-card h3 { font-size: 16px; color: #69209d; margin-bottom: 8px; font-weight: 600; }
        .service-card p { font-size: 13px; color: #6c757d; line-height: 1.6; }

        /* ========== CHAT WIDGET ========== */
        #cd-widget-btn {
            position: fixed; bottom: 24px; right: 24px;
            width: 60px; height: 60px;
            background: #69209d;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 20px #69209d;
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            transition: transform .2s;
        }
        #cd-widget-btn:hover { transform: scale(1.1); }
        #cd-widget-btn svg { width: 28px; height: 28px; fill: white; }
        .cd-pulse {
            position: absolute; width: 100%; height: 100%;
            border-radius: 50%; background: #69209d;
            animation: cdpulse 2s infinite;
        }
        @keyframes cdpulse {
            0% { transform: scale(1); opacity: .6; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        #cd-widget-box {
            position: fixed; bottom: 96px; right: 24px;
            width: 360px; max-height: 520px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,.18);
            display: none; flex-direction: column;
            overflow: hidden;
            z-index: 9998;
            animation: slideUp .2s ease;
        }
        @keyframes slideUp { from { opacity:0; transform: translateY(16px); } to { opacity:1; transform: translateY(0); } }
        #cd-widget-box.open { display: flex; }

        .cd-header {
            background: linear-gradient(135deg, #69209d, #69209d);
            color: white;
            padding: 16px 20px;
            display: flex; align-items: center; gap: 12px;
        }
        .cd-avatar {
            width: 42px; height: 42px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .cd-header-info h4 { margin: 0; font-size: 15px; }
        .cd-header-info p { margin: 0; font-size: 12px; opacity: .8; }
        .cd-status-dot {
            width: 8px; height: 8px; background: #4cef8d;
            border-radius: 50%; display: inline-block; margin-right: 4px;
        }
        .cd-close { margin-left: auto; background: none; border: none; color: white; cursor: pointer; font-size: 20px; line-height: 1; }

        #cd-form-screen { padding: 24px; }
        #cd-form-screen h5 { font-size: 15px; margin-bottom: 6px; color: #38095aff; }
        #cd-form-screen p { font-size: 13px; color: #6c757d; margin-bottom: 20px; }
        #cd-form-screen input {
            width: 100%; border: 1px solid #e0e0e0;
            border-radius: 8px; padding: 10px 14px;
            font-size: 14px; margin-bottom: 12px; outline: none;
        }
        #cd-form-screen input:focus { border-color: #1f58c7; }
        #cd-start-btn {
            width: 100%; background: #69209d; color: white;
            border: none; border-radius: 8px;
            padding: 12px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: background .2s;
        }
        #cd-start-btn:hover { background: #69209d; }

        #cd-chat-screen { display: none; flex-direction: column; flex: 1; }
        #cd-messages {
            flex: 1; overflow-y: auto; padding: 16px;
            background: #f8f9fa; min-height: 300px; max-height: 300px;
            display: flex; flex-direction: column; gap: 10px;
        }
        .cd-msg { max-width: 82%; }
        .cd-msg.client { align-self: flex-end; }
        .cd-msg.bot, .cd-msg.advisor { align-self: flex-start; }
        .cd-bubble {
            padding: 10px 14px; border-radius: 12px;
            font-size: 14px; line-height: 1.5;
        }
        .cd-msg.client .cd-bubble { background: #69209d; color: white; border-radius: 12px 12px 2px 12px; }
        .cd-msg.bot .cd-bubble, .cd-msg.advisor .cd-bubble {
            background: white; border: 1px solid #e9ecef;
            border-radius: 12px 12px 12px 2px;
        }
        .cd-time { font-size: 11px; color: #adb5bd; margin-top: 3px; }
        .cd-sender-label { font-size: 11px; color: #6c757d; margin-bottom: 3px; }

        .cd-typing { display: flex; gap: 4px; padding: 8px 12px; background: white;
                     border: 1px solid #e9ecef; border-radius: 12px; width: fit-content; }
        .cd-typing span { width: 7px; height: 7px; background: #adb5bd;
                          border-radius: 50%; animation: typing .8s infinite; }
        .cd-typing span:nth-child(2) { animation-delay: .15s; }
        .cd-typing span:nth-child(3) { animation-delay: .3s; }
        @keyframes typing { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

        #cd-input-row {
            display: flex; padding: 12px;
            border-top: 1px solid #e9ecef; gap: 8px; background: white;
        }
        #cd-input {
            flex: 1; border: 1px solid #e0e0e0;
            border-radius: 20px; padding: 8px 16px;
            font-size: 14px; outline: none;
        }
        #cd-input:focus { border-color: #1f58c7; }
        #cd-send {
            width: 38px; height: 38px;
            background: #1f58c7; color: white;
            border: none; border-radius: 50%;
            cursor: pointer; display: flex;
            align-items: center; justify-content: center;
        }

        .cd-qualified-banner {
            background: #d1fae5; color: #065f46;
            padding: 10px 16px; font-size: 13px;
            display: flex; align-items: center; gap-6px; gap: 6px;
        }
    </style>
</head>
<body>

{{-- Fake Law Firm Website --}}
<header class="site-header">
    <div class="site-logo">NELLY ESPINOZA<span>.</span>DEMO</div>
    <nav class="site-nav">
        <a href="#">Inicio</a>
        <a href="#">Trámites</a>
        <a href="#">Abogados</a>
        <a href="#">Contacto</a>
        <a href="#" onclick="resetChat()" style="background:rgba(255,255,255,.15); padding:4px 12px; border-radius:20px; font-size:12px;">↺ Nueva sesión</a>
    </nav>
</header>

<section class="hero">
    <h1>Tu futuro en EE.UU.<br>comienza aquí</h1>
    <p>Más de 15 años ayudando a familias a regularizar su estatus migratorio. Primera consulta gratuita.</p>
    <a href="#" class="hero-btn">Consulta Gratuita →</a>
</section>

<div class="services">
    <div class="service-card">
        <div class="service-icon">🇺🇸</div>
        <h3>Residencia & Ciudadanía</h3>
        <p>Green Card, naturalización, CRBA y ajustes de estatus para ti y tu familia.</p>
    </div>
    <div class="service-card">
        <div class="service-icon">💍</div>
        <h3>Visa de Prometido/a (K-1)</h3>
        <p>Une a tu pareja contigo en EE.UU. También procesamos visas por viudez.</p>
    </div>
    <div class="service-card">
        <div class="service-icon">🛡️</div>
        <h3>Perdones & USCIS</h3>
        <p>Perdones de inadmisibilidad (waivers) y todo tipo de trámites ante USCIS.</p>
    </div>
</div>

{{-- ========== CHAT WIDGET ========== --}}
<div id="cd-widget-btn" onclick="toggleWidget()">
    <div class="cd-pulse"></div>
    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
</div>

<div id="cd-widget-box">
    <div class="cd-header">
        <div class="cd-avatar">⚖️</div>
        <div class="cd-header-info">
            <h4>Asistente de Inmigración</h4>
            <p><span class="cd-status-dot"></span>En línea · Nelly Espinoza</p>
        </div>
        <button class="cd-close" onclick="toggleWidget()">✕</button>
    </div>

    {{-- Form inicial --}}
    <div id="cd-form-screen">
        <h5>¡Hola! ¿Necesitas ayuda migratoria?</h5>
        <p>Cuéntanos tu caso y una especialista en inmigración te atenderá de inmediato. Primera consulta gratuita.</p>
        <input type="text" id="cd-name" placeholder="Tu nombre completo *" required>
        <input type="tel" id="cd-phone" placeholder="WhatsApp / Teléfono *" required>
        <input type="email" id="cd-email" placeholder="Email (opcional)">
        <button id="cd-start-btn" onclick="startChat()">Iniciar consulta gratuita →</button>
    </div>

    {{-- Chat screen --}}
    <div id="cd-chat-screen">
        <div id="cd-messages"></div>
        <div id="cd-input-row">
            <input type="text" id="cd-input" placeholder="Escribe tu mensaje..." autocomplete="off">
            <button id="cd-send" onclick="sendWidgetMsg()">
                <svg viewBox="0 0 24 24" fill="white" width="18" height="18">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
const BASE_URL = '{{ url("") }}';

function resetChat() {
    localStorage.removeItem('cd_token');
    widgetToken = null;
    // Limpiar mensajes y volver al formulario
    document.getElementById('cd-messages').innerHTML = '';
    document.getElementById('cd-chat-screen').style.display = 'none';
    document.getElementById('cd-form-screen').style.display = '';
    document.getElementById('cd-name').value = '';
    document.getElementById('cd-phone').value = '';
    document.getElementById('cd-email').value = '';
    document.getElementById('cd-start-btn').textContent = 'Iniciar consulta gratuita →';
    document.getElementById('cd-start-btn').disabled = false;
    // Remover banner de calificación si existe
    document.querySelector('.cd-qualified-banner')?.remove();
    // Abrir el widget si está cerrado
    if (!isOpen) toggleWidget();
}
let widgetToken = localStorage.getItem('cd_token');
let isOpen = false;

function toggleWidget() {
    isOpen = !isOpen;
    document.getElementById('cd-widget-box').classList.toggle('open', isOpen);
    // Si ya tiene sesión, ir directamente al chat
    if (isOpen && widgetToken) loadExistingChat();
}

function now() {
    return new Date().toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
}

function appendMsg(senderType, content) {
    const box = document.getElementById('cd-messages');
    const msg = document.createElement('div');
    msg.className = `cd-msg ${senderType}`;

    const label = senderType !== 'client'
        ? `<div class="cd-sender-label">${senderType === 'bot' ? '🤖 Asistente' : '👩‍💼 Asesora'}</div>`
        : '';

    msg.innerHTML = `${label}<div class="cd-bubble">${content}</div><div class="cd-time">${now()}</div>`;
    box.appendChild(msg);
    box.scrollTop = box.scrollHeight;
}

function showTyping() {
    const box = document.getElementById('cd-messages');
    const typing = document.createElement('div');
    typing.id = 'cd-typing';
    typing.className = 'cd-msg bot';
    typing.innerHTML = '<div class="cd-typing"><span></span><span></span><span></span></div>';
    box.appendChild(typing);
    box.scrollTop = box.scrollHeight;
}

function hideTyping() {
    document.getElementById('cd-typing')?.remove();
}

function showChatScreen() {
    document.getElementById('cd-form-screen').style.display = 'none';
    document.getElementById('cd-chat-screen').style.display = 'flex';
    document.getElementById('cd-input').focus();
}

async function startChat() {
    const name  = document.getElementById('cd-name').value.trim();
    const phone = document.getElementById('cd-phone').value.trim();
    const email = document.getElementById('cd-email').value.trim();

    if (!name || !phone) { alert('Por favor ingresa tu nombre y teléfono.'); return; }

    document.getElementById('cd-start-btn').textContent = 'Conectando...';
    document.getElementById('cd-start-btn').disabled = true;

    try {
        const res = await fetch(`${BASE_URL}/api/widget/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name, phone, email }),
        });
        const data = await res.json();
        widgetToken = data.token;
        localStorage.setItem('cd_token', widgetToken);

        showChatScreen();
        showTyping();
        setTimeout(() => {
            hideTyping();
            appendMsg('bot', data.welcome);
        }, 1000);
    } catch(e) {
        alert('Error de conexión. Intenta de nuevo.');
        document.getElementById('cd-start-btn').textContent = 'Iniciar consulta gratuita →';
        document.getElementById('cd-start-btn').disabled = false;
    }
}

async function sendWidgetMsg() {
    const input   = document.getElementById('cd-input');
    const content = input.value.trim();
    if (!content || !widgetToken) return;
    input.value = '';

    appendMsg('client', content);
    showTyping();

    try {
        const res = await fetch(`${BASE_URL}/api/widget/message`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ token: widgetToken, content }),
        });
        const data = await res.json();

        setTimeout(() => {
            hideTyping();
            if (data.bot_response) {
                appendMsg(data.bot_response.sender_type, data.bot_response.content);
                if (data.bot_response.qualified) {
                    const banner = document.createElement('div');
                    banner.className = 'cd-qualified-banner';
                    banner.innerHTML = '✅ <strong>¡Tu caso fue calificado!</strong> Una asesora te contactará en breve.';
                    document.getElementById('cd-messages').after(banner);
                }
            }
        }, 1200);
    } catch(e) {
        hideTyping();
    }
}

async function loadExistingChat() {
    try {
        const res = await fetch(`${BASE_URL}/api/widget/messages?token=${widgetToken}`);
        const data = await res.json();
        showChatScreen();
        data.messages.forEach(m => appendMsg(m.sender_type, m.content));
    } catch(e) {
        localStorage.removeItem('cd_token');
        widgetToken = null;
    }
}

// Enter key to send
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('cd-input')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') sendWidgetMsg();
    });

    // Auto-open after 3 seconds for demo
    setTimeout(() => {
        if (!isOpen) toggleWidget();
    }, 3000);
});
</script>
</body>
</html>
