<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id',
        'doctors_id',
        'price',
        'code'
    ];

    protected $table = 'appointment';

    public function doctors()
    {
        return $this->hasOne(Doctor::class, 'doctors_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'users_id', 'id');
    }
}
