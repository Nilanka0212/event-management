<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = [
        'transaction_no', 'type', 'transaction_method', 'account_type',
        'amount', 'description', 'reference_no', 'cheque_no', 'bank_name',
        'transaction_date', 'running_balance', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->transaction_no)) {
                $model->transaction_no = 'TXN-' . str_pad(
                    (self::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT
                );
            }
        });
    }
}
