<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['catid', 'display_name', 'parent_catid', 'image_url'];

    public function liveAccounts()
    {
        return $this->belongsToMany(LiveAccount::class, 'account_category');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_categories',
            'category_catid',    // FK di pivot
            'product_item_id',   // FK di pivot
            'catid',             // PK/unique di categories
            'item_id'            // PK di products
        );
    }
}