<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ShopeeAffiliateScraperService;
use App\Models\ProductAffiliate;
use Illuminate\Support\Facades\Auth;

class ProductAffiliateController extends Controller
{
    protected $shopee;

    public function __construct(ShopeeAffiliateScraperService $shopee)
    {
        $this->shopee = $shopee;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $sort = $request->get('sort', null);
        $search = $request->get('search', null);

        $query = ProductAffiliate::query();

        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        switch ($sort) {
            case 'komisi_tertinggi':
                $query->orderByDesc('commission_nominal');
                break;
            case 'rating_tertinggi':
                $query->orderByDesc('rating_star');
                break;
            case 'terlaris':
                $query->orderByDesc('historical_sold');
                break;
            case 'trending': // ✅ tambahan baru
                $query->orderByDesc('trend_score');
                break;
            case 'terbaru':
                $query->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }
        

        $products = $query->paginate(20)->withQueryString();
  
        return view('affiliate.products', compact('user', 'products', 'sort'));
    }
}
