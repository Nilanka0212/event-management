<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = [
        'expense_no', 'type', 'category', 'description',
        'amount', 'payment_method', 'bank_name', 'cheque_no',
        'expense_date', 'event_id', 'order_id', 'reference', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->expense_no)) {
                $model->expense_no = 'EXP-' . str_pad(
                    (self::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT
                );
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function cheque(): HasOne
    {
        return $this->hasOne(Cheque::class);
    }
}
