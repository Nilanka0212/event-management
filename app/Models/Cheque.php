<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    protected $fillable = [
        'type',
        'cheque_number',
        'cheque_date',
        'received_or_issued_date',
        'amount',
        'payer_bank',
        'payer_branch',
        'payee_name',
        'customer_id',
        'order_id',
        'payment_id',
        'expense_id',
        'bank_id',
        'deposit_date',
        'clearance_date',
        'return_date',
        'return_reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cheque_date' => 'date',
        'received_or_issued_date' => 'date',
        'deposit_date' => 'date',
        'clearance_date' => 'date',
        'return_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get UI badge class for status
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'In Hand' => 'badge bg-warning text-dark',
            'Deposited' => 'badge bg-primary text-white',
            'Realized' => 'badge bg-success text-white',
            'Returned' => 'badge bg-danger text-white',
            'Cancelled' => 'badge bg-secondary text-white',
            'Issued' => 'badge bg-info text-dark',
            default => 'badge bg-secondary text-white',
        };
    }
}
