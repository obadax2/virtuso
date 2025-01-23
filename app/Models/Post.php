<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['post_desc', 'photo', 'music', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
{
    return $this->hasMany(Like::class);
}

public function comments()
{
    return $this->hasMany(Comments::class);
}

public function saves()
{
    return $this->hasMany(Save::class);
}
public function favourites()
{
    return $this->hasMany(Favourit::class);
}
}
