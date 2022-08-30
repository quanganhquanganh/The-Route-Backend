<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'image',
        'slug',
        'current'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
    
    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class, 'roadmap_tags');
    // }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
    
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows');
    }
}
