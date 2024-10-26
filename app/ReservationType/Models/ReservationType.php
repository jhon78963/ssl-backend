<?php

namespace App\ReservationType\Models;

use App\Reservation\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'creator_user_id',
        'last_modification_time',
        'last_modifier_user_id',
        'is_deleted',
        'deleter_user_id',
        'deletion_time',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
