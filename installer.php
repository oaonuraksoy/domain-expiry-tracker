<?php 
    if (isset($_POST["kaydet"])) {
$dbhost = $_POST['dbhost'] ?? '';
$dbname = $_POST['dbname'] ?? '';
$dbuser = $_POST['dbuser'] ?? '';
$dbpwd = $_POST['dbpwd'] ?? '';
$smtpMail=$_POST['smtpMail'] ?? '';
$smtpHost = $_POST['smtpHost'] ?? '';
$smtpPwd =$_POST['smtpPwd'] ?? '';
$toMail =$_POST['toMail'] ?? '';

$content = "<?php\n";
$content .= "global \$dbhost, \$dbname, \$dbuser, \$dbpwd;\n";
$content .= "\$dbhost='$dbhost';\n";
$content .= "\$dbname='$dbname';\n";
$content .= "\$dbuser='$dbuser';\n";
$content .= "\$dbpwd='$dbpwd';\n";
$content .= "\$smtpMail='$smtpMail';\n";
$content .= "\$smtpHost='$smtpHost';\n";
$content .= "\$smtpPwd='$smtpPwd';\n";
$content .= "\$toMail='$toMail';\n";
$content .= "?>";

file_put_contents('process/db.php', $content);

    require_once('process/db.php');

    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpwd);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Bağlantı başarılı";
    }
    catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }
    
    //test


    


    //test
    $table_name = "users";
    $stmt = $conn->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$dbname') AND (TABLE_NAME = '$table_name')");
    
    $tables = array("users", "domains");
    
    foreach($tables as $table_name) {
        $stmt = $conn->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '$dbname') AND (TABLE_NAME = '$table_name')");
    
        if($stmt->fetchColumn() == 0) {
            $sql = "";
            switch($table_name) {
                case "users":
                    $sql = "CREATE TABLE $table_name (
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                userMail VARCHAR(50) NOT NULL,
                                userPwd VARCHAR(50) NOT NULL
                            )";
                    break;
                case "domains":
                    $sql = "CREATE TABLE $table_name (
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                domainName VARCHAR(50) NOT NULL,
                                domainExpiry DATE NOT NULL,
                                domainDrop DATE NOT NULL
                            )";
                    break;
                default:
                    echo "Bilinmeyen tablo adı: " . $table_name;
                    break;
            }
    
            if(!empty($sql)) {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
              //  echo "Tablo oluşturuldu: " . $table_name . "<br>";
            }
        }
        else {
            //echo "Tablo zaten var: " . $table_name . "<br>";
        }
    }
    try {
        $userMail = $_POST['userMail'];
        $userPwd = md5($_POST['userPwd']);

        $sql = "INSERT INTO users (userMail, userPwd) VALUES (:userMail, :userPwd)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userMail', $userMail);
        $stmt->bindParam(':userPwd', $userPwd);
        $stmt->execute();

        // kullanıcı eklendikten sonra index.php'ye yönlendir
       
    }
    catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }
    $conn = null;
    header('Location: index.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DET Installer</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
</head>

<body class="hold-transition" style="position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   width:85%;
   ">
    <section class="content" style="padding-top: 5%;">

        <!-- Default box -->
        <div class="card">
            <div class="card-body row">
                <div class="col-5 text-center d-flex align-items-center justify-content-center">
                    <div class="">
                        <h2>DET<strong> Installer</strong></h2>
                        <p class="lead mb-5">Hoş Geldiniz. <br> 
                        </p>
                    </div>
                </div>
                <div class="col-7">
                    <form action="" method="post">
                    <br><br><br>        <br><br><br>
                    <h3>Veritabanı bilgileri</h3>
            
                        <p>Aşağıdaki formla hızlıca veritabanı bağlantınızı yaparak <br>gerekli olan tabloları oluşturabilirsiniz</p>
                        <div class="form-group">
                            <label for="dbhost">Veritabanı Sunucusu</label>
                            <input type="text" name="dbhost" class="form-control" placeholder="localhost" required>
                        </div>
                        <div class="form-group">
                            <label for="dbname">Veritabanı Adı</label>
                            <input type="text" name="dbname" class="form-control" placeholder="detDB" required>
                        </div>
                        <div class="form-group">
                            <label for="dbuser">Veritabanı Kullanıcı Adı</label>
                            <input type="text" name="dbuser" class="form-control" placeholder="root" required>
                        </div>
                        <div class="form-group">
                            <label for="dbpwd">Veritabanı Kullanıcı Şİfresi</label>
                            <input type="text" name="dbpwd" class="form-control" placeholder="****">
                        </div>





                        <h5>Kullanıcı Oluştur</h5>
                        <p>Aşağıdaki formla hızlıca kullanıcı oluşturarak kurulumu tamamlayabilirsiniz</p>
                        <div class="form-group">
                            <label for="userMail">E-Posta Adresi</label>
                            <input type="mail" name="userMail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="userPwd">Şifreniz</label>
                            <input type="password" name="userPwd" class="form-control" required>
                        </div>
                        <h5>SMTP Ayarları </h5>
                        <p>Aşağıdaki formla hızlıca SMTP kurulumunu tamamlayabilirsiniz</p>
                        <div class="form-group">
                            <label for="smtpMail">Smtp Mail Adresi</label>
                            <input type="mail" name="smtpMail" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="smtpHost">SMTP Host <small>smtp.gmail.com / smtp.yandex.com</small> </label>
                            <input type="text" name="smtpHost" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="smtpPwd">SMTP Şifresi <small>Host Gmail ise uygulama şifresi Giriniz.</small></label>
                            <input type="password" name="smtpPwd" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="toMail">Alıcı Mail <small>Mail kime gönderilecek ?</small> </label>
                            <input type="mail" name="toMail" class="form-control" required>
                        </div>
  














                        <div class="form-group">
                            <input type="submit" name="kaydet" class="btn btn-success" value="Kaydet">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
</body>

</html>