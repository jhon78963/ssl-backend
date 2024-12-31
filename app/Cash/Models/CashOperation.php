<?php

namespace App\Cash\Models;

use App\CashType\Models\CashType;
use App\Reservation\Models\Reservation;
use App\Schedule\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashOperation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'cash_id',
        'cash_type_id',
        'schedule_id',
        'reservation_id',
        'date',
        'amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'creation_time',
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
            'petty_cash_amount' => 'float',
            'initial_amount' => 'float',
        ];
    }

    public function cash(): BelongsTo {
        return $this->belongsTo(Cash::class);
    }

    public function cashType(): BelongsTo {
        return $this->belongsTo(CashType::class);
    }

    public function schedule(): BelongsTo {
        return $this->belongsTo(Schedule::class);
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }
}
