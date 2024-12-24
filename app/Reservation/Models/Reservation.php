<?php

namespace App\Reservation\Models;

use App\Customer\Models\Customer;
use App\Locker\Models\Locker;
use App\PaymentType\Models\PaymentType;
use App\Product\Models\Product;
use App\Reservation\Enums\ReservationStatus;
use App\ReservationType\Models\ReservationType;
use App\Room\Models\Room;
use App\Service\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'reservation_type_id',
        'customer_id',
        'initial_reservation_date',
        'final_reservation_date',
        'total',
        'total_paid',
        'extra_import',
        'facilities_import',
        'consumptions_import',
        'status',
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
            'status' => ReservationStatus::class,
            'total' => 'float',
            'total_paid' => 'float',
            'price' => 'float',
            'extra_import' => 'float',
            'consumptions_import' => 'float',
            'facilities_import' => 'float',
        ];
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function reservationType(): BelongsTo
    {
        return $this->belongsTo(ReservationType::class);
    }

    public function lockers(): BelongsToMany
    {
        return $this->belongsToMany(
            Locker::class,
            'reservation_locker',
        )->withPivot(['price', 'quantity', 'is_paid']);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'reservation_room',
        )->withPivot(['price', 'quantity', 'is_paid', 'additional_people', 'extra_hours']);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'reservation_product',
        )->withPivot(['price', 'quantity', 'is_paid', 'is_free']);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
        )->withPivot(['price', 'quantity', 'is_paid', 'is_free']);
    }

    public function paymentTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentType::class,
            'reservation_payment_type',
        )->withPivot(['payment', 'cash_payment', 'card_payment']);
    }
}
