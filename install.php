<?php 
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
if (isset($_POST["kaydet"])) {
    try {
        $userMail = $_POST['userMail'];
        $userPwd = md5($_POST['userPwd']);

        $sql = "INSERT INTO users (userMail, userPwd) VALUES (:userMail, :userPwd)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userMail', $userMail);
        $stmt->bindParam(':userPwd', $userPwd);
        $stmt->execute();

        // kullanıcı eklendikten sonra index.php'ye yönlendir
        header('Location: index.php');
        exit;
    }
    catch(PDOException $e) {
        echo "Bağlantı hatası: " . $e->getMessage();
    }

    $conn = null;
}
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
                        <p class="lead mb-5">Lütfen Kullanıcı Oluşturun.
                        </p>
                    </div>
                </div>
                <div class="col-7">
                    <form action="" method="post">
                        <h5>Hoşgeldiniz</h5>
                        <p>Aşağıdaki formla hızlıca kullanıcı oluşturarak kurulumu tamamlayabilirsiniz</p>
                        <div class="form-group">
                            <label for="userMail">E-Posta Adresi</label>
                            <input type="mail" name="userMail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="userPwd">Şifreniz</label>
                            <input type="password" name="userPwd" class="form-control">
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