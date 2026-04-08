<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'         => Prospect::count(),
            'new'           => Prospect::where('status', 'new')->count(),
            'in_progress'   => Prospect::where('status', 'in_progress')->count(),
            'qualified'     => Prospect::where('status', 'qualified')->count(),
            'disqualified'  => Prospect::where('status', 'disqualified')->count(),
            'converted'     => Prospect::where('status', 'converted')->count(),
        ];

        $recentProspects = Prospect::with('advisor')
            ->orderByDesc('last_message_at')
            ->limit(5)
            ->get();

        $todayMessages = Message::whereDate('created_at', today())->count();
        $advisors      = User::where('role', 'advisor')->count();

        // Datos para gráfico de los últimos 7 días
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyData[] = [
                'date'  => $date->format('d M'),
                'count' => Prospect::whereDate('created_at', $date)->count(),
            ];
        }

        return view('dashboard', compact('stats', 'recentProspects', 'todayMessages', 'advisors', 'weeklyData'));
    }
}
