<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show conversation
     */
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        // تحديث قراءة الرسائل
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['messages.sender', 'worker', 'investor']);

        return view('conversations.show', compact('conversation'));
    }

    /**
     * List conversations
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $conversations = Conversation::where('worker_id', $user->id)
            ->orWhere('investor_id', $user->id)
            ->with(['worker', 'investor', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Start conversation
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'investor_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::firstOrCreate([
            'worker_id' => $data['worker_id'],
            'investor_id' => $data['investor_id'],
        ]);

        return redirect()->route('conversations.show', $conversation);
    }
}
