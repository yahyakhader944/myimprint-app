<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'worker_profile_id',
        'name',
        'description',
    ];

    public function profile()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_profile_id');
    }
}
