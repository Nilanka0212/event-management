<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventSubCategoryService extends Model
{
    protected $fillable = ['event_sub_category_id', 'service_id', 'cost_price', 'qty'];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(EventSubCategory::class, 'event_sub_category_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
