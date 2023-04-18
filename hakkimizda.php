<?php
require_once('header.php');
?> 


<div class="content-wrapper"> 
 <h1>Merhaba Dünya!</h1>
 <?php $date_str = '18.04.2023';
echo    $date = date('d-m-Y', strtotime(str_replace('.', '-', $date_str))); ?>
</div>


<?php
require_once('footer.php');
?> 