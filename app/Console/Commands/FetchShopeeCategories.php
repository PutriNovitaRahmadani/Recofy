<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShopeeService;
use App\Models\Category;

class FetchShopeeCategories extends Command
{
    protected $signature = 'shopee:fetch-categories';
    protected $description = 'Fetch categories from Shopee, save to database recursively';
    protected $shopeeService;

    public function __construct(ShopeeService $shopeeService)
    {
        parent::__construct();
        $this->shopeeService = $shopeeService;
    }

    public function handle()
    {
        $this->info("Fetching categories from Shopee...");

        $categories = $this->shopeeService->getCategories();

        if (empty($categories)) {
            $this->warn("No categories found.");
            return 0;
        }

        $this->printCategories($categories);

        $this->info("Total top-level categories: " . count($categories));
        return 1;
    }

    private function printCategories(array $categories, string $prefix = '', $parentCatId = null)
    {
        foreach ($categories as $cat) {
            $catid = $cat['catid'] ?? null;
            $displayName = $cat['display_name'] ?? null;

            if ($catid) {
                $imageUrl = isset($cat['image']) ? "https://cf.shopee.co.id/file/{$cat['image']}" : null;

                $category = Category::updateOrCreate(
                    ['catid' => $catid],
                    [
                        'display_name' => $displayName,
                        'image_url'    => $imageUrl,
                    ]
                );

                if ($parentCatId) {
                    $parent = Category::where('catid', $parentCatId)->first();
                    if ($parent) {
                        $category->parent_id = $parent->id;
                        $category->save();
                    }
                }

                $this->line("{$prefix}ID: {$catid} | Name: {$displayName}");
            }

            if (!empty($cat['children']) && is_array($cat['children'])) {
                $this->printCategories($cat['children'], $prefix . '--', $catid);
            }
        }
    }
}
