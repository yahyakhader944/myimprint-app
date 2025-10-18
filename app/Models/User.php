<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_blocked',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function workerProfile()
    {
        return $this->hasOne(WorkerProfile::class, 'user_id');
    }

    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class, 'user_id');
    }

    public function unreadConversationsCount()
    {
        $userId = $this->id;

        return Conversation::where(function ($q) use ($userId) {
            $q->where('worker_id', $userId)->orWhere('investor_id', $userId);
        })
            ->whereHas('messages', function ($q) use ($userId) {
                $q->where('sender_id', '!=', $userId)->whereNull('read_at');
            })
            ->count();
    }

    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
