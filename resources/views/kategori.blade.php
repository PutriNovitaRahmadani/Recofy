<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekomendasi Produk - Recofy</title>
  <link rel="icon" href="{{ asset('assets/img/recofy.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('assets/css/kategori.css') }}">
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
          <div class="greetingg">Hi, {{ auth()->user()->username ?? auth()->user()->name }}!</div>
          <a href="{{ route('profile') }}">
            <div class="avatar" style="cursor: pointer;">
              <img src="{{ auth()->user()->img_profile ? asset('img_profiles/' . auth()->user()->img_profile) : asset('assets/img/pp kosong.jpg') }}" 
                  alt="Profil" />
            </div>
          </a>
        </div>
      </div>

      <div class="kembali-wrapper">
        <button type="button" class="btn-kembali" onclick="window.location='{{ route('live-accounts.index')}}'">
          <span>Kembali</span>
        </button>
      </div>

      <div class="kategori-wrapper">

        {{-- ALERT SUKSES --}}
        @if (session('success'))
          <div class="alert-success" style="background:#d1e7dd; color:#0f5132; border:1px solid #badbcc; padding:10px 15px; border-radius:8px; margin-bottom:15px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
          </div>
        @endif

        {{-- FORM UPDATE --}}
        <form id="formKategori" method="POST" action="{{ route('live_accounts.categories.update', $liveAccount->id) }}">
          @csrf
          @method('PUT')

          <div class="kategori-header">
            <h3>Kategori saat ini</h3>
            <button type="submit" class="btn-simpan">
              <i class="fa-solid fa-save"></i> Simpan
            </button>
          </div>

          <div class="kategori-saat-ini" id="kategoriTerpilihContainer">
            @forelse ($selectedCategories as $kategori)
              <div class="kategori-box" data-id="{{ $kategori->id }}">
              <div class="left" style="display:flex; align-items:center; gap:8px;">
                <img src="{{ $kategori->image_url }}" 
                    alt="{{ $kategori->display_name }}" 
                    style="width:30px; height:30px; object-fit:cover;">
                <span>{{ $kategori->display_name }}</span>
              </div>
              <button type="button" class="hapus-btn" onclick="hapusKategori({{ $liveAccount->id }}, {{ $kategori->id }})"
                      style="background:none; border:none; cursor:pointer;">
                <img src="https://cdn-icons-png.flaticon.com/128/484/484662.png" alt="hapus" style="width:20px; height:20px;">
              </button>
              <input type="hidden" name="categories[]" value="{{ $kategori->id }}">
            </div>
            @empty
              <p>Anda belum memilih Kategori</p>
            @endforelse
          </div>
        </form>

        <!-- Form hapus kategori (hidden) -->
        <form id="formHapusKategori" method="POST" style="display:none;">
          @csrf
          @method('DELETE')
        </form>

        <div class="kategori-tersedia">
        @forelse ($availableCategories as $category)
          <div class="kategori-item" data-id="{{ $category->id }}">
            <img src="{{ $category->image_url }}" 
                alt="{{ $category->display_name }}" 
                style="width:50px; height:50px; object-fit:cover; margin-right:8px;">
            <span>{{ $category->display_name }}</span>
          </div>
        @empty
          <p>Tidak ada kategori tersedia</p>
        @endforelse
      </div>
      </div>
    </div>
  </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const kategoriSaatIniContainer = document.querySelector(".kategori-saat-ini");
  const kategoriTersediaContainer = document.querySelector(".kategori-tersedia");

  kategoriTersediaContainer.querySelectorAll(".kategori-item").forEach(item => {
    item.addEventListener("click", function () {
      const labelText = item.querySelector("span").innerText;
      const imageSrc = item.querySelector("img") ? item.querySelector("img").src : '';

      // Cek apakah sudah ada
      const sudahAda = [...kategoriSaatIniContainer.querySelectorAll(".kategori-box span")]
        .some(span => span.innerText === labelText);
      if (sudahAda) return;

      const pesanKosong = kategoriSaatIniContainer.querySelector("p");
      if (pesanKosong) pesanKosong.remove();

      const newKategoriBox = document.createElement("div");
      newKategoriBox.className = "kategori-box";
      newKategoriBox.innerHTML = `
        <div class="left" style="display:flex; align-items:center; gap:8px;">
          <img src="${imageSrc}" alt="${labelText}" style="width:30px; height:30px; object-fit:cover;">
          <span>${labelText}</span>
        </div>
        <button class="hapus-btn" type="button">
          <img src="https://cdn-icons-png.flaticon.com/128/484/484662.png" alt="hapus" style="width:20px; height:20px;">
        </button>
        <input type="hidden" name="categories[]" value="${item.dataset.id}">
      `;

      kategoriSaatIniContainer.appendChild(newKategoriBox);
      item.remove();

      // Tombol hapus
      newKategoriBox.querySelector(".hapus-btn").addEventListener("click", function () {
        const newItem = document.createElement("div");
        newItem.className = "kategori-item";
        newItem.dataset.id = item.dataset.id;
        newItem.innerHTML = `<img src="${imageSrc}" alt="${labelText}" style="width:50px; height:50px; object-fit:cover; margin-right:8px;"><span>${labelText}</span>`;
        newItem.addEventListener("click", arguments.callee);
        kategoriTersediaContainer.appendChild(newItem);

        newKategoriBox.remove();

        if (kategoriSaatIniContainer.querySelectorAll(".kategori-box").length === 0) {
          const pesanKosong = document.createElement("p");
          pesanKosong.textContent = "Anda belum memilih Kategori";
          kategoriSaatIniContainer.appendChild(pesanKosong);
        }
      });
    });
  });
});

function hapusKategori(liveAccountId, categoryId) {
  if (!confirm("Yakin ingin menghapus kategori ini?")) return;
  let form = document.getElementById("formHapusKategori");
  form.action = `/live-accounts/${liveAccountId}/categories/${categoryId}`;
  form.submit();
}
</script>
</body>
</html>
