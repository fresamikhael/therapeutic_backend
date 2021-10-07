<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'experience',
        'lisence_number',
        'language',
        'hospitals_id',
        'price',
        'categories_id',
        'photo',
        'slug'
    ];

    public function category()
    {
        return $this->belongsTo(DoctorCategory::class, 'categories_id', 'id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospitals_id', 'id');
    }
}
