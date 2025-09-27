<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/LogoTokolabs.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
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
      <li class="dashboard active"><a href="#"><i class="fa-solid fa-gauge-high"></i> <span class="menu-text">Dashboard</span></a></li>
      <li><a href="etalase"><i class="fa-solid fa-cart-shopping"></i> <span class="menu-text">Rekomendasi Produk</span></a></li>
      <li><a href="schedule"><i class="fa-solid fa-calendar-days"></i> <span class="menu-text">Scheduler</span></a></li>
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
    <div class="nav-title">Dashboard</div>

  <div class="user-area">
        <!-- Hi, Welda dan avatar saja -->
        <div class="greetingg">Hi, {{ $user->username ?? $user->name }}!</div>
        <a href="{{ route('profile') }}">
          <div class="avatar" style="cursor: pointer;">
            <img src="{{ $user->img_profile ? asset('img_profiles/' . $user->img_profile) : asset('assets/img/pp kosong.jpg') }}" 
            alt="Profil" />
          </div>
        </a>
      </div>
    </div>

    <!-- Isi Konten Dashboard -->
    <div class="dashboard-content">
      <!-- Umur Produk -->
      <div class="box umur-produk">
        <h3>Umur Produk</h3>
        <div class="tab-header">
          <span class="active">Produk Stabil (&gt; 90 hari)</span>
          <span>Produk Baru (&lt; 30 hari)</span>
        </div>
        <table class="produk-table">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Umur (Hari)</th>
              <th>Penjualan</th>
              <th>Tren</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>[100% ORI]Glad2Glow Glowing Renew...</td>
              <td>124</td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 178%</td>
            </tr>
            <tr>
              <td>SB - Hanasui Mattedorable Lip Cream...</td>
              <td>112</td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 168%</td>
            </tr>
            <tr>
              <td>Rak troli plastik rak toilet rak kamar mandi...</td>
              <td>98</td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 153%</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Top Produk Komisi Tinggi -->
      <div class="box top-komisi">
        <h3>Top 10 Produk Komisi Tinggi</h3>
        <table class="komisi-table">
          <thead>
            <tr>
              <th>Kategori</th>
              <th>Produk</th>
              <th>Komisi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="label skincare">Skincare</span></td>
              <td>[100% ORI]Glad2Glow Glowing Renew...</td>
              <td>Rp. 15.000 <br><small>15% + 5% Extra</small></td>
            </tr>
            <tr>
              <td><span class="label makeup">MakeUp</span></td>
              <td>SB - Hanasui Mattedorable Lip Cream...</td>
              <td>Rp. 13.500 <br><small>12% + 3% Extra</small></td>
            </tr>
            <tr>
              <td><span class="label dapur">Alat Dapur</span></td>
              <td>Rak troli plastik rak toilet rak kamar mandi...</td>
              <td>Rp. 13.000 <br><small>12% + 5% Extra</small></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Produk Terlaris dan Viral -->
      <div class="box produk-viral">
        <h3>Produk Terlaris Dan Viral</h3>
        <table class="produk-table">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Kategori</th>
              <th>Penjualan</th>
              <th>Tren</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>[100% ORI]Glad2Glow Glowing Renew...</td>
              <td><span class="label skincare">Skincare</span></td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 178%</td>
            </tr>
            <tr>
              <td>SB - Hanasui Mattedorable Lip Cream...</td>
              <td><span class="label makeup">Makeup</span></td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 165%</td>
            </tr>
            <tr>
              <td>Rak troli plastik rak toilet rak kamar mandi...</td>
              <td><span class="label dapur">Alat Dapur</span></td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 152%</td>
            </tr>
            <tr>
              <td>SETRIKA LISTRIK - GOSOKAN PAKAIAN...</td>
              <td><span class="label elektronik">Elektronik</span></td>
              <td>10RB+ Terjual</td>
              <td class="tren naik">↑ 145%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Script interaktif (dropdown & sidebar) -->
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