<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ssn',
        'name',
        'phone_number',
        'gender',
        'birth_date',
        'email',
        'patient_id',
        'medical_no',
        'guarantor_id',
        'guarantor_name',
    ];

    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => config('app.calling_code').$value,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
