<?php
require_once('process/db.php');
$charset = 'utf8';

// Veritabanı bağlantısını oluştur
// Veritabanı bağlantısını oluştur
$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $dbuser, $dbpwd, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Tarihi çek ve kontrol et
$sql = "SELECT domainName, domainDrop FROM domains WHERE domainDrop = DATE(NOW() + INTERVAL 1 DAY)";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    // Tarihi yarın olan herhangi bir domain varsa, e-posta gönder
    $from = "smm@onuraksoy.com.tr";
    $to = $adminMail;
    $subject = "Domain süresi dolmak üzere";
    $body = "Sayın domain sahibi,\n\n" .
            "Aşağıdaki domaininizin yarın düşecek. Lütfen geri almak için gerekli önlemleri alın.\n\n" .
            "Domain: ";
    
    $domains = array();
    while ($row = $stmt->fetch()) {
        $domainName = $row['domainName'];
        array_push($domains, $domainName);
    }
    
    $body .= implode(", ", $domains);
    $body .= "\n\nSaygılarımızla,\n\nDET";
    
    mail($to, $subject, $body, "From: " . $from);
}


?>