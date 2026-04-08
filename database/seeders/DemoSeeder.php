<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $advisors = User::where('role', 'advisor')->get();

        $cases = [
            [
                'name' => 'Carlos Mendoza', 'phone' => '555-1001', 'email' => 'carlos@gmail.com',
                'status' => 'qualified', 'ai_score' => 91, 'case_type' => 'Ajuste de Estatus',
                'ai_summary' => 'Ajuste de Estatus (I-485). Casado con ciudadana. Alta viabilidad.',
            ],
            [
                'name' => 'Laura Rodríguez', 'phone' => '555-1002', 'email' => 'laura@hotmail.com',
                'status' => 'in_progress', 'ai_score' => 74, 'case_type' => 'Residencia Permanente',
                'ai_summary' => 'Green Card por petición familiar. Requiere documentos adicionales.',
            ],
            [
                'name' => 'Miguel Ángel Torres', 'phone' => '555-1003', 'email' => null,
                'status' => 'new', 'ai_score' => null, 'case_type' => null, 'ai_summary' => null,
            ],
            [
                'name' => 'Sandra Flores', 'phone' => '555-1004', 'email' => 'sandra@gmail.com',
                'status' => 'qualified', 'ai_score' => 88, 'case_type' => 'Ciudadanía / Naturalización',
                'ai_summary' => 'Naturalización. 5 años de residencia permanente. Caso sólido.',
            ],
            [
                'name' => 'Roberto Jiménez', 'phone' => '555-1005', 'email' => 'roberto@outlook.com',
                'status' => 'disqualified', 'ai_score' => 12, 'case_type' => null,
                'ai_summary' => 'Trámite fuera de especialidad (consulta laboral).',
            ],
            [
                'name' => 'Ana Patricia Vega', 'phone' => '555-1006', 'email' => 'anavega@gmail.com',
                'status' => 'converted', 'ai_score' => 95, 'case_type' => 'Visa K-1 (Prometido/a)',
                'ai_summary' => 'Visa K-1. Prometida de ciudadano americano. Cliente activa.',
            ],
            [
                'name' => 'José Hernández', 'phone' => '555-1007', 'email' => null,
                'status' => 'in_progress', 'ai_score' => 68, 'case_type' => 'Perdón de Inadmisibilidad',
                'ai_summary' => 'Waiver I-601A. Entrada sin inspección. En evaluación.',
            ],
            [
                'name' => 'María Martínez', 'phone' => '555-1008', 'email' => 'mmartinez@gmail.com',
                'status' => 'new', 'ai_score' => null, 'case_type' => null, 'ai_summary' => null,
            ],
        ];

        foreach ($cases as $i => $case) {
            $prospect = Prospect::create(array_merge($case, [
                'bot_active'      => in_array($case['status'], ['new', 'in_progress']),
                'widget_token'    => Str::uuid(),
                'assigned_to'     => $case['status'] !== 'new' ? $advisors->random()->id : null,
                'last_message_at' => now()->subMinutes(rand(1, 1440)),
            ]));

            $convos = [
                // Carlos - Ajuste de Estatus
                0 => [
                    ['client', 'Hola, estoy en EE.UU. sin papeles y me casé con una ciudadana americana hace 6 meses.'],
                    ['bot', '¡Hola Carlos! Soy el asistente de inmigración. Tu situación puede calificar para un Ajuste de Estatus (I-485). ¿Tienes alguna orden de deportación o has salido del país recientemente?'],
                    ['client', 'No tengo orden de deportación, nunca he salido desde que entré. Tenemos el acta de matrimonio y todo.'],
                    ['bot', 'Perfecto. Tu caso de Ajuste de Estatus parece muy viable. Tengo registrada tu información y una especialista te contactará pronto.'],
                    ['advisor', '¡Hola Carlos! Soy Ana García. Revisé tu caso y podemos iniciar el I-485 de inmediato. ¿Tienes disponible para una llamada mañana?'],
                ],
                // Laura - Residencia
                1 => [
                    ['client', 'Necesito ayuda con mi residencia, mi hermano es ciudadano y me peticionó hace años.'],
                    ['bot', '¡Hola Laura! Eso es una petición familiar. ¿Sabes en qué categoría de preferencia está tu caso y si ya tienes fecha de prioridad disponible?'],
                    ['client', 'Creo que es F4, me dijeron que ya casi me toca. Tengo todos los documentos listos.'],
                    ['bot', 'Excelente. Con fecha de prioridad disponible podemos iniciar el proceso consular o ajuste. Una especialista revisará tu expediente.'],
                ],
            ];

            if (isset($convos[$i])) {
                foreach ($convos[$i] as $msg) {
                    Message::create([
                        'prospect_id'    => $prospect->id,
                        'sender_type'    => $msg[0],
                        'sender_user_id' => $msg[0] === 'advisor' ? $advisors->first()->id : null,
                        'content'        => $msg[1],
                        'is_read'        => true,
                        'created_at'     => now()->subMinutes(rand(5, 60)),
                        'updated_at'     => now()->subMinutes(rand(1, 5)),
                    ]);
                }
            } else {
                Message::create([
                    'prospect_id' => $prospect->id,
                    'sender_type' => 'client',
                    'content'     => $case['status'] === 'new'
                        ? 'Hola, necesito información sobre trámites migratorios.'
                        : 'Quisiera saber más sobre mi caso de ' . ($case['case_type'] ?? 'inmigración') . '.',
                    'is_read'     => false,
                    'created_at'  => $prospect->last_message_at,
                ]);
            }
        }
    }
}
