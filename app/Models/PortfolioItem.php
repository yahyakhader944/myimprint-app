<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $fillable = [
        'worker_profile_id',
        'title',
        'subtitle',
        'description',
        'image',
    ];

    public function profile()
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_profile_id');
    }
}
