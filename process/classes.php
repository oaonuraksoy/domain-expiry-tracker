<?php
require_once('db.php');
//login sınıfı 
class Login {
    private $userMail;
    private $userPwd;
    private $pdo;

    public function __construct($userMail, $userPwd) {
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
          $sql = "SHOW TABLES LIKE 'users'";
          $stmt = $this->pdo->prepare($sql); // Burada değişiklik yapıldı
          $stmt->execute();
        
          if ($stmt->rowCount() == 0) {
              header("Location: install.php");
              exit();
          }
      } catch (PDOException $e) {
          // header("Location: ../install.php");
          echo $e->getMessage();
          exit();
      }
    }

    public function authenticate() {
        // Kullanıcının doğrulanması için veritabanına sorgu yapacağız.
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE userMail = :userMail AND userPwd = :userPwd");
        $stmt->bindParam(':userMail', $this->userMail);
        $stmt->bindParam(':userPwd', $this->userPwd);
        $stmt->execute();

        // Kullanıcı verileri eşleşirse, oturum açıyoruz.
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            session_start();
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
    private $db; // PDO veritabanı bağlantısı
    
    public function __construct() {
      global $dbhost, $dbname, $dbuser, $dbpwd;
      try {
        $this->db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpwd);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo "Hata: " . $e->getMessage();
      }
    }
    public function countDomains() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM domains");
            $count = $stmt->fetchColumn();
            return $count;
        } catch (PDOException $e) {
            echo 'Hata: ' . $e->getMessage();
        }
    }
    public function getDomainData() {
      try {
        // domains tablosundan verileri seçin
        $query = "SELECT * FROM domains";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
        
      } catch(PDOException $e) {
        echo "Hata: " . $e->getMessage();
      }
    }
    public function addDomain($domainName) {
        try {
            $data= $this->getWhoisData($domainName);
            $uzanti = $this->getDomainExtension($domainName);
            $sonuc = $this->getExpirationDate($data,$uzanti);
            $date = $sonuc;
            $domainExpiry = date('Y-m-d', strtotime($date));
            $domainDrop = date('Y-m-d', strtotime($domainExpiry . ' +65 days'));
            
            $query = "INSERT INTO domains (domainName, domainExpiry, domainDrop) VALUES (:domainName, :domainExpiry, :domainDrop)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':domainName', $domainName);
            $stmt->bindParam(':domainExpiry', $domainExpiry);
            $stmt->bindParam(':domainDrop', $domainDrop);
            $stmt->execute();
            echo "true";
        } catch(PDOException $e) {
            echo "Hata: " . $e->getMessage();
        }
    }
    public function deleteDomain($domainId) {
        try {
            $query = "DELETE FROM domains WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $domainId);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo "Hata: " . $e->getMessage();
            return false;
        }
    }
    

    // buraya

    public function getDomainExtension($domainName) {
        // alan adını noktalara göre ayırıyoruz
        // $parts = explode('.', $domainName);
    
        // // uzantıyı belirliyoruz
        // if (count($parts) > 1 && end($parts) == 'tr') {
        //   $extension = $parts[count($parts) - 2] . '.' . end($parts);
        // } else {
        //   $extension = end($parts);
        // }
    
        // return $extension;
        $parts = explode('.', $domainName);
        return end($parts);
    }
    
    public function getWhoisServer($extension){
    
        switch ($extension) {
            case 'com':
            case 'net':
              return 'whois.verisign-grs.com';
            case 'org':
              return 'whois.pir.org';
            case 'biz':
              return 'whois.biz';
            case 'info':
              return 'whois.afilias.net';
            case 'us':
              return 'whois.nic.us';
            case 'uk':
              return 'whois.nic.uk';
            case 'ca':
              return 'whois.cira.ca';
            case 'au':
              return 'whois.ausregistry.net.au';
            case 'de':
              return 'whois.denic.de';
            case 'fr':
              return 'whois.nic.fr';
            case 'it':
              return 'whois.nic.it';
            case 'nl':
              return 'whois.domain-registry.nl';
            case 'se':
              return 'whois.iis.se';
            case 'no':
              return 'whois.norid.no';
            case 'edu':
              return 'whois.educause.edu';
            case 'mil':
              return 'whois.nic.mil';
            case 'arpa':
              return 'whois.iana.org';
            case 'tr':
                return 'whois.nic.tr';
            default:
              return 'whois.iana.org';
        }
    }
    
 
public function getWhoisData($domainName) {
    // domain uzantısını belirle
    $domainExtension = $this->getDomainExtension($domainName);

    // uygun whois sunucusunu belirle
    switch ($domainExtension) {
        case 'com':
        case 'net':
            $whoisServer = 'whois.verisign-grs.com';
            break;
        case 'org':
            $whoisServer = 'whois.pir.org';
            break;
        case 'biz':
            $whoisServer = 'whois.biz';
            break;
        case 'tr':
            $whoisServer = 'whois.nic.tr';
            break;
        default:
            return 'Extension not supported';
    }

    // whois sunucusuna bağlan
    $fp = fsockopen($whoisServer, 43, $errno, $errstr, 10);
    if (!$fp) {
        return "Connection error: $errno - $errstr";
    }

    // whois sorgusunu yap
    fwrite($fp, "$domainName\r\n");

    // cevabı al
    $result = '';
    while (!feof($fp)) {
        $result .= fgets($fp, 128);
    }

    // bağlantıyı kapat
    fclose($fp);

    return $result;
}




public function getExpirationDate($whoisData, $extension) {
    switch ($extension) {
        case 'com':
        case 'net':
            if (preg_match('/Registry Expiry Date: (.*)\n/', $whoisData, $matches)) {
                return $matches[1];
            } else {
                return 'Expiration date not found';
            }
        case 'org':
            if (preg_match('/Registry Expiry Date: (.*)\n/', $whoisData, $matches)) {
                return $matches[1];
            } else {
                return 'Expiration date not found';
            }
        case 'biz':
            if (preg_match('/Domain Expiration Date: (.*)\n/', $whoisData, $matches)) {
                return $matches[1];
            } else {
                return 'Expiration date not found';
            }
        case 'tr':
            if (preg_match('/Expires on\.+:(.*)\n/', $whoisData, $matches)) {
                return $matches[1];
            } else {
                return 'Expiration date not found';
            }
        default:
            return 'Extension not supported';
    }
}





  }





?>