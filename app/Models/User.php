<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guarded = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // User posts one-to-many relationship
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    // User creating groups #author groups
    public function authorGroups()
    {
        return $this->hasMany(Group::class, 'user_id', 'id');
    }

    // User stories one-to-many relationship
    public function stories()
    {
        return $this->hasMany(Story::class, 'user_id', 'id');
    }

    // in User changed is_online to boolean  (user is online === true, user is offline === false)
    public function markOnline()
    {
        $this->last_seen_at = Carbon::now();
        $this->is_online = true;
        $this->save();
    }
    // in User changed is_online to boolean  (user is online === false, user is offline === true)
    public function markOffline()
    {
        $this->last_seen_at = Carbon::now();
        $this->is_online = false;
        $this->save();
    }

    public function isOnline()
    {
        return $this->is_online;
    }

    // User in groups many-to-many relationship
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    // User friends many-to-many relationship (friendships)
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id');
    }
}
