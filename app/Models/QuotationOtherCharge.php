<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationOtherCharge extends Model
{
    protected $fillable = ['quotation_id', 'description', 'amount'];
    protected $casts = ['amount' => 'decimal:2'];
    public function quotation(): BelongsTo { return $this->belongsTo(Quotation::class); }
}
