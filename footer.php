<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2023 <a href="https://onuraksoy.com.tr">#OA</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Page specific script -->
<script>

$(function() {
    var table = $('#DomainListTable').DataTable({
        language: {
            buttons: {
                "excel": "Excel",
                "colvis": "Kolon Ayarları",
            },
            search : "Ara",
            paginate: {
                first: "İlk",
                previous: "Önceki",
                next: "Sonraki",
                last: "Son"
            },
            info: "Sayfa: _PAGE_ / _PAGES_",
        },
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: [
            {
                text: 'Ekle',
                className: 'btn-success',
                action: function() {
                    $('#addDomainModal').modal('show');
                }
            },
            "excel", "pdf", "colvis"
        ]
    });
    table.buttons().container().appendTo('#DomainListTable_wrapper .col-md-6:eq(0)');
});

$(function() {
  var addDomainForm = $("#addDomainForm");
  var sweetAlertSuccess = {
    icon: 'success',
    title: 'Başarılı!',
    text: 'Alan adı kaydedildi.'
  };
  var sweetAlertError = {
    icon: 'error',
    title: 'Hata!',
    text: 'Alan adı kaydedilemedi.'
  };

  addDomainForm.submit(function(event) {
    event.preventDefault();
    var formData = addDomainForm.serialize();
    $.ajax({
      type: "POST",
      url: "/process/function.php",
      data: formData,
      success: function(response) {
        if (response === "true") {
          Swal.fire(sweetAlertSuccess).then(function() {
            addDomainForm.trigger("reset");
            $('#addDomainModal').modal('hide');
            location.reload();
          });
        } else {
          Swal.fire(sweetAlertError);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        Swal.fire(sweetAlertError);
      }
    });
  });

  var currentPagePath = window.location.pathname;
  var navLinks = $('.nav-link');
  navLinks.each(function() {
    if ($(this).attr('href') === currentPagePath) {
      $(this).addClass('active');
      return false;
    }
  });
});

</script>

<?php 
if (file_exists('install.php')) { ?>
  <script>alert("Uyarı: Install.php Dosyasını silmeniz gerekmektedir.");</script>
  
  
<?php }
?>

</body>
</html>
