<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventSubCategory extends Model
{
    protected $fillable = [
        'event_category_id', 'name', 'description', 
        'cost_price', 'sell_price', 'is_active'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'event_sub_category_services')
            ->withPivot(['cost_price', 'qty'])
            ->withTimestamps();
    }

    public function subCategoryServices(): HasMany
    {
        return $this->hasMany(EventSubCategoryService::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function recalculateCostPrice(): void
    {
        $total = $this->subCategoryServices()->sum(\DB::raw('cost_price * qty'));
        $this->update(['cost_price' => $total]);
    }
}
