<?php
require_once('db.php');
//login sınıfı 
class Login {
    private $userMail;
    private $userPwd;
    private $pdo;

    public function __construct($userMail, $userPwd = null) {
      global $dbhost, $dbname, $dbuser, $dbpwd;
        $this->userMail = $userMail;
        $this->userPwd = $userPwd;

        // Veritabanı bağlantısı için PDO nesnesi oluşturuyoruz.
        $dsn = 'mysql:host='.$dbhost.';dbname='.$dbname.'';
        $username = $dbuser;
        $password = $dbpwd;

        try {
          $this->pdo = new PDO($dsn, $username, $password);
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);          
      } catch (PDOException $e) {
          // header("Location: ../install.php");
          echo $e->getMessage();
          exit();
      }
    }

    public function authenticate() {
        // Kullanıcının e-posta adresine göre verileri çekiyoruz.
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE userMail = :userMail");
        $stmt->bindParam(':userMail', $this->userMail);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Kullanıcı bulundu mu ve şifre doğrulanıyor mu?
        if ($user && password_verify($this->userPwd, $user['userPwd'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['userMail'] = $user['userMail'];
            return true;
        } else {
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}
//login sınıfı sonu

class DomainTable {
    private ?PDO $db;

    public function __construct() {
        global $dbhost, $dbname, $dbuser, $dbpwd;
        try {
            $this->db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpwd);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->db = null;
            error_log("Connection Error: " . $e->getMessage());
        }
    }

    public function countDomains(): int {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM domains");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getDomainData(): array {
        try {
            $query = "SELECT * FROM domains ORDER BY domainExpiry ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function addDomain(string $domainName): bool {
        try {
            $domainName = strtolower(trim($domainName));
            $whoisData = $this->getWhoisData($domainName);
            $extension = $this->getDomainExtension($domainName);
            $expiryDateStr = $this->getExpirationDate($whoisData, $extension);

            if ($expiryDateStr === 'Expiration date not found' || $expiryDateStr === 'Extension not supported') {
                return false;
            }

            $domainExpiry = date('Y-m-d', strtotime($expiryDateStr));
            $domainDrop = date('Y-m-d', strtotime($domainExpiry . ' +65 days'));

            $query = "INSERT INTO domains (domainName, domainExpiry, domainDrop) VALUES (:domainName, :domainExpiry, :domainDrop)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':domainName', $domainName);
            $stmt->bindParam(':domainExpiry', $domainExpiry);
            $stmt->bindParam(':domainDrop', $domainDrop);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDomain(int $domainId): bool {
        try {
            $query = "DELETE FROM domains WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $domainId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getDomainExtension(string $domainName): string {
        $parts = explode('.', $domainName);
        return strtolower(end($parts));
    }

    public function getWhoisServer(string $extension): string {
        $servers = [
            'com' => 'whois.verisign-grs.com',
            'net' => 'whois.verisign-grs.com',
            'org' => 'whois.pir.org',
            'info' => 'whois.afilias.net',
            'biz' => 'whois.nic.biz',
            'us' => 'whois.nic.us',
            'uk' => 'whois.nic.uk',
            'ca' => 'whois.cira.ca',
            'tr' => 'whois.nic.tr',
            'de' => 'whois.denic.de',
            'fr' => 'whois.nic.fr',
            'it' => 'whois.nic.it',
            'nl' => 'whois.domain-registry.nl',
            'io' => 'whois.nic.io',
            'me' => 'whois.nic.me',
            'co' => 'whois.nic.co',
            'tv' => 'whois.nic.tv',
        ];
        return $servers[$extension] ?? 'whois.iana.org';
    }

    public function getWhoisData(string $domainName): string {
        $extension = $this->getDomainExtension($domainName);
        $whoisServer = $this->getWhoisServer($extension);

        if ($whoisServer === 'whois.iana.org') {
            $ianaData = $this->queryWhois('whois.iana.org', $domainName);
            if (preg_match('/refer: (.*)\n/', $ianaData, $matches)) {
                $whoisServer = trim($matches[1]);
            }
        }

        return $this->queryWhois($whoisServer, $domainName);
    }

    private function queryWhois(string $server, string $domain): string {
        $fp = @fsockopen($server, 43, $errno, $errstr, 5);
        if (!$fp) return "";
        
        if ($server === 'whois.denic.de') {
            fwrite($fp, "-T dn,ace $domain\r\n");
        } else {
            fwrite($fp, "$domain\r\n");
        }
        
        $result = '';
        while (!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        fclose($fp);
        return $result;
    }

    public function getExpirationDate(string $whoisData, string $extension): string {
        $patterns = [
            '/Registry Expiry Date: (.*)/i',
            '/Expiry Date: (.*)/i',
            '/Expiration Date: (.*)/i',
            '/Expires on\.+:(.*)/i',
            '/Record expires on (.*)/i',
            '/free-date: (.*)/i',
            '/Expiration Time: (.*)/i',
            '/renewal date: (.*)/i',
            '/Valid-until: (.*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $whoisData, $matches)) {
                return trim($matches[1]);
            }
        }

        return 'Expiration date not found';
    }





  }





?>