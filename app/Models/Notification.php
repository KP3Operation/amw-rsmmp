<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const FEE = 1;
    const INPATIENT_LIST = 2;
    const APPOINTMENT = 3;

    protected $fillable = [
        'doctor_id',
        'context',
        'is_read',
        'appointment_date',
        'message'
    ];
}
