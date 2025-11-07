<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekomendasi Produk - Recofy</title>
  <link rel="icon" href="{{ asset('assets/img/recofy.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/produk.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    .produk-item {
      position: relative;
    }

    .produk-checkbox {
      position: absolute;
      bottom: 8px;
      right: 8px;
      width: 20px;
      height: 20px;
      accent-color: #4f46e5;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="menu-container">
        <div class="logo">
          <div class="brand">
            <img src="/assets/img/4-foto.png">
          </div>
          <div class="bars-wrapper" id="toggleSidebar">
            <i class="fa-solid fa-bars"></i>
          </div>
        </div>
        <ul>
          <li><a href="{{ url('dashboard') }}"><i class="fa-solid fa-gauge-high"></i> <span class="menu-text">Dashboard</span></a></li>
          <li class="products active"><a href="#"><i class="fa-solid fa-cart-shopping"></i> <span class="menu-text">Rekomendasi Produk</span></a></li>
          <li><a href="{{ url('schedule') }}"><i class="fa-solid fa-calendar-days"></i> <span class="menu-text">Scheduler</span></a></li>
          <li><a href="{{ url('akun') }}"><i class="fa-solid fa-gear"></i> <span class="menu-text">Pengaturan Akun</span></a></li>
        </ul>
      </div>
      <div class="logout-wrapper">
        <a href="#" onclick="konfirmasiLogout()" class="logout-btn">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span class="logout-text">Keluar</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
      <div class="navbar">
        <div class="nav-title">Rekomendasi Produk</div>
        <div class="user-area">
          <div class="greetingg">Hi, {{ $user ? ($user->username ?? $user->name) : 'Guest' }}!</div>
          <a href="{{ route('profile') }}">
            <div class="avatar" style="cursor: pointer;">
              <img src="{{ auth()->user()->img_profile ? asset('img_profiles/' . auth()->user()->img_profile) : asset('assets/img/pp kosong.jpg') }}" alt="Profil" />
            </div>
          </a>
        </div>
      </div>

      <!-- Halaman Produk -->
      <div class="halaman-produk-container">
        <div class="filter-search flex items-center justify-between gap-3">

          <!-- Search Bar -->
          <form action="{{ route('products.index') }}" method="GET" class="flex w-full max-w-xl">
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              class="search-bar flex-grow px-4 py-2 text-sm border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500"
              placeholder="Cari Produk dengan Komisi Ekstra">

            @if(request('sort'))
              <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <button 
              type="submit" 
              class="btn-cari px-5 py-2 text-sm font-semibold text-white bg-orange-500 rounded-r-md hover:bg-orange-600 transition-colors duration-200">
              Cari
            </button>
          </form>

          <!-- Checkbox Pilih Semua Produk -->
          <div class="pilih-semua flex items-center gap-2">
            <input type="checkbox" id="pilihSemua" class="w-4 h-4 cursor-pointer accent-indigo-600">
            <label for="pilihSemua" class="text-sm text-gray-700 select-none cursor-pointer">
              Pilih semua produk di halaman ini
            </label>
          </div>

          <!-- Dropdown Urutkan -->
          <div class="dropdown-group">
            <form id="sortForm" action="{{ route('produk.index') }}" method="GET">
              @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
              @endif

              <select 
                name="sort" 
                class="sort-select px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                onchange="document.getElementById('sortForm').submit()">
                <option disabled {{ $sort ? '' : 'selected' }}>Urutkan</option>
                <option value="komisi_tertinggi" {{ $sort == 'komisi_tertinggi' ? 'selected' : '' }}>Komisi Tertinggi</option>
                <option value="rating_tertinggi" {{ $sort == 'rating_tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="terlaris" {{ $sort == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="trending" {{ $sort == 'trending' ? 'selected' : '' }}>Trending 🔥</option>
              </select>
            </form>
          </div>
        </div>
      </div>

      <!-- Grid Produk -->
      <div class="produk-grid">
    @forelse ($products as $item)
        <a href="{{ $item->product_link }}" target="_blank" class="produk-item-link">
            <div class="produk-item relative">
                {{-- Komisi --}}
                @if(isset($item->commission_nominal))
                    <div class="produk-komisi">
                         Rp {{ number_format($item->commission_nominal / 1000, 0, ',', '.') }}
                    </div>
                @endif

                <img src="{{ $item->image }}" alt="Gambar Produk" class="produk-img">

                <div class="produk-info">
                    <div class="produk-header">
                        <div class="produk-rating">
                            <span class="rating-icon">⭐</span>{{ number_format($item->rating_star ?? 0, 1) }}
                        </div>
                        <span class="produk-harga">
                            Rp {{ number_format($item->price_min / 1000, 0, ',', '.') }}
                            @if(isset($item->price_max) && $item->price_max != $item->price_min)
                                - Rp {{ number_format($item->price_max / 1000, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>

                    <div class="produk-nama">
                        {{ \Illuminate\Support\Str::limit($item->title ?? '-', 100) }}
                    </div>

                    <div class="produk-rating-terjual">
                        <div class="produk-terjual">
                            {{ $item->historical_sold >= 1000 ? round($item->historical_sold/1000).'k' : $item->historical_sold }} terjual
                        </div>
                    </div>
                </div>

                <input type="checkbox" class="produk-checkbox" value="{{ $item->product_link }}">
            </div>
        </a>
    @empty
        <p>Tidak ada produk ditemukan.</p>
    @endforelse
</div>

      <div class="buat-link-container flex items-center justify-end gap-4">
        <div id="jumlahChecklist" class="text-gray-700 text-sm font-medium">
          0 produk dipilih
        </div>

        <button id="batalChecklist" class="btn-batal">
          <span>Batal</span>
        </button>

        <button id="buatLinkMassal" class="btn-buat-link">
          <i class="fa-solid fa-circle-plus"></i>
          <span>Buat Link masal</span>
        </button>
      </div>

      <!-- Pagination Laravel -->
      @if ($products->hasPages())
        <div class="pagination-container">
          <div class="pagination-wrapper">
            {{ $products->links('vendor.pagination.bootstrap-5') }}
          </div>
        </div>
      @endif
    </div>
  </div>

  <script>
    function konfirmasiLogout() {
      if (confirm("Apakah Anda yakin ingin logout?")) {
        window.location.href = "/";
      }
    }

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');

    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    });

    // CSV dari produk yang dicentang
    document.getElementById("buatLinkMassal").addEventListener("click", function() {
      const checked = document.querySelectorAll(".produk-checkbox:checked");
      if (checked.length === 0) {
        alert("Pilih minimal satu produk terlebih dahulu!");
        return;
      }

      let csvContent = "data:text/csv;charset=utf-8,";
      checked.forEach(chk => {
        csvContent += chk.value + "\n";
      });

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", "link_produk.csv");
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });

    // Update jumlah produk dicentang
    const jumlahChecklist = document.getElementById("jumlahChecklist");
    const checkboxes = document.querySelectorAll(".produk-checkbox");

    function updateJumlahChecklist() {
      const count = document.querySelectorAll(".produk-checkbox:checked").length;
      jumlahChecklist.textContent = `${count} produk dipilih`;
    }

    checkboxes.forEach(chk => {
      chk.addEventListener("change", updateJumlahChecklist);
    });

    document.getElementById("batalChecklist").addEventListener("click", function() {
      checkboxes.forEach(chk => chk.checked = false);
      updateJumlahChecklist();
      const pilihSemua = document.getElementById("pilihSemua");
      if (pilihSemua.checked) pilihSemua.checked = false;
      alert("Semua produk yang dipilih akan dibatalkan!");
    });

    const pilihSemua = document.getElementById("pilihSemua");
    if (pilihSemua) {
      pilihSemua.addEventListener("change", function() {
        const allCheckboxes = document.querySelectorAll(".produk-checkbox");
        allCheckboxes.forEach(chk => chk.checked = pilihSemua.checked);
        updateJumlahChecklist();
      });
    }
  </script>
</body>
</html>
