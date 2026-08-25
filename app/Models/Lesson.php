<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    
    public function User(){
        $this->belongsToMany(User::class,'user_id','lesson_id');
    }
}
