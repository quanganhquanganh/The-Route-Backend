<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'roadmap_id',
    ];

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }
}
