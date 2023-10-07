<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $connection = 'objects';

    public function reservations() {
        return $this->hasMany(Reservation::class, 'event_id');
    }

    public function students() {
        return $this->belongsToMany(
            User::class,
            "reservations",
            'event_id',
            'student_id'
        );
    }
}
