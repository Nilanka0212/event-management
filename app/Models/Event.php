<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    protected $fillable = [
        'event_no', 'customer_id', 'event_sub_category_id', 'event_name',
        'event_date', 'today_date', 'venue', 'remarks',
        'package_cost_price', 'package_sell_price',
        'additional_services_total', 'other_charges_total',
        'sub_total', 'discount', 'total_amount', 'advance_payment', 'balance',
        'status', 'notification_7_sent', 'notification_3_sent'
    ];

    protected $casts = [
        'event_date' => 'date',
        'today_date' => 'date',
        'package_cost_price' => 'decimal:2',
        'package_sell_price' => 'decimal:2',
        'additional_services_total' => 'decimal:2',
        'other_charges_total' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'advance_payment' => 'decimal:2',
        'balance' => 'decimal:2',
        'notification_7_sent' => 'boolean',
        'notification_3_sent' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->event_no)) {
                $model->event_no = 'EV-' . str_pad(
                    (self::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT
                );
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(EventSubCategory::class, 'event_sub_category_id');
    }

    public function additionalServices(): HasMany
    {
        return $this->hasMany(EventService::class);
    }

    public function otherCharges(): HasMany
    {
        return $this->hasMany(EventOtherCharge::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function recalculateTotals(): void
    {
        $additionalTotal = $this->additionalServices()->sum('total_price');
        $otherChargesTotal = $this->otherCharges()->sum('amount');
        $subTotal = $this->package_sell_price + $additionalTotal + $otherChargesTotal;
        $totalAmount = $subTotal - $this->discount;
        $balance = $totalAmount - $this->advance_payment;

        $this->update([
            'additional_services_total' => $additionalTotal,
            'other_charges_total' => $otherChargesTotal,
            'sub_total' => $subTotal,
            'total_amount' => $totalAmount,
            'balance' => $balance,
        ]);
    }
}
