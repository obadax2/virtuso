<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubComments extends Model
{   
    public function comment()
{
    return $this->belongsTo(Comments::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
