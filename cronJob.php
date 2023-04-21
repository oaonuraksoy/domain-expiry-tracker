<?php
require_once('process/db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';

$charset = 'utf8';
// Veritabanı bağlantısını oluştur

$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $dbuser, $dbpwd, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// Tarihi çek ve kontrol et
$sql = "SELECT domainName, domainDrop FROM domains WHERE domainDrop = DATE(NOW() + INTERVAL 1 DAY)";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    $mail = new PHPMailer(true);
    //SMTP settings
    $mail->SMTPDebug = 0; // Enable verbose debug output
    $mail->isSMTP(); // Send using SMTP
    //$mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = $smtpMail; // SMTP username
    $mail->Password = $smtpPwd; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($smtpMail, 'DET Mailler');
    $mail->addAddress($toMail); // Add a recipient

    // Tarihi yarın olan herhangi bir domain varsa, e-posta gönder
    $mail->isHTML(true);  
    $mail->Subject = 'DET Domain Habercisi';
    $mail->Body = "Sayın DET Kullanıcısı,<br>\n\n" .
        "Aşağıdaki domaininizin yarın düşecek. Lütfen geri almak için gerekli önlemleri alın.<br>\n\n" .
        "Domain: ";

    $domains = array();
    while ($row = $stmt->fetch()) {
        $domainName = $row['domainName'];
        array_push($domains, $domainName);
    }

    $mail->Body .= implode(", ", $domains);
    $mail->Body .= "\n\n<br>Saygılarımızla,\n\nDET";

    if($mail->send()) {
    //   echo 'Message has been sent';
     } else {
        $error_message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        error_log($error_message, 3, "smtpErr.log");

     }
}


?>