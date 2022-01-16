<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the roadmaps for the user.
     */
    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class);
    }

    /**
     * Get the tasks for the user.
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Get the todos for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //Get the user's liked roadmaps
    public function likedRoadmaps()
    {
        return $this->belongsToMany(Roadmap::class, 'likes', 'user_id', 'roadmap_id');
    }

    //Get the user's followed roadmaps
    public function followedRoadmaps()
    {
        return $this->belongsToMany(Roadmap::class, 'follows', 'user_id', 'roadmap_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
