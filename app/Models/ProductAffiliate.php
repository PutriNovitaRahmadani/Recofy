<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAffiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'shop_id',
        'title',
        'image',
        'images',
        'product_link',
        'seller_name',
        'price_min',
        'price_max',
        'price_min_before_discount',
        'price_max_before_discount',
        'historical_sold',
        'commission_rate',
        'commission_nominal',
        'rating_star',
        'rating_count',
        'liked_count',
        'category_id',
        'ctime',
        'trend_score', // ✅ Tambahkan kolom ini
    ];

    protected $casts = [
        'images' => 'array',
        'ctime' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ✅ (opsional) accessor untuk format angka besar
    public function getFormattedTrendScoreAttribute()
    {
        return number_format($this->trend_score, 0);
    }
}
