<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointments_id',
        'doctors_id',
        'appointment_date',
        'status'
    ];

    protected $table = 'appointment_details';

    public function doctors()
    {
        return $this->hasOne(Doctor::class, 'doctors_id', 'id');
    }

    public function appointments()
    {
        return $this->hasOne(Appointment::class, 'appointments_id', 'id');
    }
}
