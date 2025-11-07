<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'product_models'; // nama tabel
    protected $primaryKey = 'id';        // PK bawaan
    public $incrementing = true;         // karena PK auto increment
    protected $keyType = 'int';          // tipe PK

    protected $fillable = [
        'product_item_id',
        'model_id',
        'name',
        'price',
        'stock',
        'sold',
    ];

    /**
     * Relasi ke produk induk.
     * Satu varian milik satu produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_item_id', 'item_id');
    }
}