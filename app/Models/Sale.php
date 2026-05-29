<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'payment_method_id',
        'sale_status_id',
        'description',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SaleStatus::class, 'sale_status_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
