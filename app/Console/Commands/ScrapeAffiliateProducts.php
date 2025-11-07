<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShopeeAffiliateScraperService;

class ScrapeAffiliateProducts extends Command
{
    protected $signature = 'shopee:scrape-affiliate';
    protected $description = 'Scrape product data from Shopee Affiliate web dashboard';

    public function handle(ShopeeAffiliateScraperService $scraper)
    {
        $count = $scraper->scrapeProducts();
        $this->info("✅ Berhasil menyimpan {$count} produk dari halaman Affiliate Shopee!");
    }
}
