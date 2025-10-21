<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'job_title',
        'bio',
        'avatar',
    ];

    /**
     * Each InvestorProfile belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
