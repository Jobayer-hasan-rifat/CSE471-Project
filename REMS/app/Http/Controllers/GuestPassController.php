<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestPass;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class GuestPassController extends Controller
{
    public function index()
    {
        $guestPasses = GuestPass::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guest-passes.index', compact('guestPasses'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        
        for ($i = 0; $i < $validated['quantity']; $i++) {
            GuestPass::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'status' => 'active',
            ]);
        }

        return redirect()->route('guest-passes.index')
            ->with('success', 'Guest passes generated successfully.');
    }

    public function statistics()
    {
        $totalPasses = GuestPass::count();
        $activePasses = GuestPass::where('status', 'active')->count();
        $usedPasses = GuestPass::where('status', 'used')->count();
        $expiredPasses = GuestPass::where('status', 'expired')->count();

        $passesByEvent = GuestPass::selectRaw('event_id, COUNT(*) as total')
            ->groupBy('event_id')
            ->with('event')
            ->get();

        return view('guest-passes.statistics', compact(
            'totalPasses',
            'activePasses',
            'usedPasses',
            'expiredPasses',
            'passesByEvent'
        ));
    }

    public function revoke(GuestPass $guestPass)
    {
        $guestPass->update(['status' => 'expired']);

        return redirect()->route('guest-passes.index')
            ->with('success', 'Guest pass revoked successfully.');
    }
}
