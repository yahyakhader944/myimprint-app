<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'job_title',
        'avatar',
        'bio_title',
        'bio',
    ];

     // علاقة مع User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     // علاقة One-to-Many مع المهارات
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

     // علاقة One-to-Many مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }

     // علاقة One-to-Many مع الأعمال السابقة
    public function portfolioItems()
    {
        return $this->hasMany(PortfolioItem::class);
    }
}
