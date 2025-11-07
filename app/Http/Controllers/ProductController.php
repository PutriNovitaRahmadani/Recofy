<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ShopeeService; // ⬅️ Tambahan
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $shopee;

    public function __construct(ShopeeService $shopee)
    {
        $this->shopee = $shopee;
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort');
        $search = $request->get('search');

        $query = Product::with(['category', 'models']);

        if (!empty($search)) {
            $query->whereRaw('LOWER(title) LIKE LOWER(?)', ['%' . trim($search) . '%']);
        }

        switch ($sort) {
            case 'komisi_tertinggi':
                $query->orderBy('commission', 'desc');
                break;
            case 'rating_tertinggi':
                $query->orderBy('rating_star', 'desc');
                break;
            case 'terlaris':
                $query->orderBy('historical_sold', 'desc');
                break;
            case 'terbaru':
                $query->orderBy('ctime', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(18);
        $categories = Category::orderBy('display_name')->get();
        $user = Auth::user();

        return view('produk', compact('products', 'user', 'categories', 'sort'));
    }

    public function show($id)
    {
        $product = Product::withSum('models', 'sold')
            ->with(['categories', 'models'])
            ->where('item_id', $id)
            ->firstOrFail();

        $user = Auth::user();
        $modelsSold = (int) ($product->models_sum_sold ?? 0);
        $ctime = $product->ctime;

        if (is_numeric($ctime)) {
            $ctimeInt = (int) $ctime;
            $createdAt = strlen((string) $ctimeInt) > 10
                ? Carbon::createFromTimestampMs($ctimeInt)
                : Carbon::createFromTimestamp($ctimeInt);
        } else {
            $createdAt = Carbon::parse($ctime);
        }

        $diffDays = $createdAt->diffInDays(now());
        $umurProduk = max(1, round($diffDays / 30, 1));
        $umurProdukBulat = max(1, floor($diffDays / 30));
        $rataPerBulan = $umurProdukBulat > 0 ? ceil($modelsSold / $umurProdukBulat) : $modelsSold;

        $totalPendapatan = ($product->price_min / 100000) * $product->historical_sold;

        $omsetVarian = $product->models->sum(function ($model) {
            return ($model->price / 100000) * $model->sold;
        });

        $rataPendapatanPerBulan = $umurProdukBulat > 0
            ? ceil($totalPendapatan / $umurProdukBulat)
            : $totalPendapatan;

        return view('detail', compact(
            'product',
            'user',
            'umurProduk',
            'umurProdukBulat',
            'rataPerBulan',
            'totalPendapatan',
            'omsetVarian',
            'rataPendapatanPerBulan',
            'createdAt'
        ));
    }

    /**
     * 🛍️ Ambil produk dari Shopee berdasarkan kategori dan simpan ke DB
     */
    public function fetchFromShopee($catid)
    {
        // ambil produk dari Shopee API
        $fetchedProducts = $this->shopee->getProducts($catid, 0, 50);

        foreach ($fetchedProducts as $item) {
            Product::updateOrCreate(
                ['item_id' => $item['itemid']],
                [
                    'title' => $item['name'],
                    'image' => $item['image'],
                    'price_min' => $item['price'] / 100000, // sesuaikan jika harga sen
                    'price_max' => $item['price_max'] / 100000 ?? $item['price'] / 100000,
                    'category_id' => $catid,
                    'historical_sold' => $item['historical_sold'] ?? 0,
                    'rating_star' => $item['item_rating']['rating_star'] ?? 0,
                    'commission' => $item['commission'] ?? 0,
                    'ctime' => $item['ctime'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Produk berhasil diambil dari Shopee.');
    }
}
