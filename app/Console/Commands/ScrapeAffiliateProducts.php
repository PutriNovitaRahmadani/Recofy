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
    $total = 0;
$limit = 20;
$maxPages = 25; // jumlah halaman di affiliate

for ($page = 0; $page < $maxPages; $page++) {
    try {
        $count = $scraper->scrapeProducts(null, $page, $limit);

        if ($count === 0) {
            $this->info("✅ Halaman {$page} kosong.");
            continue; // lanjut halaman berikutnya
        }

        $total += $count;
        $this->info("Halaman {$page}: {$count} produk tersimpan.");
        sleep(1); // delay supaya aman
    } catch (\Exception $e) {
        $this->error("Halaman {$page} gagal: " . $e->getMessage());
        continue;
    }
}

$this->info("🎉 Total produk tersimpan: {$total}");
}
}
