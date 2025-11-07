<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            box-sizing: border-box;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .trend-up {
            color: #10b981;
        }
        .trend-down {
            color: #ef4444;
        }
        .live-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="gradient-bg py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-4">
                <button onclick="history.back()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center mr-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </button>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Detail Produk Analytics</h1>
            <p class="text-blue-100">Dashboard lengkap performa produk dan metadata penjualan</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Produk -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3">
                    <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center mb-4">
                        <img src="https://cf.shopee.co.id/file/{{ $product->image }}" alt="Gambar Produk" class="object-contain h-full w-full rounded-lg">
                    </div>
                </div>
                <div class="lg:w-2/3">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $product->title ?? $product->name ?? 'Nama produk tidak tersedia' }}
                    </h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-2">
                                {{-- Nilai rating --}}
                                <span class="text-2xl font-bold text-yellow-500">
                                    {{ number_format($product->rating_star ?? 0, 1) }}
                                </span>

                                {{-- Bintang dinamis --}}
                                <div class="flex ml-2">
                                    @php
                                        $rating = $product->rating_star ?? 0;
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    {{-- Bintang penuh --}}
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.285-3.957a1 1 0 00-.364-1.118L2.037 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endfor

                                    {{-- Setengah bintang --}}
                                    @if ($halfStar)
                                        <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="halfGrad">
                                                    <stop offset="50%" stop-color="currentColor"/>
                                                    <stop offset="50%" stop-color="currentColor" stop-opacity="0.3"/>
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#halfGrad)" stroke="currentColor" stroke-width="1"
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.285-3.957a1 1 0 00-.364-1.118L2.037 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endif

                                    {{-- Bintang kosong --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.285-3.957a1 1 0 00-.364-1.118L2.037 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Rating</p>
                        </div>

                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 mb-2">
                                {{ number_format($product->rating_count) }}
                            </div>
                            <p class="text-sm text-gray-600">Total Review</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-500 mb-2">❤️ 
                                {{ number_format($product->liked_count) }}
                            </div>
                            <p class="text-sm text-gray-600">Likes</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 mb-2">
                                {{ number_format($product->historical_sold) }}
                            </div>
                            <p class="text-sm text-gray-600">Terjual</p>
                        </div>

                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-2">
                                {{ number_format($product->stock) }}
                            </div>
                            <p class="text-sm text-gray-600">Total Stok</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            📱 Kategori: 
                            {{ $product->categories->pluck('display_name')->join(' > ') }}
                        </span>
                    </div>
                    
                    @php
                        $priceMin = $product->price_min / 100000;
                        $priceMax = $product->price_max / 100000;
                    @endphp

                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="bg-green-100 px-4 py-2 rounded-lg">
                            <span class="text-green-800 font-semibold">
                                @if($priceMin != $priceMax)
                                    Rp {{ number_format($priceMin, 0, ',', '.') }} - Rp {{ number_format($priceMax, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($priceMin, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="bg-red-100 px-3 py-2 rounded-lg flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full live-pulse mr-2"></div>
                            <span class="text-red-800 font-medium">🔴 3 Live Streaming</span>
                        </div>  
                            <div class="relative inline-block">
                                <button 
                                    id="copyBtn"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                                    onclick="copyLink({{ json_encode($product->product_link) }})">
                                    🔗 Copy Link Produk
                                </button>
                                
                                <!-- Tooltip -->
                                <div id="copyTooltip" class="absolute left-1/2 -top-10 -translate-x-1/2 bg-black/80 text-white text-xs px-3 py-1 rounded opacity-0 transition-all duration-300 transform scale-90 pointer-events-none">
                                    Link tersalin!
                                    <div class="absolute bottom-[-4px] left-1/2 -translate-x-1/2 w-2 h-2 bg-black/80 rotate-45"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Komisi -->
        @php
            // Hitung total komisi
            $totalCommission = $product->seller_commission + $product->shopee_commission;
        @endphp

        <div class="bg-white rounded-xl card-shadow p-6 mt-8 mb-10">
            <h3 class="text-xl font-bold text-gray-900 mb-6">💼 Informasi Komisi</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Komisi -->
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-1">
                        Rp {{ number_format($totalCommission, 0, ',', '.') }}
                    </div>
                    <p class="text-sm text-gray-600">Total Komisi</p>
                </div>

                <!-- Seller -->
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600 mb-1">
                        Rp {{ number_format($product->seller_commission, 0, ',', '.') }}
                    </div>
                    <p class="text-sm text-gray-600">Seller</p>
                </div>

                <!-- Shopee -->
                <div class="bg-orange-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-orange-600 mb-1">
                        Rp {{ number_format($product->shopee_commission, 0, ',', '.') }}
                    </div>
                    <p class="text-sm text-gray-600">Shopee</p>
                </div>
            </div>
        </div>

        <!-- Metadata Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ringkasan Penjualan -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    📊 Ringkasan Penjualan
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-gray-700">Penjualan Total</span>
                        <span class="font-bold text-blue-600">{{ number_format($product->historical_sold) }} unit</span>
                    </div>
                    
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-gray-700">Penjualan Total Varian</span>
                    <span class="font-bold text-purple-600">{{ $product->formatted_total_sold }} unit</span>
                </div>
                    
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-gray-700">Rata-rata/Bulan</span>
                    <span class="font-bold text-green-600">
                        {{ number_format($rataPerBulan, 0, ',', '.') }} unit
                    </span>
                </div>
                    
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <span class="text-gray-700">Penjualan 30 Hari</span>
                        <span class="font-bold text-orange-600 trend-up">unit ↗️</span>
                    </div>
                </div>
            </div>

            <!-- Metrik Pendapatan -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    💰 Metrik Pendapatan
                </h3>
                
                    @php
                        $priceMin = $product->price_min / 100000; // sesuaikan skala sesuai DB
                        $priceMax = $product->price_max / 100000;
                    @endphp

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-lg">
                            <span class="text-gray-700">Rentang Harga</span>
                            <span class="font-bold text-emerald-600">
                                @if($priceMin != $priceMax)
                                    Rp {{ number_format($priceMin, 0, ',', '.') }} - Rp {{ number_format($priceMax, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($priceMin, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    
                    <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg">
                        <span class="text-gray-700">Total Pendapatan</span>
                        <span class="font-bold text-indigo-600">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-pink-50 rounded-lg">
                        <span class="text-gray-700">Omset Varian</span>
                        <span class="font-bold text-pink-600">
                            Rp {{ number_format($omsetVarian, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-cyan-50 rounded-lg">
                        <span class="text-gray-700">Rata-rata/Bulan</span>
                        <span class="font-bold text-cyan-600">
                            Rp {{ number_format($rataPendapatanPerBulan, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <span class="text-gray-700">Pendapatan 30 Hari</span>
                        <span class="font-bold text-yellow-600 trend-up">Rp ↗️</span>
                    </div>
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    📋 Detail Produk
                </h3>
                
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-gray-700">Tanggal Upload</span>
                            <span class="font-bold text-slate-600">
                                {{ \Carbon\Carbon::parse($product->ctime)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-teal-50 rounded-lg">
                        <span class="text-gray-700">Umur Produk</span>
                        @php
                            use Carbon\Carbon;

                            $ctime = $product->ctime;

                            try {
                                if (is_numeric($ctime)) {
                                    $ctimeInt = (int) $ctime;
                                    // Deteksi milidetik atau detik
                                    $createdAt = strlen((string) $ctimeInt) > 10
                                        ? Carbon::createFromTimestampMs($ctimeInt)
                                        : Carbon::createFromTimestamp($ctimeInt);
                                } else {
                                    $createdAt = Carbon::parse($ctime);
                                }
                            } catch (\Exception $e) {
                                $createdAt = now();
                            }

                            // Hitung umur produk dalam bulan (dibulatkan ke bawah agar natural)
                            $diffDays = $createdAt->diffInDays(now());
                            $umurProduk = max(1, floor($diffDays / 30)); // 14.5 → 14 bulan
                        @endphp

                        <span class="font-bold text-teal-600">{{ $umurProduk }} bulan</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 bg-violet-50 rounded-lg">
                        <span class="text-gray-700">Jumlah Varian</span>
                        <span class="font-bold text-violet-600">{{ $product->models->count() }} varian</span>
                    </div>
                    
                    <div class="p-3 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700">Trend Penjualan</span>
                            <span class="font-bold text-green-600 trend-up">📈</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Performa sangat baik vs bulan lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Penjualan & Stok Per Varian -->
        <div class="bg-white rounded-xl card-shadow p-6 mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">📦 Detail Penjualan & Stok Per Varian</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Varian</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Harga</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Terjual</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($product->models as $model)
                            <tr class="hover:bg-gray-50 {{ $model->stock == 0 ? 'text-red-400' : '' }}">
                                <!-- Nama Varian -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span class="font-medium">
                                            {!! html_entity_decode(json_decode('"' . $model->name . '"')) ?? 'Varian #' . $loop->iteration !!}
                                        </span>
                                    </div>
                                </td>

                                <!-- Harga -->
                                <td class="px-4 py-3 text-center font-semibold">
                                    Rp {{ number_format($model->price / 100000, 0, ',', '.') }}
                                </td>

                                <!-- Terjual -->
                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ number_format($model->sold, 0, ',', '.') }}
                                </td>

                                <!-- Stok -->
                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $model->stock == 0 ? 'Habis' : number_format($model->stock, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <div class="bg-white rounded-xl card-shadow p-6 mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">📈 Grafik Penjualan & Pendapatan</h3>
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg h-64 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <p class="text-gray-600 font-medium">Area untuk grafik penjualan dan trend pendapatan</p>
                    <p class="text-sm text-gray-500 mt-2">Data visualisasi performa produk dari waktu ke waktu</p>
                </div>
            </div>
        </div>
    </div>
<script>
function copyLink(link) {
    if (!navigator.clipboard) {
        const textarea = document.createElement('textarea');
        textarea.value = link;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showTooltip();
        return;
    }

    navigator.clipboard.writeText(link)
        .then(() => {
            showTooltip();
        })
        .catch(err => {
            console.error('Gagal menyalin: ', err);
        });
}

function showTooltip() {
    const tooltip = document.getElementById('copyTooltip');
    tooltip.classList.remove('opacity-0', 'scale-90');
    tooltip.classList.add('opacity-100', 'scale-100');

    setTimeout(() => {
        tooltip.classList.remove('opacity-100', 'scale-100');
        tooltip.classList.add('opacity-0', 'scale-90');
    }, 2000);
}
</script>
</body>
</html>