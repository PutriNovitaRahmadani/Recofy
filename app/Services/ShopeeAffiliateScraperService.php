<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ProductAffiliate;
use Carbon\Carbon;

class ShopeeAffiliateScraperService
{
    public function scrapeProducts($keyword = null, $pageNumber = 0, $pageLimit = 20)
    {
        $pageOffset = $pageNumber * $pageLimit;

        $params = [
            'list_type' => 0,
            'sort_type' => 1,
            'page_offset' => $pageOffset,
            'page_limit' => $pageLimit,
            'client_type' => 1,
        ];

        if ($keyword) {
            $params['keyword'] = $keyword;
        }

        $response = Http::withHeaders([
            'accept' => 'application/json, text/plain, */*',
            'accept-language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            'af-ac-enc-dat' => env('SHOPEE_AF_ENC_DAT'),
            'af-ac-enc-sz-token' => env('SHOPEE_AF_ENC_SZ_TOKEN'),
            'affiliate-program-type' => '1',
            'referer' => 'https://affiliate.shopee.co.id/offer/product_offer',
            'origin' => 'https://affiliate.shopee.co.id',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'x-sap-ri' => env('SHOPEE_X_SAP_RI'),
            'x-sap-sec' => env('SHOPEE_X_SAP_SEC'),
            'cookie' => env('SHOPEE_AFFILIATE_COOKIE'),
        ])->get('https://affiliate.shopee.co.id/api/v3/offer/product/list', $params);

        if ($response->failed()) {
            throw new \Exception("Gagal mengambil data Shopee Affiliate: " . $response->status());
        }

        $data = $response->json();

        if (!isset($data['data']['list']) || empty($data['data']['list'])) {
            throw new \Exception("Data produk kosong atau struktur JSON tidak sesuai");
        }

        foreach ($data['data']['list'] as $item) {
            $basic = $item['batch_item_for_item_card_full'] ?? [];

            $priceMin = isset($basic['price_min']) ? $basic['price_min'] / 100 : 0;
            $priceMax = isset($basic['price_max']) ? $basic['price_max'] / 100 : 0;
            $priceMinBefore = isset($basic['price_min_before_discount']) ? $basic['price_min_before_discount'] / 100 : 0;
            $priceMaxBefore = isset($basic['price_max_before_discount']) ? $basic['price_max_before_discount'] / 100 : 0;

            $commissionRate = isset($item['seller_commission_rate'])
                ? (float) str_replace('%', '', $item['seller_commission_rate'])
                : 0;

            $commissionNominal = round(($commissionRate / 100) * $priceMin, 0);

            $historicalSold = $basic['historical_sold'] ?? 0;
            $ratingStar = $basic['item_rating']['rating_star'] ?? 0;
            $likedCount = $basic['liked_count'] ?? 0;

            // Hitung trend score
            $trendScore = ($historicalSold * 0.6) + ($ratingStar * 100 * 0.3) + ($likedCount * 0.1);

            ProductAffiliate::updateOrCreate(
                ['item_id' => $item['item_id']],
                [
                    'shop_id' => $basic['shopid'] ?? null,
                    'title' => $basic['name'] ?? null,
                    'image' => isset($basic['images'][0])
            ? "https://down-id.img.susercontent.com/file/{$basic['images'][0]}"
            : null,
        'images' => isset($basic['images']) ? json_encode($basic['images']) : null,
                    'product_link' => $item['product_link'] ?? null,
                    'seller_name' => $basic['shop_name'] ?? null,
                    'price_min' => $priceMin,
                    'price_max' => $priceMax,
                    'price_min_before_discount' => $priceMinBefore,
                    'price_max_before_discount' => $priceMaxBefore,
                    'historical_sold' => $historicalSold,
                    'commission_rate' => $commissionRate,
                    'commission_nominal' => $commissionNominal,
                    'rating_star' => $ratingStar,
                    'rating_count' => $basic['item_rating']['rating_count'][0] ?? 0,
                    'liked_count' => $likedCount,
                    'category_id' => $basic['catid'] ?? null,
                    'ctime' => $this->parseCtime($basic['ctime'] ?? null),
                    'trend_score' => $trendScore,
                ]
            );
        }

        return count($data['data']['list']);
    }

    private function parseCtime($timestamp)
    {
        return $timestamp ? Carbon::createFromTimestamp($timestamp) : null;
    }

    public function getTrendingProducts($limit = 10)
    {
        return ProductAffiliate::orderByDesc('trend_score')->take($limit)->get();
    }
}
