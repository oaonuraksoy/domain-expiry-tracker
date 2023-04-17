<?php
require_once('header.php');
?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Alan adı Listesi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Anasayfa</a></li>
              <li class="breadcrumb-item active">Alan adı Listesi</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Takipteki alan adlarınız</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="DomainListTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="width: 35px !important;">Sıra</th>
                    <th>Alan Adı</th>
                    <th>Son Kullanma Tarihi</th>
                    <th>Düşme Tarihi</th>
                    <th style="width: 35px !important;">Eylem</th>
                  </tr>
                  </thead>
                  <tbody>



               <?php $s=1;  foreach ($data as $row) { ?>

 

                  <tr>
                    <td ><?php echo $s; ?></td>
                    <td><?php echo $row['domainName']; ?>
                    </td>
                    <td><?php echo $row['domainExpiry']; ?></td>
                    <td> <?php echo $row['domainDrop']; ?></td>
                    <td><a  class="btn btn-danger"  style="font-size:8pt;" href="process/function.php?del&dn=<?php echo $row['id']; ?>"> <i class="fas fa-trash"></i></a></td>
                  </tr>
                
<?php $s++; } ?>











                  </tbody>

                </table>
 </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          





<!-- burdan -->

<br>
<br>
<?php 
// $domain = "onuraksoy.com.tr";

// echo $data= $domainTable->getWhoisData($domain);

// echo "<br><br>".$uzanti = $domainTable->getDomainExtension($domain);
// echo "<br><br>".$sonuc = $domainTable->getExpirationDate($data,$uzanti);
// $date = $sonuc;
// $formatted_date = date('d.m.Y', strtotime($date));
// echo "<br><br>".$formatted_date;
// $new_date = date('d.m.Y', strtotime($formatted_date . ' +62 days'));
// echo "<br><br>".$new_date;
// echo "<br><br>";
// $now = time(); // Şu anki zamanı al
// $new_date_timestamp = strtotime($formatted_date) + (62 * 24 * 60 * 60); // Yeni tarihi hesapla
// $new_date = date('d.m.Y', $new_date_timestamp); // Yeni tarihi formatla

// // Kalan günü hesapla ve duruma göre mesaj yazdır
// $days_left = ceil(($new_date_timestamp - $now) / (24 * 60 * 60)); 
// if ($days_left == 1) {
//   echo "1 gün kaldı";
// } else {
//   echo "$days_left gün kaldı";
// }
// ?>
<!-- Buraya -->















          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->





  <!-- test -->
<!-- Modal -->
<div class="modal fade" id="addDomainModal" tabindex="-1" role="dialog" aria-labelledby="addDomainModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDomainModalLabel">Alan Adı Ekle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addDomainForm">
          <div class="form-group">
            <label for="domainName">Alan Adı</label>
            <input type="text" class="form-control" id="domainName" name="domainName" required>
          </div>
          <div class="form-group">
        <input type="submit" class="btn btn-success" id="addDomainButton"></input>
      </div>
        </form>
      </div>
      
    </div>
  </div>
</div>
  <?php
require_once('footer.php');
?> 