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

$(document).ready(function() {
    var table = $('#DomainListTable').DataTable({
        language: {
            buttons: {
                "excel": "Excel",
                "colvis": "Kolon Ayarları",
            },
            search : "Ara",
            paginate: {
                first:      "İlk",
                previous:   "Önceki",
                next:       "Sonraki",
                last:       "Son"
            },
            info: "Sayfa : _PAGE_ / _PAGE_",
        },
        responsive: true, 
        lengthChange: false, 
        autoWidth: false,
        buttons: [ 
            {
                text: 'Ekle',
                className: 'btn-success',
                action: function () {
                    // Ekleme butonuna tıklandığında yapılacak işlemler burada olacak
                    $('#addDomainModal').modal('show'); // Modal'ı göster
                }
            },
            "excel", "pdf", "colvis"
        ]
    }).buttons().container().appendTo('#DomainListTable_wrapper .col-md-6:eq(0)');

    
});



  $(document).ready(function() {

  $("#addDomainForm").submit(function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Get form data
    var formData = $(this).serialize();

    // Post data to kaydet.php using AJAX
    $.ajax({
      type: "POST",
      url: "/process/function.php",
      data: formData,
      success: function(response) {
        // Check response and display appropriate message using SweetAlert
        if (response == "true") {
          Swal.fire({
            icon: 'success',
            title: 'Başarılı!',
            text: 'Alan adı kaydedildi.',
          }).then(function() {
            // Reset form inputs and close modal after SweetAlert is closed
            $('#addDomainForm').trigger("reset");
            $('#addDomainModal').modal('hide');
    // Reload page
    location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Alan adı kaydedilemedi.',
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Display error message using SweetAlert
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'Alan adı kaydedilemedi.',
        });
      }
    });
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
