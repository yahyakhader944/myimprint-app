<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'worker_id',
        'investor_id'
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
