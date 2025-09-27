<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Scheduler - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/LogoTokolabs.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/schedule.css') }}">
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
      <li><a href="etalase"><i class="fa-solid fa-cart-shopping"></i> <span class="menu-text">Rekomendasi Produk</span></a></li>
      <li class="schedule active"><a href="#"><i class="fa-solid fa-calendar-days"></i> <span class="menu-text">Scheduler</span></a></li>
      <li><a href="profile"><i class="fa-solid fa-gear"></i> <span class="menu-text">Pengaturan Akun</span></a></li>
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
      <div class="nav-title">Scheduler</div>
      <div class="user-area">
        <div class="greetingg">Hi, {{ auth()->user()->username ?? auth()->user()->name }}!</div>
        <div class="avatar">
          <img src="{{ auth()->user()->img_profile ? asset('img_profiles/' . auth()->user()->img_profile) : asset('assets/img/pp kosong.jpg') }}" alt="Profil" />
        </div>
      </div>
    </div>

<div class="schedule-wrapper">
  <!-- Box Jadwal -->

  <!-- Box Terakhir Scraping -->
  <div class="schedule-box">
    <div class="icon-box green">
      <i class="fa-solid fa-clock-rotate-left"></i>
    </div>
    <div class="schedule-content">
      <p class="label">Terakhir Scraping</p>
      <p class="time" id="last-scraping">Kamis, 24 Juli 2025 Pukul 11:00</p>
    </div>
  </div>

  <!-- Tombol Run Scraping -->
  <button id="run-btn" class="btn-run">
    <i class="fa-solid fa-play"></i> Run Scraping
  </button>
</div>

<script>
  
  document.getElementById('run-btn').addEventListener('click', function () {
  const btn = this;
  const lastScraping = document.getElementById('last-scraping');

  // Ubah tombol jadi loading
  btn.disabled = true;
  btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Sedang Berjalan...`;

  // Ubah last scraping jadi status berjalan (warna hijau)
  lastScraping.innerHTML = `<span class="status-berjalan">Sedang Berjalan...</span>`;

  setTimeout(() => {
    const now = new Date();
    const hari = now.toLocaleDateString("id-ID", { weekday: "long" });
    const tanggal = now.toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" });
    const jam = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });

    lastScraping.textContent = `${hari}, ${tanggal} Pukul ${jam}`;
    btn.disabled = false;
    btn.innerHTML = `<i class="fa-solid fa-play"></i> Run Scraping`;
  }, 4000);
});
</script>
<script>
  // Toggle dropdown menu saat avatar diklik
  function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    if (menu) {
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
  }

  // Konfirmasi logout
  function konfirmasiLogout() {
    const yakin = confirm("Apakah Anda yakin ingin logout?");
    if (yakin) {
      window.location.href = "/";
    }
  }

  // Sembunyikan dropdown jika klik di luar
  document.addEventListener("click", function (event) {
    const dropdown = document.querySelector(".dropdown");
    const menu = document.getElementById("dropdownMenu");
    if (dropdown && menu && !dropdown.contains(event.target)) {
      menu.style.display = "none";
    }
  });

  // Toggle sidebar jika tombol tersedia
  document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("mainContent");
    const toggleBtn = document.getElementById("toggleSidebar");

    if (sidebar && mainContent && toggleBtn) {
      toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        mainContent.classList.toggle("expanded");
      });
    }
  });
</script>
</body>
</html>