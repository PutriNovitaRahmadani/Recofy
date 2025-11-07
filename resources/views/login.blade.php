<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin - Tokolabs</title>
  <link rel="icon" href="{{ asset('assets/img/recofy.png') }}" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="/assets/img/recofy.png" alt="Ilustrasi Login Tokolabs" />
    </div>
    <div class="right">
      <h2>Welcome Back!</h2>
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <i class="fas fa-envelope"></i>
          <input type="text" name="email" placeholder=" " value="{{ old('email') }}" required />
          <label>Email</label>
        </div>

        <div class="form-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder=" " required />
          <label>Password</label>
        </div>

        <div class="forgot-link">
          <a href="javascript:void(0)" onclick="openModal()">Lupa Password?</a>
        </div>

        <button type="submit">Login</button>

        {{-- Tampilkan error hanya dari login --}}
        @if ($errors->has('email') || $errors->has('password'))
          <p style="color:red; margin-top:10px;">{{ $errors->first() }}</p>
        @endif
      </form>
    </div>
  </div>


  <!-- Modal Lupa Password -->
<div id="modal" class="modal" style="display:none; align-items:center; justify-content:center; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
  <div class="modal-box" style="background:#fff; padding:20px; border-radius:8px; max-width:400px; width:100%; text-align:center;">
    <img src="/assets/img/4-foto.png" alt="Lock Icon" style="max-width: 100px; margin-bottom: 10px;">
    <h3>Lupa Password</h3>

    {{-- Form Kirim / Verifikasi OTP --}}
    @if (session('otp_phase'))
  <form method="POST" action="{{ route('password.verifyOtp') }}">
  @else
    <form method="POST" action="{{ route('password.sendOtp') }}">
  @endif
      @csrf

      {{-- Nomor WhatsApp --}}
      @if (!session('otp_phase'))
      <div class="form-group">
        <i class="fas fa-envelope"></i>
        <input type="text" name="phone" placeholder=" " value="{{ old('phone') }}" required />
        <label>Masukkan Nomor WhatsApp</label>

        {{-- Tampilkan error phone --}}
        @if ($errors->has('phone'))
          <small style="color: #ff0000; display:block; margin-top:5px;">
            {{ $errors->first('phone') }}
          </small>
        @endif
      </div>
      @endif

      {{-- OTP --}}
      @if (session('otp_phase'))
      <input type="hidden" name="phone" value="{{ session('otp_phone') }}">
      <div class="form-group">
        <i class="fas fa-key"></i>
        <input type="text" name="otp" placeholder=" " required />
        <label>Masukkan Kode OTP</label>
      </div>
      @endif

      {{-- Pesan Error --}}
      @if ($errors->has('otp'))
        <small style="color: #ff0000; display:block; margin-bottom:10px;">{{ $errors->first() }}</small>
      @endif

      {{-- Pesan Sukses --}}
      @if (session('success'))
        <small style="color: #008000; display:block; margin-bottom:10px;">{{ session('success') }}</small>
      @endif

      <div class="modal-buttons" style="display:flex; justify-content:center; gap:10px;">
        <button type="submit" class="send" style="padding:10px 20px; background:#28a745; color:#fff; border:none; border-radius:4px; cursor:pointer;">
          {{ session('otp_phase') ? 'Verifikasi OTP' : 'Kirim OTP' }}
        </button>
        <button type="button" class="close" onclick="closeModal()" style="padding:10px 20px; background:#dc3545; color:#fff; border:none; border-radius:4px; cursor:pointer;">Tutup</button>
      </div>

      {{-- Tombol Ulangi Proses --}}
      @if (session('otp_phase'))
      <div style="margin-top: 10px;">
        <a href="{{ route('modal.forgot-password.reset') }}" style="color: #007bff; text-decoration: underline;">
        </a>
      </div>
      @endif
    </form>
  </div>
</div>

<!-- Script Modal -->
<script>
  function openModal() {
    document.getElementById('modal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('modal').style.display = 'none';
  }

  window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) {
      closeModal();
    }
  }
</script>

{{-- Buka modal hanya jika session show_modal diset --}}
@if (session('show_modal'))
  <script>
    openModal();
  </script>
@php session()->forget('show_modal'); @endphp
@endif
