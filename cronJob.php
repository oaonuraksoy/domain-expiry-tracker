<?php
require_once('process/db.php');
// Veritabanı bilgileri
// Veritabanı bilgilerini burada ayarlayın
$host = 'localhost';
$db   = 'detDB_news';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Veritabanı bağlantısını oluştur
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Tarihi çek
$sql = "SELECT domainName, domainDrop FROM domains WHERE domainDrop = DATE(NOW() + INTERVAL 1 DAY)";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    echo "geldi";
}
?>