<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public function post()
{
    return $this->belongsTo(Post::class);
}

public function subComments()
{
    return $this->hasMany(SubComments::class, 'comment_id');
}
}
