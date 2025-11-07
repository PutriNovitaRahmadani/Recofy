<?php

namespace App\Http\Controllers;

use App\Services\ShopeeService;
use App\Models\Category;
use App\Models\LiveAccount;

class CategoryController extends Controller
{
    public function index(ShopeeService $shopeeService)
    {
        // 1. Ambil data mentah dari service
        $raw = $shopeeService->getCategories();

        // 2. Normalisasi ke array
        if ($raw instanceof \Illuminate\Support\Collection) {
            $items = $raw->toArray();
        } elseif (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $items = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        } elseif (is_array($raw)) {
            $items = $raw;
        } else {
            $items = [];
        }

        // 3. Loop dan mapping dengan pengecekan kunci yang fleksibel
        foreach ($items as $cat) {
            // pastikan $cat adalah array
            if (is_object($cat)) $cat = (array) $cat;
            if (!is_array($cat)) continue;

            // cari nilai catid dari beberapa kemungkinan key / path
            $catid = $this->findFirst($cat, [
                'catid',
                'category_id',
                'cat_id',
                'id',
                'cid',
                'category.catid',
                'data.catid',
                'category.id'
            ]);

            // kalau tidak ditemukan catid, skip (hindari undefined index)
            if (empty($catid)) {
                continue;
            }

            // nama/tampilan kategori - coba beberapa key umum
            $displayName = $this->findFirst($cat, [
                'display_name',
                'name',
                'title',
                'category_name',
                'label',
                'displayName'
            ]) ?? '';

            // parent id jika ada
            $parentId = $this->findFirst($cat, [
                'parent_catid',
                'parent_cat_id',
                'parent_id',
                'parentId',
                'parent'
            ]);

            // ambil gambar dari beberapa kemungkinan key
$image = $this->findFirst($cat, [
    'image',
    'image_url',
    'icon',
    'img',
    'picture'
]);

// simpan/update ke DB (casting ke int untuk catid/parent)
Category::updateOrCreate(
    ['catid' => (int) $catid],
    [
        'display_name' => $displayName,
        'parent_id'    => $parentId !== null ? (int) $parentId : null,
        'image_url'    => $image, // ✅ tambahkan ini
    ]
);


        // 4. Ambil dari DB dan kirim ke view
        $categories = Category::all();

        return view('kategori', compact('categories'));
    }

    /**
     * Cari nilai pertama yang tidak null/empty dari array berdasarkan daftar key/path.
     * Mendukung dot-notation untuk nested arrays, ex: 'category.catid'
     */
    private function findFirst(array $arr, array $keys)
    {
        foreach ($keys as $key) {
            $val = $this->getNested($arr, $key);
            if ($val !== null && $val !== '') {
                return $val;
            }
        }
        return null;
    }

    /**
     * Ambil nilai dari array dengan mendukung dot notation.
     */
    private function getNested(array $arr, string $path)
    {
        if ($path === '') return null;
        $parts = explode('.', $path);
        $tmp = $arr;
        foreach ($parts as $p) {
            if (is_array($tmp) && array_key_exists($p, $tmp)) {
                $tmp = $tmp[$p];
            } else {
                return null;
            }
        }
        return $tmp;
    }
}
