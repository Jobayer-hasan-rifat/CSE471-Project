<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Club not found');
        }

        $transactions = Transaction::where('club_id', $club->id)
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        return view('club.transactions.index', compact('club', 'transactions'));
    }

    public function store(Request $request)
    {
        $club = Club::where('user_id', Auth::id())->first();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Club not found');
        }

        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        $transaction = new Transaction([
            'club_id' => $club->id,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'status' => 'pending',
        ]);

        $transaction->save();

        return redirect()->route('club.transactions.index')
            ->with('success', 'Transaction request submitted successfully');
    }

    /**
     * Display a listing of transactions for OCA.
     */
    public function ocaIndex()
    {
        $transactions = Transaction::with(['club'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('oca.transactions.index', compact('transactions'));
    }

    /**
     * Approve a transaction request.
     */
    public function approve(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        return redirect()->route('oca.transactions.index')
            ->with('success', 'Transaction approved successfully.');
    }

    /**
     * Reject a transaction request.
     */
    public function reject(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        return redirect()->route('oca.transactions.index')
            ->with('success', 'Transaction rejected successfully.');
    }
}
