<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id'); 
    }
}
