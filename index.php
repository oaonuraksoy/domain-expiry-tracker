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
                 <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDomainModal">
                    <i class="fas fa-plus"></i> Yeni Ekle
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="DomainListTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="width: 35px !important;">Sıra</th>
                    <th>Alan Adı</th>
                    <th>Bitiş Tarihi</th>
                    <th>Düşme Tarihi</th>
                    <th>Kalan Gün</th>
                    <th style="width: 35px !important;">Eylem</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $s=1; 
                  $today = new DateTime();
                  foreach ($data as $row) { 
                    $expiry = new DateTime($row['domainExpiry']);
                    $interval = $today->diff($expiry);
                    $daysLeft = (int)$interval->format('%r%a');
                    $badgeClass = ($daysLeft < 30) ? 'badge-danger' : (($daysLeft < 60) ? 'badge-warning' : 'badge-success');
                  ?>
                    <tr>
                      <td><?php echo $s; ?></td>
                      <td><b><?php echo htmlspecialchars($row['domainName']); ?></b></td>
                      <td><?php echo date('d.m.Y', strtotime($row['domainExpiry'])); ?></td>
                      <td><?php echo date('d.m.Y', strtotime($row['domainDrop'])); ?></td>
                      <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $daysLeft; ?> gün</span></td>
                      <td>
                        <a class="btn btn-danger btn-sm" href="process/function.php?del&dn=<?php echo $row['id']; ?>" onclick="return confirm('Emin misiniz?')"> 
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php $s++; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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
        <form action="process/function.php" method="POST">
          <div class="form-group">
            <label for="domainName">Alan Adı</label>
            <input type="text" class="form-control" id="domainName" name="domainName" placeholder="orn: google.com" required>
          </div>
          <div class="form-group text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
            <button type="submit" class="btn btn-success">Ekle</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
require_once('footer.php');
?> 