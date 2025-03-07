<?php

namespace App\Service\Models;

use App\Booking\Models\Booking;
use App\Reservation\Models\Reservation;
use App\Unit\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'price',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class
        )->withPivot(['price', 'quantity', 'is_paid']);
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(
            Booking::class
        )->withPivot(['price', 'quantity', 'is_paid']);
    }

    public function units(): BelongsToMany {
        return $this->belongsToMany(
            Unit::class,
            'unit_service'
        );
    }
}
