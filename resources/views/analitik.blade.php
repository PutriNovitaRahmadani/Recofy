<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Analitik - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/LogoTokolabs.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/analitik.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <i class="fa-solid fa-shop"></i> TokoLabs
      </div>
      <ul>
        <li><a href="dashboard"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
        <li><a href="produk"><i class="fa-solid fa-box-open"></i> Data Produk</a></li>
        <li class="active"><i class="fa-solid fa-chart-line"></i> Analitik</li>
        <li><a href="laporan.html"><i class="fa-solid fa-calendar-days"></i> Laporan Bulanan</a></li>
        <li><a href="ekspor.html"><i class="fa-solid fa-file-export"></i> Ekspor Data</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Navbar -->
      <div class="navbar">
        <div class="nav-title">Analitik</div>
        <div class="user-area">
          <div class="greeting">Hi, Welda!</div>
          <div class="notif">
            <i class="fa-regular fa-bell"></i>
            <span class="dot"></span>
          </div>
          <div class="avatar">
            <img src="https://i.pravatar.cc/100?img=5" alt="Avatar">
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="content">
        <div class="card">
          <h3>Grafik Penjualan Mingguan</h3>
          <canvas id="weeklyChart"></canvas>
        </div>

        <div class="card">
          <h3>Kategori Produk Terlaris</h3>
          <canvas id="categoryChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- ChartJS -->
  <script>
    new Chart(document.getElementById("weeklyChart"), {
      type: 'line',
      data: {
        labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
        datasets: [{
          label: "Penjualan (Rp)",
          data: [1200000, 1400000, 1000000, 1600000, 1300000, 1800000, 2000000],
          borderColor: "#6366f1",
          backgroundColor: "rgba(99, 102, 241, 0.1)",
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    new Chart(document.getElementById("categoryChart"), {
      type: 'bar',
      data: {
        labels: ["Hijab", "Kosmetik", "Aksesoris", "Pakaian", "Elektronik"],
        datasets: [{
          label: "Terjual",
          data: [1200, 950, 800, 670, 400],
          backgroundColor: ['#4f46e5', '#6366f1', '#10b981', '#f59e0b', '#ef4444']
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
</body>
</html>
