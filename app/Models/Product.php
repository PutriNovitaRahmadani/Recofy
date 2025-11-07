<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'title',
        'image',
        'ctime',
        'price_min_before_discount',
        'price_max_before_discount',
        'price_min',
        'price_max',
        'historical_sold',
        'product_link',
        'commission',
        'seller_commission',
        'shopee_commission',
        'rating_star',
        'rating_count',
        'liked_count',
        'category_id',
    ];

    protected $casts = [
        'ctime' => 'datetime',
    ];

    /**
     * Relasi 1 produk -> 1 metadata
     */
    public function metadata()
    {
        return $this->hasOne(ProductMetadata::class, 'product_id', 'item_id');
    }

    /**
     * Relasi 1 produk -> 1 kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageFullUrlAttribute()
    {
        if ($this->image) {
            return "https://down-id.img.susercontent.com/file/" . $this->image;
        }
        return $this->image_url ?? asset('assets/img/no-image.png');
    }

    public function getPriceFormattedAttribute()
    {
        // Pastikan harga min dan max ada
        if (!empty($this->price_min) && !empty($this->price_max)) {
            if ($this->price_min != $this->price_max) {
                // Jika berbeda → tampilkan rentang harga
                return 'Rp ' . number_format($this->price_min / 100000, 0, ',', '.') .
                    ' - Rp ' . number_format($this->price_max / 100000, 0, ',', '.');
            } else {
                // Jika sama → tampilkan satu harga
                return 'Rp ' . number_format($this->price_min / 100000, 0, ',', '.');
            }
        }

        // Jika hanya ada satu harga (price_min atau price)
        $price = $this->price_min ?? $this->price ?? 0;
        return 'Rp ' . number_format($price / 100000, 0, ',', '.');
    }

    public function getSoldFormattedAttribute()
    {
        $sold = $this->historical_sold ?? $this->total_sales ?? 0;

        if ($sold >= 1000000) {
            // Jika jutaan → tampil "x,x jt"
            return number_format($sold / 1000000, 1, ',', '.') . ' jt terjual';
        } elseif ($sold >= 1000) {
            // Jika ribuan → tampil "xxxk"
            return number_format($sold / 1000, 0, ',', '.') . 'k terjual';
        }

        return $sold . ' terjual';
    }

    public function categories()
        {
            return $this->belongsToMany(
                Category::class,
                'product_categories',
                'product_item_id',   // FK di pivot
                'category_catid',    // FK di pivot
                'item_id',           // PK di products
                'catid'              // PK/unique di categories
            );
        }

    public function models()
    {
        return $this->hasMany(ProductModel::class, 'product_item_id', 'item_id');
    }

    public function getTotalSoldAttribute()
    {
        return $this->models()->sum('sold');
    }

    public function getFormattedTotalSoldAttribute()
    {
        return number_format($this->models->sum('sold'), 0, ',', '.');
    }

}