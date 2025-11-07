<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ShopeeService
{
    protected $client;
    protected $cookiePath;

    public function __construct()
    {
        $this->cookiePath = storage_path('cookies/shopee.json');

        $this->client = new Client([
            'base_uri' => 'https://shopee.co.id',
            'timeout'  => 30,
            'headers' => [
                'Accept'             => '*/*',
                'Accept-Language'    => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                'User-Agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36',
                'Referer'            => 'https://shopee.co.id/',
                'X-Requested-With'   => 'XMLHttpRequest',
                'X-Api-Source'       => 'pc',
                'X-Shopee-Language'  => 'id',
                'Content-Type'       => 'application/json',
                'Origin'             => 'https://shopee.co.id',
            ],
        ]);
    }

    protected function loadCookies(): CookieJar
    {
        if (!file_exists($this->cookiePath)) {
            return new CookieJar();
        }

        $cookies = json_decode(file_get_contents($this->cookiePath), true) ?? [];
        $cookieArray = [];
        foreach ($cookies as $cookie) {
            $cookieArray[$cookie['name']] = $cookie['value'];
        }

        return CookieJar::fromArray($cookieArray, '.shopee.co.id');
    }

    protected function saveCookies($response): void
    {
        $setCookies = $response->getHeader('Set-Cookie');
        if (empty($setCookies)) return;

        $existing = [];
        if (file_exists($this->cookiePath)) {
            $existing = json_decode(file_get_contents($this->cookiePath), true) ?? [];
        }

        foreach ($setCookies as $set) {
            $parts = explode(';', $set);
            $nv = explode('=', trim($parts[0]), 2);
            if (count($nv) !== 2) continue;

            $name = $nv[0];
            $value = $nv[1];

            $found = false;
            foreach ($existing as &$c) {
                if ($c['name'] === $name) {
                    $c['value'] = $value;
                    $found = true;
                    break;
                }
            }
            unset($c);

            if (!$found) {
                $existing[] = [
                    'domain'   => '.shopee.co.id',
                    'name'     => $name,
                    'value'    => $value,
                    'path'     => '/',
                    'httpOnly' => true,
                    'secure'   => true,
                ];
            }
        }

        file_put_contents($this->cookiePath, json_encode($existing, JSON_PRETTY_PRINT));
    }

    protected function getCsrfToken(): ?string
    {
        if (!file_exists($this->cookiePath)) {
            return null;
        }
        $cookies = json_decode(file_get_contents($this->cookiePath), true);
        foreach ($cookies as $cookie) {
            if ($cookie['name'] === 'csrftoken') {
                return $cookie['value'];
            }
        }
        return null;
    }

    protected function get($uri, $query = [])
    {
        $cookieJar = $this->loadCookies();
        $response = $this->client->get($uri, [
            'cookies' => $cookieJar,
            'query'   => $query,
        ]);

        $this->saveCookies($response);
        return json_decode($response->getBody(), true);
    }

    protected function post($uri, $payload = [])
    {
        $cookieJar = $this->loadCookies();
        $csrfToken = $this->getCsrfToken();

        $response = $this->client->post($uri, [
            'cookies' => $cookieJar,
            'headers' => [
                'x-csrftoken' => $csrfToken,
                'referer'     => 'https://shopee.co.id/',
                'origin'      => 'https://shopee.co.id',
                'content-type'=> 'application/json',
            ],
            'json' => $payload,
        ]);

        $this->saveCookies($response);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Ambil semua kategori
     */
    public function getCategories(): array
    {
        $data = $this->get('/api/v4/pages/get_category_tree');
        return $data['data']['category_list'] ?? [];
    }

    /**
     * ✅ Ambil rekomendasi produk per kategori
     */
    public function getRecommend($catid, $offset = 0, $limit = 10)
    {
        $payload = [
            'bundle' => 'category_landing_page',
            'catid'  => (int) $catid,
            'limit'  => (int) $limit,
            'offset' => (int) $offset,
        ];

        return $this->post('/api/v4/recommend/recommend_v2', $payload);
    }

    /**
     * Ambil produk per kategori (umum)
     */
    public function getProductsByCategory($catid, $offset = 0, $limit = 60)
    {
        $query = [
            'by'        => 'pop',
            'limit'     => (int) $limit,
            'match_id'  => (int) $catid,
            'newest'    => (int) $offset,
            'order'     => 'desc',
            'page_type' => 'category',
        ];

        return $this->get('/api/v4/search/search_items', $query);
    }

    /**
     * Ambil detail produk
     */
    public function getProductDetail($itemId, $shopId)
    {
        $query = [
            'itemid' => (int) $itemId,
            'shopid' => (int) $shopId
        ];

        return $this->get('/api/v4/item/get', $query);
    }
}
