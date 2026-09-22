<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions')
            ->withPivot('status', 'date_suscription')
            ->withTimestamps();
    }
}
