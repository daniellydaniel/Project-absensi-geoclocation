<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon" />
  <title>Login | Sistem Absensi Sekolah</title>

  <!-- ========== All CSS files linkup ========= -->
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>" />
  <style>
    body {
      background-color: #f0f4ff;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #dbeafe, #eff6ff);
    }

    .signin-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-card {
      background: #ffffff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
    }

    .login-card img {
      max-height: 200px;
      margin-bottom: 20px;
    }

    .login-card h4 {
      font-weight: bold;
      color: #2a2a86;
    }

    .btn-primary {
      background-color: #2a2a86;
      border: none;
    }

    .btn-primary:hover {
      background-color: #1f1f6d;
    }

    .bg-shape {
      position: fixed;
      bottom: 0;
      left: 0;
      z-index: -1;
      width: 100%;
      opacity: 0.4;
    }
  </style>
</head>

<body>

  <section class="signin-wrapper">
    <div class="login-card text-center">
      <img src="<?= base_url('assets/images/logo-sekolah.png') ?>" alt="Logo Sekolah" class="mb-3" style="height: 70px;">
      <img src="<?= base_url('assets/images/auth/school_login.svg') ?>" alt="" class="img-fluid mb-4">

      <h4 class="mt-3">SEKOLAH DASAR BETHANY SCHOOL</h4>
      <p class="text-muted mb-3">Sistem Informasi Absensi Digital</p>
      <p class="mb-4 text-muted">Silakan login sebagai <strong>Guru</strong> atau <strong>Kepala Sekolah</strong></p>


      <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-danger">
          <?= session()->getFlashdata('pesan') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('login') ?>" method="POST">
        <div class="mb-3 text-start">
          <label class="form-label fw-bold">Username</label>
          <input type="text" name="username" class="form-control <?= (isset($validation) && $validation->hasError('username')) ? 'is-invalid' : '' ?>" value="<?= old('username') ?>" placeholder="Masukkan Username">
          <?php if (isset($validation) && $validation->hasError('username')) : ?>
            <div class="invalid-feedback">
              <?= $validation->getError('username') ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="mb-4 text-start">
          <label class="form-label fw-bold">Password</label>
          <input type="password" name="password" class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" placeholder="Masukkan Password">
          <?php if (isset($validation) && $validation->hasError('password')) : ?>
            <div class="invalid-feedback">
              <?= $validation->getError('password') ?>
            </div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary w-100">Masuk</button>
      </form>
    </div>
  </section>

  <!-- Shape Background (opsional) -->
  <img class="bg-shape" src="<?= base_url('assets/images/auth/bg_shape_wave.svg') ?>" alt="Shape">

  <!-- JS -->
  <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>