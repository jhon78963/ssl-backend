<?php

namespace App\Book\Models;

use App\Book\Enums\BookStatus;
use App\Customer\Models\Customer;
use App\PaymentType\Models\PaymentType;
use App\Room\Models\Room;
use App\Schedule\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
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
            'status' => BookStatus::class,
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
            'book_room',
        )->withPivot(['price', 'quantity', 'additional_people']);
    }

    public function paymentTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentType::class,
            'book_payment_type',
        )->withPivot(['payment', 'cash_payment', 'card_payment']);
    }
}
