<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMetadata;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductMetadataController extends Controller
{
    /**
     * Generate metadata untuk satu produk.
     */
    public function generate($id)
    {
        $product = Product::findOrFail($id);

        // Hitung umur produk
        $dibuat = Carbon::createFromTimestamp($product->ctime);
        $umur_bulan = max(1, $dibuat->diffInMonths(now())); // supaya tidak bagi nol
        $umur_hari = $dibuat->diffInDays(now());

        // Hitung ringkasan penjualan
        $total_penjualan = $product->historical_sold ?? 0;
        $penjualan_total_varian = $product->historical_sold ?? 0; // default, nanti bisa detail
        $penjualan_rata_rata_bulan = $total_penjualan / $umur_bulan;

        // TODO: hitung penjualan 30 hari terakhir dari tabel histories kalau sudah ada
        $penjualan_30_hari = 0;

        // Simpan/update metadata
        $metadata = ProductMetadata::updateOrCreate(
            ['product_id' => $product->item_id],
            [
                'penjualan_total' => $total_penjualan,
                'penjualan_total_varian' => $penjualan_total_varian,
                'penjualan_rata_rata_bulan' => $penjualan_rata_rata_bulan,
                'penjualan_30_hari' => $penjualan_30_hari,
                'dibuat' => $dibuat,
                'umur' => $umur_hari,
                'jumlah_varian' => $product->models_count ?? 0,
                'trend' => null, // bisa diisi algoritma analisis tren
            ]
        );

        return redirect()
            ->back()
            ->with('success', "Metadata produk {$product->name} berhasil di-generate!");
    }

    /**
     * Generate metadata untuk semua produk.
     */
    public function generateAll()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $dibuat = Carbon::createFromTimestamp($product->ctime);
            $umur_bulan = max(1, $dibuat->diffInMonths(now()));
            $umur_hari = $dibuat->diffInDays(now());

            $total_penjualan = $product->historical_sold ?? 0;
            $penjualan_total_varian = $product->historical_sold ?? 0;
            $penjualan_rata_rata_bulan = $total_penjualan / $umur_bulan;

            ProductMetadata::updateOrCreate(
                ['product_id' => $product->item_id],
                [
                    'penjualan_total' => $total_penjualan,
                    'penjualan_total_varian' => $penjualan_total_varian,
                    'penjualan_rata_rata_bulan' => $penjualan_rata_rata_bulan,
                    'penjualan_30_hari' => 0,
                    'dibuat' => $dibuat,
                    'umur' => $umur_hari,
                    'jumlah_varian' => $product->models_count ?? 0,
                    'trend' => null,
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', "Metadata semua produk berhasil di-generate!");
    }

    /**
     * Tampilkan metadata produk (opsional, misalnya untuk debug).
     */
    public function show($id)
    {
        $metadata = ProductMetadata::with('product')->where('product_id', $id)->firstOrFail();

        return view('metadata.show', compact('metadata'));
    }

    /**
     * Tambahan: Generate metadata setelah produk baru dimasukkan dari Shopee.
     * Tidak mengubah method lain, hanya tambahan opsional.
     */
    public function generateForNewProduct($itemId)
    {
        $product = Product::where('item_id', $itemId)->firstOrFail();

        $dibuat = Carbon::createFromTimestamp($product->ctime);
        $umur_bulan = max(1, $dibuat->diffInMonths(now()));
        $umur_hari = $dibuat->diffInDays(now());

        $total_penjualan = $product->historical_sold ?? 0;
        $penjualan_total_varian = $product->historical_sold ?? 0;
        $penjualan_rata_rata_bulan = $total_penjualan / $umur_bulan;

        ProductMetadata::updateOrCreate(
            ['product_id' => $product->item_id],
            [
                'penjualan_total' => $total_penjualan,
                'penjualan_total_varian' => $penjualan_total_varian,
                'penjualan_rata_rata_bulan' => $penjualan_rata_rata_bulan,
                'penjualan_30_hari' => 0,
                'dibuat' => $dibuat,
                'umur' => $umur_hari,
                'jumlah_varian' => $product->models_count ?? 0,
                'trend' => null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Metadata untuk produk {$product->name} berhasil dibuat."
        ]);
    }
}
