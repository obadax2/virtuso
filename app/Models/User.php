<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
        ->select('users.id');
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}
public function friendRequests()
{
    return $this->hasMany(Friends::class);
}


public function friendRequestsWithUser()
{
    return $this->hasMany(Friends::class)->with('user');
}
public function sentRequests(): HasMany
{
    return $this->hasMany(Friends::class, 'sender_id');
}

}
