<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Form verilerini al
    $vtServer = $_POST['vtServer'];
    $vtName = $_POST['vtName'];
    $vtUser = $_POST['vtUser'];
    $vtPass = $_POST['vtPass'];

    // DB.php dosyasını yazdırmak için değişkenleri atayın
    $dbhost = $vtServer;
    $dbname = $vtName;
    $dbuser = $vtUser;
    $dbpwd = $vtPass;
$username = $_POST['Username'];
$password = $_POST['password'];
    // DB.php dosyasını kaydetmek için yolu belirtin
    $dbFilePath = 'process/db.php';

    // DB.php dosyasını açın ve içeriği yazdırın
    $dbFile = fopen($dbFilePath, 'w');
    fwrite($dbFile, "<?php\nglobal \$dbhost, \$dbname, \$dbuser, \$dbpwd;\n\$dbhost='$dbhost';\n\$dbname='$dbname';\n\$dbuser='$dbuser';\n\$dbpwd='$dbpwd';\n?>");
    fclose($dbFile);

// Veritabanı bilgileri
$host = $dbhost;
$kullaniciAdi = $dbuser;
$sifre = $dbpwd;
$veritabaniAdi = $dbname;

try {
    // PDO bağlantısı
    $baglanti = new PDO("mysql:host=$host;charset=utf8mb4", $kullaniciAdi, $sifre);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // // Veritabanı ve tabloların oluşturulması
    // $baglanti->exec("CREATE DATABASE IF NOT EXISTS $veritabaniAdi CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci");
    // $baglanti->exec("USE $veritabaniAdi");

    // Users tablosunu oluştur
    $baglanti->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userMail VARCHAR(80) NOT NULL,
            userPwd VARCHAR(32) NOT NULL
        )
    ");

    // Domains tablosunu oluştur
    $baglanti->exec("
        CREATE TABLE IF NOT EXISTS domains (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            domainName VARCHAR(80) NOT NULL,
            domainExpiry VARCHAR(80) NOT NULL,
            domainDrop VARCHAR(80) NOT NULL
        )
    ");

    // Formdan gelen verileri users tablosuna ekle
        // Şifre MD5 ile işleniyor
        $password = md5($pasword);
        $sql = "INSERT INTO users (userMail, userPwd) VALUES (?, ?)";
        $stmt = $baglanti->prepare($sql);
        $stmt->execute([$username, $password]);
        // echo "Kullanıcı başarıyla kaydedildi.";
        header("Location: index"); // index.php'ye yönlendirme
        exit(); // işlemi sonlandır

} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}


}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DET INSTALLER</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
          <form action="" method="post">
            <div class="form-group">
              <h4>Veritabanı Bağlantısı</h4>
              <label for="vtServer">Veri Tabanı Sunucusu</label>
              <input type="text" name="vtServer" class="form-control" placeholder="localhost" required>
            </div>
            <div class="form-group">
              <label for="vtName">Veritabanı Adı <small>Henüz oluşturmadıysanız, biz oluşturacağız.</small></label>
              <input type="text" name="vtName" class="form-control" placeholder="detDB" required>
            </div>
            <div class="form-group">
              <label for="vtUser">Veritabanı Kullanıcı Adı</label>
              <input type="text" name="vtUser" class="form-control" placeholder="root" required>
            </div>
            <div class="form-group">
              <label for="vtPass">Veritabanı Kullanıcı Şifresi</label>
              <input type="text" name="vtPass" class="form-control" placeholder="*****">
            </div>
            <div class="form-group">
              <h4>Sisteme Giriş Bilgileri</h4>
              <label for="Username">E-Posta Adresiniz</label>
              <input type="mail" name="Username" class="form-control" placeholder="demo@demo.com" required>
            </div>
            <div class="form-group">
              <label for="password">Şifre Oluştur.</label>
              <input type="text" name="password" class="form-control" placeholder="******" required>
            </div>

            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Send message">
            </div>
          </form>
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