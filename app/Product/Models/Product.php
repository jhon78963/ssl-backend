<?php

namespace App\Product\Models;

use App\Book\Models\Book;
use App\Reservation\Models\Reservation;
use App\Unit\Models\Unit;
use App\ProductType\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
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
        'product_type_id',
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

    public function productType(): BelongsTo {
        return $this->belongsTo(ProductType::class);
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class,
            'reservation_product',
        )->withPivot(['price', 'quantity', 'is_paid']);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(
            Book::class,
            'book_product',
        )->withPivot(['price', 'quantity', 'is_paid']);
    }

    public function units(): BelongsToMany {
        return $this->belongsToMany(
            Unit::class,
            'unit_product'
        );
    }
}
