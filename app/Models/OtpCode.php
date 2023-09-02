<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OtpCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereUserId($value)
 * @property int $code
 * @method static \Illuminate\Database\Eloquent\Builder|OtpCode whereCode($value)
 * @mixin \Eloquent
 */
class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
