<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'description',
        'price',
        'quantity',
        'reorder_level',
        'supplier',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'reorder_level' => 'integer',
        ];
    }

    // All inventory transactions for this product
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Most recent inventory transaction
    public function latestTransaction(): HasOne
    {
        return $this->hasOne(InventoryTransaction::class)->latestOfMany();
    }

    // Check if product stock is low
    public function isLowStock(): bool
    {
        return $this->quantity > 0
            && $this->quantity <= $this->reorder_level;
    }

    // Check if product is out of stock
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    // Get product stock status
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    // Get Bootstrap color for stock status
    public function getStockStatusColorAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'danger';
        }

        if ($this->isLowStock()) {
            return 'warning';
        }

        return 'success';
    }

    // Scope: products with low stock
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level');
    }

    // Scope: products with no stock
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    // Scope: products in a specific category
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
