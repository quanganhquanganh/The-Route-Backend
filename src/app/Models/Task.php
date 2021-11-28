<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'roadmap_id',
        'user_id',
    ];

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
