<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekomendasi Produk - TokoLabs</title>
  <link rel="icon" href="{{ asset('assets/img/recofy.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/etalase.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
      <li><a href="dashboard"><i class="fa-solid fa-gauge-high"></i> <span class="menu-text">Dashboard</span></a></li>
      <li class="etalase active"><a href="#"><i class="fa-solid fa-cart-shopping"></i> <span class="menu-text">Rekomendasi Produk</span></a></li>
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
    
  <!-- Akun Cards -->
<div class="rekomendasi-etalase">
  <div class="etalase-header">
    <h2></h2>
    <button class="btn-tambah-akun" onclick="openModal()">
      <i class="fa-solid fa-circle-plus"></i> Tambah Akun
    </button>
  </div>

  <div class="akun-grid" id="akunGrid">
  @foreach ($liveAccounts as $index => $akun)  
  <div class="akun-card">
    <div class="card-overlay"></div>
    <div class="akun-header">
      <div class="akun-number">{{ $index + 1 }}</div>
      <div class="akun-info-center">
        <div class="akun-info-wrapper">
        <p class="akun-nama">{{ '@' . $akun->name }}</p>
        <p class="akun-studio">{{ $akun->studio->name ?? 'Tanpa Studio' }}</p>
        </div>
        <div class="akun-dropdown">
  <span class="more-icon" onclick="toggleDropdown(this)">⋮</span>
  <div class="dropdown-menu">
    <button onclick="editAkun(this, {{ $akun->id }}, '{{ $akun->name }}', '{{ $akun->studio_id }}')">
      <span class="dropdown-icon"><i class="fa-solid fa-pen-to-square"></i></span>
      <span>Edit</span>
    </button>
    <form action="{{ route('live-accounts.destroy', $akun->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
      @csrf
      @method('DELETE')
      <button type="submit">
      <span class="dropdown-icon"><i class="fa-solid fa-trash-can"></i></span>
      <span>Hapus</span>
    </button>
    </form>
    </div>
    </div>
    </div>
    </div>

      <div class="akun-stats">
        <div><h3>100</h3><p>Produk</p></div>
      <div>
        <h3>{{ $akun->categories->count() }}</h3>
        <p>Kategori</p>
      </div>
      </div>

      <div class="akun-btns">
        <a href="{{ route('live_accounts.categories.edit', $akun->id) }}" class="btn-atur">
          <i class="fa-solid fa-gear"></i> Atur Kategori
        </a>
        <a href="{{ url('products') }}" class="btn-lihat">
          <i class="fa-solid fa-cart-shopping"></i> Lihat Semua Produk
        </a>
      </div>
    </div>
  @endforeach
</div>
  <!-- Pagination -->
  <div class="pagination" id="pagination"></div>

<!-- Modal Tambah Akun -->
<div class="modal-overlay" id="modal">
  <div class="modal-box">
    <div class="modal-close" onclick="closeModal()">
      <i class="fa-solid fa-xmark"></i>
    </div>

    <div class="modal-icon">
      <i class="fa-solid fa-user-plus"></i>
    </div>
    <h3>Tambah Akun Baru</h3>
    <p>Lengkapi Formulir Ini !</p>

    <form action="{{ route('live-accounts.store') }}" method="POST">
  @csrf
  <div class="modal-form">
    <div class="input-group">
      <i class="fa-solid fa-user"></i>
      <input type="text" name="name" placeholder="Masukkan Nama Akun" required />
    </div>
    <div class="input-group">
      <i class="fa-solid fa-building"></i>
      <select name="studio_id" required>
        <option disabled selected>Pilih Studio</option>
        @foreach ($studios as $studio)
          <option value="{{ $studio->id }}">{{ $studio->name }}</option>
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn-modal-submit">Tambah Akun</button>
  </div>
</form>
  </div>
</div>

<!-- Modal Edit Akun -->
<div class="edit-modal-overlay" id="editModal">
  <div class="edit-modal">
    
    <!-- Ikon lingkaran atas -->
    <div class="modal-icon-circle">
      <i class="fa-solid fa-user-pen"></i>
    </div>

    <h3>Edit Akun</h3>
    <p>Perbarui informasi akun</p>

    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      
      <!-- Input Nama Akun -->
      <div class="form-group">
        <span class="input-icon"><i class="fa-solid fa-user"></i></span>
        <input
          type="text"
          id="edit-nama-akun"
          name="name"
          placeholder="Masukkan Nama Akun"
          required
        >
      </div>

      <!-- Dropdown Studio -->
      <div class="form-group">
        <span class="input-icon"><i class="fa-solid fa-building"></i></span>
        <select id="edit-studio" name="studio_id" required>
          <option disabled selected>Pilih Studio</option>
          @foreach ($studios as $studio)
            <option value="{{ $studio->id }}">{{ $studio->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Tombol Simpan -->
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <!-- Tombol tutup -->
    <button class="btn-close" onclick="closeEditModal()">×</button>
  </div>
</div>



<!-- JavaScript Modal -->
 <script>
  function editAkun(button, id, name, studioId) {
      // isi data ke input
      document.getElementById('edit-nama-akun').value = name;
      document.getElementById('edit-studio').value = studioId;

      // set action form ke /live-accounts/{id}
      const form = document.getElementById('editForm');
      form.action = `/live-accounts/${id}`;

      openEditModal();
  }

  function openEditModal() {
    document.getElementById('editModal').style.display = 'flex';
    closeAllDropdown(); // Pastikan semua dropdown tertutup
  }

  function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
  }

  function toggleDropdown(element) {
    const dropdown = element.closest('.akun-dropdown')?.querySelector('.dropdown-menu');
    if (!dropdown) return;

    closeAllDropdown();
    dropdown.classList.toggle('show');
  }

  function closeAllDropdown() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.classList.remove('show');
      menu.style.display = 'none'; // opsional paksa tutup kalau pakai inline display
    });
  }

  window.addEventListener('click', function (event) {
    if (!event.target.closest('.akun-dropdown')) {
      closeAllDropdown();
    }
  });
</script>



<script>
  function openModal() {
    document.getElementById('modal').classList.add('show');
  }

  function closeModal() {
    document.getElementById('modal').classList.remove('show');
  }

  // Menutup modal jika klik di luar kotak modal
  window.addEventListener('click', function(e) {
  if (!e.target.closest('.akun-dropdown')) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.classList.remove('show');
    });
  }
});

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
<script>
  function toggleDropdown(trigger) {
    const dropdown = trigger.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.dropdown-menu');

    // Tutup semua dropdown lain
    allDropdowns.forEach(d => {
      if (d !== dropdown) d.style.display = 'none';
    });

    // Toggle dropdown sekarang
    if (dropdown.style.display === 'block') {
      dropdown.style.display = 'none';
    } else {
      dropdown.style.display = 'block';
    }
  }

  // Tutup dropdown jika klik di luar
  document.addEventListener('click', function(event) {
    const isClickOnTrigger = event.target.closest('.more-icon');
    const isClickInsideDropdown = event.target.closest('.dropdown-menu');

    if (!isClickOnTrigger && !isClickInsideDropdown) {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.style.display = 'none';
      });
    }
  });
</script>


<script>
  const akunGrid = document.getElementById("akunGrid");
  const akunCards = Array.from(akunGrid.getElementsByClassName("akun-card"));
  const paginationContainer = document.getElementById("pagination");
  const perPage = 9;
  let currentPage = 1;

  function showPage(page) {
    const start = (page - 1) * perPage;
    const end = start + perPage;

    akunCards.forEach((card, index) => {
      card.style.display = index >= start && index < end ? "block" : "none";
    });
  }

  function renderPagination() {
    const totalPages = Math.ceil(akunCards.length / perPage);
    paginationContainer.innerHTML = "";

    // Tombol Sebelumnya
    const prevBtn = document.createElement("button");
    prevBtn.innerHTML = "«";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
        renderPagination();
      }
    };
    paginationContainer.appendChild(prevBtn);

    // Tombol Angka
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      if (i === currentPage) btn.classList.add("active");
      btn.onclick = () => {
        currentPage = i;
        showPage(currentPage);
        renderPagination();
      };
      paginationContainer.appendChild(btn);
    }

    // Tombol Berikutnya
    const nextBtn = document.createElement("button");
    nextBtn.innerHTML = "»";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => {
      if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
        renderPagination();
      }
    };
    paginationContainer.appendChild(nextBtn);
  }

  document.addEventListener("DOMContentLoaded", () => {
    showPage(currentPage);
    renderPagination();
  });
</script>
</body>
</html>