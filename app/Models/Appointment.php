<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'related_user_id',
        'appointment_date',
        'service_unit_id',
        'appointment_no',
        'is_family_member',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
