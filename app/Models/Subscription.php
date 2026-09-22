<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lesson_id',
        'status',
        'date_suscription'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
