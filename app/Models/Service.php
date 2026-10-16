<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'cost_price', 'is_active'];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(EventSubCategory::class, 'event_sub_category_services')
            ->withPivot(['cost_price', 'qty'])
            ->withTimestamps();
    }

    public function eventServices(): HasMany
    {
        return $this->hasMany(EventService::class);
    }
}
