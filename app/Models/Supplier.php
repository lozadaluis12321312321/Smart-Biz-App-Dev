<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Suppliers with no purchase or profile activity for this many days become Inactive automatically.
    public const INACTIVITY_DAYS = 20;

    protected $guarded = ['id'];

    protected $casts = ['active' => 'boolean', 'last_activity_at' => 'datetime'];

    public function purchases()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function touchActivity(): void
    {
        static::whereKey($this->id)->update(['last_activity_at' => now(), 'active' => true]);
        $this->forceFill(['last_activity_at' => now(), 'active' => true])->syncOriginal();
    }

    public static function syncActivity(): int
    {
        return static::where('active', true)->where('last_activity_at', '<', now()->subDays(static::INACTIVITY_DAYS))->update(['active' => false]);
    }

    public function getDaysIdleAttribute(): ?int
    {
        return $this->last_activity_at ? (int) $this->last_activity_at->diffInDays(now()) : null;
    }
}
