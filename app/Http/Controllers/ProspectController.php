<?php

namespace App\Http\Controllers;

use App\Events\ProspectStatusChanged;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with('advisor')->orderByDesc('last_message_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $prospects = $query->paginate(15)->withQueryString();
        $advisors  = User::where('role', 'advisor')->get();

        return view('prospects.index', compact('prospects', 'advisors'));
    }

    public function updateStatus(Request $request, Prospect $prospect)
    {
        $request->validate(['status' => 'required|in:new,in_progress,qualified,disqualified,converted']);

        $prospect->update(['status' => $request->status]);

        broadcast(new ProspectStatusChanged($prospect));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status'  => $prospect->status,
                'label'   => $prospect->statusLabel(),
                'color'   => $prospect->statusColor(),
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function assign(Request $request, Prospect $prospect)
    {
        $request->validate(['advisor_id' => 'required|exists:users,id']);
        $prospect->update(['assigned_to' => $request->advisor_id]);

        return response()->json(['success' => true]);
    }

    public function convert(Prospect $prospect)
    {
        $prospect->update(['status' => 'converted']);
        broadcast(new ProspectStatusChanged($prospect));

        // Aquí iría la llamada al CRM externo / n8n webhook
        // Http::post(config('services.crm_webhook'), [...])

        return response()->json([
            'success' => true,
            'message' => "¡{$prospect->name} convertido a cliente exitosamente!",
        ]);
    }
}
