<?php

require_once 'classes.php';
if (isset($_POST['Login'])) {
    $login = new Login($_POST['userMail'],  md5($_POST['userPwd'])              );
    if ($login->authenticate()) {
        // Kullanıcı doğrulandı, yönlendirme yapılabilir veya başka bir işlem yapılabilir.        
      //  echo "giriş yapıldı";
        header('Location: /');
        exit;
    } else {
        // Kullanıcı doğrulanamadı, hata mesajı gösterilebilir veya başka bir işlem yapılabilir.
        echo "Olmadı bu";
    }
}
if (isset($_POST['domainName'])) {
    $DomainTable = new DomainTable();
    $DomainTable->addDomain($_POST['domainName']);
  }

  if (isset($_GET['del'])) {
try {
    $DomainTable = new DomainTable();
    $DomainTable->deleteDomain($_GET['dn']);
    header('Location: /');

} catch (\Throwable $th) {
    throw $th;
}
  }

?>