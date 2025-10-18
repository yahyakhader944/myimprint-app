<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine if the user can view the conversation.
     * Only participants (worker or investor) can view.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->worker_id
            || $user->id === $conversation->investor_id;
    }

    /**
     * Determine if the user can send a message in the conversation.
     */
    public function message(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    /**
     * Optional: restrict delete to admins only.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->hasRole('admin');
    }
}
