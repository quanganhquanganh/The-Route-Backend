<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roadmap_id'
    ];

    protected $table = 'likes';
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roadmaps()
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }
}
