<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DET INSTALLER</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.css">
</head>
<body class="hold-transition install-page">
<div>
  <div class="login-logo">
    <a href="#"><b>DET</b> INSTALLER</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
        <div class="card-body row">
          <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
              <h2>Domain<strong> Expiry Tracker</strong></h2>
              <p class="lead mb-5">DET kurucusuna hoşgeldiniz. <br> Formu dolur, gönder. <br> Sistem hazır!.
              </p>
            </div>
          </div>
          <div class="col-7">
            <div class="form-group">
              <h4>Veritabanı Bağlantısı</h4>
              <label for="vtServer">Veri Tabanı Sunucusu</label>
              <input type="text" id="vtServer" class="form-control" placeholder="localhost" >
            </div>
            <div class="form-group">
              <label for="vtName">Veritabanı Adı <small>Henüz oluşturmadıysanız, biz oluşturacağız.</small></label>
              <input type="email" id="vtName" class="form-control" placeholder="detDB">
            </div>
            <div class="form-group">
              <label for="vtUser">Veritabanı Kullanıcı Adı</label>
              <input type="text" id="vtUser" class="form-control" placeholder="root">
            </div>
            <div class="form-group">
              <label for="vtPass">Veritabanı Kullanıcı Şifresi</label>
              <input type="text" id="vtPass" class="form-control" placeholder="*****">
            </div>
            <div class="form-group">
              <h4>Sisteme Giriş Bilgileri</h4>
              <label for="Username">E-Posta Adresiniz</label>
              <input type="mail" id="Username" class="form-control" placeholder="demo@demo.com">
            </div>
            <div class="form-group">
              <label for="password">Şifre Oluştur.</label>
              <input type="text" id="password" class="form-control" placeholder="******">
            </div>
            <div class="form-group">
              <label for="password2">Tekrar Şifre.</label>
              <input type="text" id="password2" class="form-control" placeholder="******">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Send message">
            </div>
          </div>
        </div>
      </div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
