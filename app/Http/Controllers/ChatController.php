<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Events\ProspectStatusChanged;
use App\Models\Message;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // Panel de chat para asesoras
    public function index(Request $request)
    {
        $prospects = Prospect::with(['messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->get();

        $activeProspect = null;
        $messages = collect();

        if ($request->filled('prospect')) {
            $activeProspect = Prospect::findOrFail($request->prospect);
            $messages = Message::where('prospect_id', $activeProspect->id)
                ->orderBy('created_at')
                ->get();

            // Marcar como leídos
            Message::where('prospect_id', $activeProspect->id)
                ->where('sender_type', 'client')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.index', compact('prospects', 'activeProspect', 'messages'));
    }

    // Enviar mensaje como asesora
    public function sendAdvisorMessage(Request $request, Prospect $prospect)
    {
        $request->validate(['content' => 'required|string|max:2000']);

        $message = Message::create([
            'prospect_id' => $prospect->id,
            'sender_type' => 'advisor',
            'sender_user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $prospect->update(['last_message_at' => now(), 'bot_active' => false]);

        broadcast(new NewMessage($message));

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => 'advisor',
                'content' => $message->content,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    // ---- WIDGET API (sin autenticación) ----

    // Iniciar sesión de chat desde el widget
    public function widgetStart(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);

        $token = Str::uuid();

        $prospect = Prospect::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'new',
            'bot_active' => true,
            'widget_token' => $token,
            'last_message_at' => now(),
        ]);

        // Mensaje de bienvenida del bot
        $welcome = Message::create([
            'prospect_id' => $prospect->id,
            'sender_type' => 'bot',
            'content' => "¡Hola {$prospect->name}! Soy el asistente virtual de inmigración. Nos especializamos en: USCIS, CRBA, Residencias, Perdones, Ciudadanías, Visa de Prometido/a (K-1), Visa por Viudez y Ajustes de Estatus.\n\n¿En cuál de estos trámites puedo ayudarte hoy?",
        ]);

        broadcast(new NewMessage($welcome));

        return response()->json([
            'token' => $token,
            'prospect_id' => $prospect->id,
            'welcome' => $welcome->content,
        ]);
    }

    // Recibir mensaje del cliente en el widget
    public function widgetMessage(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'content' => 'required|string|max:2000',
        ]);

        $prospect = Prospect::where('widget_token', $request->token)->firstOrFail();

        // Guardar mensaje del cliente
        $message = Message::create([
            'prospect_id' => $prospect->id,
            'sender_type' => 'client',
            'content' => $request->content,
        ]);

        $prospect->update(['last_message_at' => now(), 'status' => 'in_progress']);

        broadcast(new NewMessage($message));

        // Si el bot está activo, generar respuesta automática
        $botResponse = null;
        if ($prospect->bot_active) {
            $botResponse = $this->generateBotResponse($prospect, $request->content);
        }

        return response()->json([
            'success' => true,
            'bot_response' => $botResponse,
        ]);
    }

    // Obtener historial de mensajes del widget
    public function widgetMessages(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $prospect = Prospect::where('widget_token', $request->token)->firstOrFail();
        $messages = Message::where('prospect_id', $prospect->id)->orderBy('created_at')->get();

        return response()->json([
            'messages' => $messages->map(fn($m) => [
        'sender_type' => $m->sender_type,
        'content' => $m->content,
        'created_at' => $m->created_at->format('H:i'),
        ]),
        ]);
    }

    // Trámites de inmigración que hace nelly
    private const VIABLE_CASES = [
        'uscis' => 'Trámite USCIS',
        'crba' => 'CRBA (Ciudadanía por Nacimiento en el Extranjero)',
        'residencia' => 'Residencia Permanente',
        'green card' => 'Residencia Permanente',
        'tarjeta verde' => 'Residencia Permanente',
        'perdón' => 'Perdón de Inadmisibilidad',
        'perdon' => 'Perdón de Inadmisibilidad',
        'waiver' => 'Perdón de Inadmisibilidad',
        'ciudadanía' => 'Ciudadanía / Naturalización',
        'ciudadania' => 'Ciudadanía / Naturalización',
        'naturalización' => 'Ciudadanía / Naturalización',
        'naturalizacion' => 'Ciudadanía / Naturalización',
        'prometido' => 'Visa K-1 (Prometido/a)',
        'prometida' => 'Visa K-1 (Prometido/a)',
        'k-1' => 'Visa K-1 (Prometido/a)',
        'viudez' => 'Visa por Viudez',
        'viudo' => 'Visa por Viudez',
        'viuda' => 'Visa por Viudez',
        'ajuste' => 'Ajuste de Estatus',
        'i-485' => 'Ajuste de Estatus',
        'estatus' => 'Ajuste de Estatus',
    ];

    private function detectCaseType(string $text): ?string
    {
        $lower = mb_strtolower($text);
        foreach (self::VIABLE_CASES as $keyword => $label) {
            if (str_contains($lower, $keyword)) {
                return $label;
            }
        }
        return null;
    }

    private function generateBotResponse(Prospect $prospect, string $userMessage): array
    {
        $messageCount = $prospect->messages()->where('sender_type', 'client')->count();
        $content = '';
        $qualified = false;
        $caseType = $this->detectCaseType($userMessage);

        if ($messageCount === 1) {
            // Primer mensaje — identificar el trámite
            if ($caseType) {
                $content = "Gracias por la información. Entiendo que necesitas ayuda con: **{$caseType}**. ¿Cuál es tu situación migratoria actual? (por ejemplo: estás en EE.UU., fuera del país, tienes alguna orden de deportación, etc.)";
                $prospect->update(['case_type' => $caseType]);
            }
            else {
                $content = "Gracias por contactarnos. Nos especializamos en los siguientes trámites migratorios:\n\n• USCIS · CRBA · Residencia Permanente\n• Perdones de Inadmisibilidad · Ciudadanía\n• Visa de Prometido/a (K-1) · Visa por Viudez · Ajuste de Estatus\n\n¿Cuál de estos trámites necesitas o en cuál de estas situaciones te encuentras?";
            }

        }
        elseif ($messageCount === 2) {
            // Segundo mensaje — revisar si el caso aplica con más contexto
            $caseType = $caseType ?? $this->detectCaseType($prospect->case_description ?? '') ?? $prospect->case_type;

            if ($caseType || strlen($userMessage) > 25) {
                $label = $caseType ?? 'Trámite Migratorio';
                $content = "Perfecto, he registrado tu caso de **{$label}**. Basándonos en lo que describes, tu situación parece viable para iniciar el proceso. ¿Tienes documentos como pasaporte, acta de nacimiento, o cualquier notificación previa de USCIS?";
                $qualified = true;

                $prospect->update([
                    'status' => 'qualified',
                    'case_type' => $label,
                    'ai_score' => rand(72, 96),
                    'ai_summary' => "{$label}: " . substr($userMessage, 0, 120),
                    'bot_active' => false,
                ]);

                broadcast(new ProspectStatusChanged($prospect));
            }
            else {
                // No identificamos un trámite que manejemos
                $content = "Entiendo tu situación. Para poder orientarte mejor, ¿podrías indicarme cuál de nuestros trámites se relaciona con tu caso? (Residencia, Ciudadanía, Ajuste de Estatus, Perdón, CRBA, Visa K-1 o Viudez)";
            }

        }
        elseif ($messageCount >= 3) {
            // Tercer mensaje — transferir si aún no calificó, o despedir si ya no aplica
            $caseType = $prospect->case_type;
            if ($caseType) {
                $content = "Excelente. He registrado toda la información de tu caso de {$caseType}. Una de nuestras especialistas en inmigración te contactará muy pronto para darte orientación personalizada. ¡Gracias por confiar en nosotros!";
                $prospect->update(['bot_active' => false]);
            }
            else {
                $content = "Lamentablemente, el caso que describes no corresponde a los trámites que manejamos actualmente (USCIS, CRBA, Residencias, Perdones, Ciudadanías, Visa K-1, Visa por Viudez y Ajustes). Te recomendamos consultar con un abogado especializado en el área que necesitas. ¡Mucho éxito!";
                $prospect->update([
                    'bot_active' => false,
                    'status' => 'disqualified',
                    'ai_score' => rand(5, 25),
                    'ai_summary' => 'Trámite fuera de especialidad.',
                ]);
                broadcast(new ProspectStatusChanged($prospect));
            }
        }

        $botMessage = Message::create([
            'prospect_id' => $prospect->id,
            'sender_type' => 'bot',
            'content' => $content,
        ]);

        broadcast(new NewMessage($botMessage));

        return [
            'sender_type' => 'bot',
            'content' => $content,
            'qualified' => $qualified,
            'created_at' => $botMessage->created_at->format('H:i'),
        ];
    }
}
