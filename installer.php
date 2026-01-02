<?php
/**
 * DET - Installer
 * Powered by Hackonomist
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$repoUrl = "https://github.com/oaonuraksoy/domain-expiry-tracker/releases/latest/download/det.zip";
$isZipInstalled = class_exists('ZipArchive');

if (isset($_POST["kaydet"])) {
    $dbhost = $_POST['dbhost'] ?? 'localhost';
    $dbname = $_POST['dbname'] ?? '';
    $dbuser = $_POST['dbuser'] ?? '';
    $dbpwd = $_POST['dbpwd'] ?? '';
    $smtpMail = $_POST['smtpMail'] ?? '';
    $smtpHost = $_POST['smtpHost'] ?? '';
    $smtpPwd = $_POST['smtpPwd'] ?? '';
    $toMail = $_POST['toMail'] ?? '';
    $userMail = $_POST['userMail'] ?? '';
    $userPassword = $_POST['userPwd'] ?? '';

    // 1. Download and Extract Files if missing
    if (!file_exists('index.php')) {
        $zipFile = 'det.zip';
        $download = @file_get_contents($repoUrl);
        if ($download === false) {
            die("Hata: Dosyalar GitHub üzerinden indirilemedi. Lütfen internet bağlantınızı kontrol edin veya det.zip dosyasını manuel olarak ana dizine yükleyin.");
        }
        file_put_contents($zipFile, $download);

        if ($isZipInstalled) {
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo(__DIR__);
                $zip->close();
                unlink($zipFile);
            } else {
                die("Hata: Zip dosyası açılamadı.");
            }
        } else {
            die("Hata: Sunucunuzda ZipArchive sınıfı yüklü değil. Lütfen zip dosyasını manuel açın.");
        }
    }

    // 2. Database Connection Check & Create Tables
    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create Tables
        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userMail VARCHAR(50) NOT NULL,
            userPwd VARCHAR(255) NOT NULL
        )");

        $conn->exec("CREATE TABLE IF NOT EXISTS domains (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            domainName VARCHAR(100) NOT NULL,
            domainExpiry DATE NOT NULL,
            domainDrop DATE NOT NULL
        )");

        // Add User
        $hashedPwd = password_hash($userPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (userMail, userPwd) VALUES (:mail, :pwd)");
        $stmt->execute(['mail' => $userMail, 'pwd' => $hashedPwd]);

        // 3. Create db.php
        $content = "<?php\n";
        $content .= "global \$dbhost, \$dbname, \$dbuser, \$dbpwd, \$smtpMail, \$smtpHost, \$smtpPwd, \$toMail;\n";
        $content .= "\$dbhost = '$dbhost';\n";
        $content .= "\$dbname = '$dbname';\n";
        $content .= "\$dbuser = '$dbuser';\n";
        $content .= "\$dbpwd = '$dbpwd';\n";
        $content .= "\$smtpMail = '$smtpMail';\n";
        $content .= "\$smtpHost = '$smtpHost';\n";
        $content .= "\$smtpPwd = '$smtpPwd';\n";
        $content .= "\$toMail = '$toMail';\n";
        $content .= "?>";
        
        if (!is_dir('process')) mkdir('process', 0777, true);
        file_put_contents('process/db.php', $content);

        // 4. Self-Rename
        @rename('installer.php', 'installer.php.old');

        header('Location: login.html');
        exit;

    } catch (PDOException $e) {
        $error = "Veritabanı Hatası: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DET | Kurulum Sihirbazı</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { background: #f4f6f9; }
        .setup-container { max-width: 900px; margin: 40px auto; }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: none; }
        .card-header { background: #343a40; color: #fff; border-radius: 15px 15px 0 0 !important; padding: 25px; }
        .card-title { font-size: 1.5rem; font-weight: 600; }
        .btn-success { background-color: #28a745; border-color: #28a745; padding: 10px 30px; border-radius: 8px; font-weight: 600; }
        .form-section { padding: 20px; background: #fff; border-radius: 10px; margin-bottom: 20px; border: 1px solid #e9ecef; }
        .form-section h5 { color: #007bff; border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 5px; margin-bottom: 20px; }
        .form-group label { font-weight: 500; color: #495057; }
        .instruction-box { background: #e7f3ff; border-left: 5px solid #007bff; padding: 15px; margin-bottom: 25px; border-radius: 0 8px 8px 0; }
    </style>
</head>
<body>

<div class="setup-container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title"><i class="fas fa-magic mr-2"></i> DET Kurulum Sihirbazı</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="instruction-box">
                <p class="mb-0"><strong>Hoş Geldiniz!</strong> DET (Domain Expiry Tracker) kurulumuna başlamak üzeresiniz. Bu form gerekli veritabanı tablolarını oluşturacak, yönetici hesabınızı kuracak ve SMTP ayarlarınızı yapılandıracaktır. Dosyalar eksikse otomatik olarak indirilecektir.</p>
            </div>

            <form action="" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section shadow-sm">
                            <h5><i class="fas fa-database mr-2"></i> Veritabanı Bilgileri</h5>
                            <div class="form-group">
                                <label>Sunucu</label>
                                <input type="text" name="dbhost" class="form-control" value="localhost" required>
                            </div>
                            <div class="form-group">
                                <label>Veritabanı Adı</label>
                                <input type="text" name="dbname" class="form-control" placeholder="det_db" required>
                            </div>
                            <div class="form-group">
                                <label>Kullanıcı Adı</label>
                                <input type="text" name="dbuser" class="form-control" placeholder="root" required>
                            </div>
                            <div class="form-group">
                                <label>Şifre</label>
                                <input type="password" name="dbpwd" class="form-control" placeholder="****">
                            </div>
                        </div>

                        <div class="form-section shadow-sm">
                            <h5><i class="fas fa-user-shield mr-2"></i> Yönetici Hesabı</h5>
                            <div class="form-group">
                                <label>E-Posta Adresi</label>
                                <input type="email" name="userMail" class="form-control" placeholder="admin@example.com" required>
                            </div>
                            <div class="form-group">
                                <label>Şifre</label>
                                <input type="password" name="userPwd" class="form-control" placeholder="Giriş şifreniz" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-section shadow-sm">
                            <h5><i class="fas fa-paper-plane mr-2"></i> SMTP Ayarları</h5>
                            <div class="form-group">
                                <label>SMTP Sunucusu</label>
                                <input type="text" name="smtpHost" class="form-control" placeholder="smtp.gmail.com" required>
                                <small class="text-muted">Gmail, Yandex vb. sunucu adresi.</small>
                            </div>
                            <div class="form-group">
                                <label>SMTP E-Posta</label>
                                <input type="email" name="smtpMail" class="form-control" placeholder="bildirim@domain.com" required>
                            </div>
                            <div class="form-group">
                                <label>SMTP Şifresi</label>
                                <input type="password" name="smtpPwd" class="form-control" placeholder="****" required>
                                <small class="text-muted">Gmail için uygulama şifresi kullanın.</small>
                            </div>
                            <div class="form-section mt-3 p-2 bg-light border-0">
                                <div class="form-group mb-0">
                                    <label>Bildirim Gidecek Adres</label>
                                    <input type="email" name="toMail" class="form-control" placeholder="sahibi@domain.com" required>
                                    <small class="text-muted">Uyarılar bu adrese gönderilir.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <button type="submit" name="kaydet" class="btn btn-success btn-lg shadow">
                        <i class="fas fa-check-circle mr-2"></i> Kurulumu Tamamla
                    </button>
                    <p class="mt-3 text-muted">
                        <small><i class="fas fa-info-circle"></i> Dosyalar eksikse yaklaşık 5-10 MB indirme yapılacaktır.</small>
                    </p>
                </div>
            </form>
        </div>
        <div class="card-footer text-center bg-transparent border-0 pb-4">
            <span class="text-muted small">DET v1.2 | Powered by Hackonomist</span>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>