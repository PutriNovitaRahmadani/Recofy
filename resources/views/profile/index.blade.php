<!DOCTYPE html>
<html lang="id">
<head>
  <style>
  .notif-popup {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    gap: 10px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    animation: fadeSlide 0.4s ease;
  }

  .notif-icon {
    font-size: 18px;
  }

  .notif-popup.hidden {
    display: none;
  }

  @keyframes fadeSlide {
    from {
      opacity: 0;
      transform: translateX(-50%) translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateX(-50%) translateY(0);
    }
  }
</style>

  <meta charset="UTF-8">
  <title>Pengaturan Akun - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/LogoTokolabs.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/index_profile.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <div class="container">
    <div id="notif-popup" class="notif-popup hidden">
      <span class="notif-icon">✔</span>
      <span id="notif-message">Notifikasi</span>
    </div>

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
      <li><a href="schedule"><i class="fa-solid fa-calendar-days"></i> <span class="menu-text">Scheduler</span></a></li>
      <li class="akun active"><a href="#"><i class="fa-solid fa-gear"></i> <span class="menu-text">Pengaturan Akun</span></a></li>
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
      <div class="nav-title">Pengaturan Akun</div>
      <div class="user-area">
        <div class="greetingg">Hi, {{ $user->username ?? $user->name }} </div>
        <div class="avatar">
          <img 
            src="{{ $user->img_profile ? asset('img_profiles/' . $user->img_profile) : asset('assets/img/pp kosong.jpg') }}" 
            alt="Profil" />
        </div>
        <div class="dropdown">
          <div class="dropdown-toggle" onclick="toggleDropdown()">

          </div>
        </div>
      </div>
    </div>

<div class="tab-wrapper">
  <div class="tab-card">
    <!-- Navigasi Tab -->
    <div class="tab-navigation">
      <button class="tab-button active" onclick="showTab('profile', this)">
        <i class="fas fa-user"></i> Profil
      </button>
      <button class="tab-button" onclick="showTab('password', this)">
        <i class="fas fa-lock"></i> Ganti Password
      </button>
    </div>

    <!-- Tab: Profil -->
    <div id="profile" class="tab-content active">
      <div class="profile-password-page">
        
        <!-- FOTO DI KIRI -->
        <div class="profile-section">
          <div class="profile-pic-container">
            <img 
              src="{{ $user->img_profile ? asset('img_profiles/' . $user->img_profile) : asset('assets/img/pp kosong.jpg') }}" 
              alt="Profile" 
              class="profile-pic" 
            />
            <label for="file-upload" class="camera-icon">
              <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="file-upload" name="img_profile" hidden form="profile-form" />
          </div>
          <p class="greeting">Hi, {{ $user->username ?? $user->name }}!</p>
        </div>

        <!-- FORM DI KANAN -->
        <div class="form-section">
           @if(session('success'))
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                showNotification(@json(session('success')));
              });
            </script>
          @endif

          <form id="profile-form" class="profile-info-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <input type="text" name="username" placeholder="Username" value="{{ old('username', $user->username) }}" required />
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required />
            <input type="tel" name="phone" placeholder="Nomor Telepon" value="{{ old('phone', $user->phone) }}" required />
            <button type="submit">Simpan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Tab: Ganti Password -->
    <div id="password" class="tab-content">
      <div class="profile-password-page">
        <div class="form-section">
          <form action="{{ route('password.update') }}" method="POST">
          @csrf
            <input type="password" name="current_password" placeholder="Password Lama" required />
            <input type="password" name="new_password" placeholder="Password Baru" required />
            <input type="password" name="new_password_confirmation" placeholder="Konfirmasi Password Baru" required />
            <button type="submit">Simpan Password</button>
          </form>
        <!-- Tambahkan jika ingin tampilkan error -->
      @if(session('success'))
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      showNotification(@json(session('success')));
    });
  </script>
@endif

@if($errors->any())
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      showNotification("Terjadi kesalahan. Silakan periksa kembali form.");
    });
  </script>
@endif
    </div>
  </div>
</div>

<script>
  function showTab(id, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));

    document.getElementById(id).classList.add('active');
    el.classList.add('active');
  }
</script>


  <script>
    function previewProfile(event) {
      const reader = new FileReader();
      reader.onload = function () {
        document.getElementById('profilePreview').src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
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
<script>
  function showNotification(message) {
    const notif = document.getElementById("notif-popup");
    const notifMessage = document.getElementById("notif-message");

    notifMessage.textContent = message;
    notif.classList.remove("hidden");

    setTimeout(() => {
      notif.classList.add("hidden");
    }, 3000);
  }
</script>
</body>
</html>