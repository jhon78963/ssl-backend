<?php

namespace App\Booking\Models;

use App\Booking\Enums\BookingStatus;
use App\Customer\Models\Customer;
use App\PaymentType\Models\PaymentType;
use App\Product\Models\Product;
use App\Room\Models\Room;
use App\Schedule\Models\Schedule;
use App\Service\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'customer_id',
        'schedule_id',
        'start_date',
        'end_date',
        'total',
        'total_paid',
        'people_extra_import',
        'facilities_import',
        'consumptions_import',
        'notes',
        'status',
        'title',
        'description',
        'location',
        'background_color',
        'border_color',
        'text_color',
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
            'status' => BookingStatus::class,
            'total' => 'float',
            'total_paid' => 'float',
            'price' => 'float',
            'facilities_import' => 'float',
        ];
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'booking_room',
        )->withPivot(['price', 'quantity', 'additional_people']);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'booking_product',
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
            'booking_payment_type',
        )->withPivot(['payment', 'cash_payment', 'card_payment']);
    }
}
