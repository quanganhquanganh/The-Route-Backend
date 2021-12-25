<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'start_date',
        'end_date',
        'completed',
        'milestone_id',
        'roadmap_id',
        'user_id',
    ];

    public function milestone()
    {
        return $this->belongsTo(Task::class, 'milestone_id');
    }

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
