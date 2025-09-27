<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekomendasi Produk - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/LogoTokolabs.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/produk.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
  <div class="menu-container">
    <div class="logo">
      <div class="brand">
        <i class="fa-solid fa-shop"></i>
        <span class="brand-text">TokoLabs</span>
      </div>
      <div class="bars-wrapper" id="toggleSidebar">
        <i class="fa-solid fa-bars"></i>
      </div>
    </div>
    <ul>
      <li><a href="dashboard"><i class="fa-solid fa-gauge-high"></i> <span class="menu-text">Dashboard</span></a></li>
      <li class="produk active"><a href="#"><i class="fa-solid fa-cart-shopping"></i> <span class="menu-text">Rekomendasi Produk</span></a></li>
      <li><a href="schedule"><i class="fa-solid fa-calendar-days"></i> <span class="menu-text">Scheduler</span></a></li>
      <li><a href="akun"><i class="fa-solid fa-gear"></i> <span class="menu-text">Pengaturan Akun</span></a></li>
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
        <!-- Hi, Welda dan avatar saja -->
        <div class="greetingg">Hi, {{ auth()->user()->username ?? auth()->user()->name }}!</div>
          <a href="{{ route('profile') }}">
            <div class="avatar" style="cursor: pointer;">
              <img src="{{ auth()->user()->img_profile ? asset('img_profiles/' . auth()->user()->img_profile) : asset('assets/img/pp kosong.jpg') }}" 
                  alt="Profil" />
          </div>
        </a>
      </div>
    </div>

 <!-- Halaman Produk -->
<div class="halaman-produk-container">
  <div class="filter-search">
    <input type="text" class="search-bar" placeholder="Search...">
    <div class="dropdown-group">
      <select class="filter-select">
        <option disabled selected>Pilih Kategori</option>
        @foreach ($categories as $category)
          <option value="{{ $category->slug }}">{{ $category->name }}</option>
        @endforeach
      </select>
      <select class="sort-select">
        <option disabled selected>Urutkan</option>
        <option value="harga_terendah">Harga Terendah</option>
        <option value="harga_tertinggi">Harga Tertinggi</option>
        <option value="rating_tertinggi">Rating Tertinggi</option>
        <option value="terlaris">Terlaris</option>
        <option value="terbaru">Terbaru</option>
      </select>
    </div>
    </div>
    
<div class="pagination-wrapper" id="paginationTop"></div>
<div class="produk-container" id="produkContainer">
  @foreach ($product as $item )
    <div class="produk-item">
    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="produk-img" />
    <div class="produk-info">
      <div class="produk-header">
        <div class="produk-rating">
          <span class="rating-icon">⭐</span>{{ $item->rating }}
        </div>
        <span class="produk-harga">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
      </div>
      <div class="produk-nama">{{ \Illuminate\Support\Str::limit($item->name, 40) }}</div>
      <div class="produk-rating-terjual">
        <div class="produk-terjual">{{ $item->total_sales }} terjual</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

  <!-- Tambahkan produk lain di sini -->
</div>

<!-- Tombol Buat Link Masal -->
<div class="buat-link-container">
  <button class="btn-buat-link">
    <i class="fa-solid fa-circle-plus"></i> Buat Link masal
  </button>
</div>

<div class="pagination-wrapper" id="paginationBottom"></div>
<script>
  const itemsPerPage = 18;
  const produkContainer = document.getElementById("produkContainer");
  const produkItems = Array.from(produkContainer.getElementsByClassName("produk-item"));
  const paginationTop = document.getElementById("paginationTop");
  const paginationBottom = document.getElementById("paginationBottom");

  let currentPage = 1;
  const totalPages = Math.ceil(produkItems.length / itemsPerPage);

  function showPage(page) {
    currentPage = page;

    // Tampilkan hanya item pada halaman saat ini
    produkItems.forEach((item, index) => {
      item.style.display = (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage)
        ? "block" : "none";
    });

    renderPaginationTop(currentPage);
    renderPaginationBottom(currentPage);
  }

  function renderPaginationTop(activePage) {
    paginationTop.innerHTML = "";

    // Info halaman (contoh: 2/5)
    const pageInfo = document.createElement("span");
    pageInfo.className = "page-info";
    pageInfo.textContent = `${activePage}/${totalPages}`;
    paginationTop.appendChild(pageInfo);

    // Tombol << (prev)
    const btnPrev = document.createElement("button");
    btnPrev.innerHTML = "&laquo;";
    btnPrev.disabled = activePage === 1;
    btnPrev.onclick = () => showPage(activePage - 1);
    paginationTop.appendChild(btnPrev);

    // Tombol >> (next)
    const btnNext = document.createElement("button");
    btnNext.innerHTML = "&raquo;";
    btnNext.disabled = activePage === totalPages;
    btnNext.onclick = () => showPage(activePage + 1);
    paginationTop.appendChild(btnNext);
  }

  function renderPaginationBottom(activePage) {
    paginationBottom.innerHTML = "";

    // Tombol << (prev)
    const btnPrev = document.createElement("button");
    btnPrev.innerHTML = "&laquo;";
    btnPrev.disabled = activePage === 1;
    btnPrev.onclick = () => showPage(activePage - 1);
    paginationBottom.appendChild(btnPrev);

    // Tampilkan max 5 tombol halaman
    const maxPagesShown = 5;
    let startPage = Math.max(1, activePage - 2);
    let endPage = Math.min(totalPages, startPage + maxPagesShown - 1);

    if (endPage - startPage < maxPagesShown - 1) {
      startPage = Math.max(1, endPage - maxPagesShown + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      if (i === activePage) {
        btn.classList.add("active");
        btn.disabled = true;
      }
      btn.onclick = () => showPage(i);
      paginationBottom.appendChild(btn);
    }

    // Tambahkan "..." jika masih ada halaman di akhir
    if (endPage < totalPages) {
      const dots = document.createElement("span");
      dots.textContent = "...";
      paginationBottom.appendChild(dots);
    }

    // Tombol >> (next)
    const btnNext = document.createElement("button");
    btnNext.innerHTML = "&raquo;";
    btnNext.disabled = activePage === totalPages;
    btnNext.onclick = () => showPage(activePage + 1);
    paginationBottom.appendChild(btnNext);
  }

  // Tampilkan halaman pertama saat pertama kali
  showPage(1);
</script>


  <script>
  function konfirmasiLogout() {
    const yakin = confirm("Apakah Anda yakin ingin logout?");
    if (yakin) window.location.href = "/";
  }

  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const toggleBtn = document.getElementById('toggleSidebar');

  toggleBtn.addEventListener('click', function () {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  });
</script>
</body>
</html>