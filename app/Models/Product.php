<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const CATEGORIES = ['Eyeglass Frame' => 'FRM', 'Sunglasses' => 'SUN', 'Contact Lenses & Solution' => 'CTL', 'Reading Glasses' => 'RDG', 'Lens' => 'LNS', 'Accessory' => 'ACC'];

    protected $guarded = ['id'];

    protected $casts = ['active' => 'boolean'];

    public function stock()
    {
        return $this->hasOne(StockManagement::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getAvailableAttribute(): int
    {
        return max(0, ($this->stock?->on_hand ?? 0) - ($this->stock?->reserved ?? 0));
    }

    // Codes are system generated per category (FRM-0001, SUN-0001 ...) and never entered by hand.
    public static function generateCode(string $category): string
    {
        $prefix = static::CATEGORIES[$category] ?? 'OPT';
        $last = static::where('code', 'like', $prefix.'-%')->lockForUpdate()->orderByRaw('CAST(SUBSTRING(code, '.(strlen($prefix) + 2).') AS UNSIGNED) DESC')->value('code');
        $next = $last ? ((int) substr($last, strlen($prefix) + 1)) + 1 : 1;

        return sprintf('%s-%04d', $prefix, $next);
    }
}
