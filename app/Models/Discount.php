<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'category_id',
        'min_amount',
        'min_quantity',
        'discount_rate',
        'free_items',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'min_quantity' => 'integer',
        'discount_rate' => 'decimal:2',
        'free_items' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
} 