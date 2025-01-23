<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
     public function user()
    {
        return $this->belongsTo(User::class);
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
  
}
