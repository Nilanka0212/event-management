<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name', 'company_name', 'email', 'phone', 'phone2', 
        'address', 'city', 'postal_code', 'nic', 'dob',
        'notes', 'lead_source', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dob' => 'date',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getTotalDueAttribute(): float
    {
        return $this->orders()->sum('balance');
    }
}
