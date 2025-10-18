<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a new message via AJAX
     */
    public function store(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $data = $request->validate([
            'body' => 'nullable|string|max:200',
            'attachment' => 'nullable|file|max:10240', // 10240 = 10 MB
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('messages', 'public');
        }

        $data['conversation_id'] = $conversation->id;
        $data['sender_id'] = Auth::id();

        $message = Message::create($data);
        $message->load('sender');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('conversations.show', $conversation)
                         ->with('status', 'Message sent successfully.');
    }

    /**
     * Get messages for AJAX
     */
    public function index(Conversation $conversation, Request $request)
    {
        $this->authorize('view', $conversation);

        $query = $conversation->messages()->with('sender');

        if ($request->has('after_id')) {
            $query->where('id', '>', $request->after_id);
        }

        $messages = $query->orderBy('id', 'asc')->get();

        return response()->json($messages);
    }
}
