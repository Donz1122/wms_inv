<?php

include_once '../session.php';

if(isset($_POST['del'])) {
  $file     = $_POST['filename'];
  $mrnumber = $_POST['mrnumber'];
  $isempty  = glob("../files/".$mrnumber."/*.*");
  if(file_exists($file)) {
    unlink($file);
    echo 1;
  } else {
    echo 0;
  }
  if(count($isempty) <= 1) {
    $db->query("update tbl_empreceipts set pdflocation = '' where mrnumber = '".$mrnumber."'");
  }
  $_SESSION['success'] = "Deleted!";
  die;
}

if(isset($_POST['mrnumber'])) {
  $mrnumber = $_POST['mrnumber'];
  $pdfs = glob("../files/".$_POST['mrnumber']."/*.*");
}

?>

<section class="content">
  <div class="card card-warning card-outline">
    <div class="card-header">
      <h3 class="card-title ml-2">Attachment <span class="badge badge-info"><?= count($pdfs) ?></span></h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="col-12 users-list row clearfix">
        <?php
        $i = 1;
        if(isset($_POST['mrnumber'])) {
          foreach($pdfs as $file) {
            $filename = "files/".$_POST['mrnumber']."/".basename($file); ?>
            <div class="col-sm-2">
              <div class="product-image-thumb mb-3">
                <a href="<?= $filename ?>" title="<?= basename($file)?>" target="_blank">
                  <?php $ext = pathinfo($filename, PATHINFO_EXTENSION);
                  if ($ext =='jpg' || $ext =='png'|| $ext =='bmp' || $ext =='jif') { ?>
                    <img src="<?= $filename ?>" class="img-size-200" alt="Product Image"/>
                  <?php } elseif($ext =='pdf') { ?>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
                  <?php } elseif($ext === 'doc' || $ext === 'docx') { ?>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>
                  <?php } elseif($ext =='xls*' || $ext === 'xlsx') { ?>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-excel"></i></span>
                  <?php } else { ?>
                    <img src="../dist/img/text.png" class="img-size-50" alt="User Image"/>
                  <?php } ?>
                </a>
              </div>
            </div>
          <?php }
        } ?>
      </div>
    </div>
    <div class="card-footer text-center">
      <!-- <a href="javascript::">View All Users</a> -->
    </div>
  </div>
</section>

<script type="text/javascript">
  function confirm_delete(e) {
    var del       = 'del';
    var filename  = $('#filename').val();
    alert(filename);
    var mrnumber  = $('#mrnumber').val();
    var ok        = confirm('Are you sure to remove this file?');
    if ( ok == true ) {
      $.ajax({
        url       : 'pdf/pdf_file_loader.php',
        type      : 'post',
        data      : { del:del, filename:filename, mrnumber:mrnumber },
        success   : function (data) {
          window.location="<?php echo $_SERVER['HTTP_REFERER'] ?>";
        },
        error     : function (data) { alert('2:'+data); },
      });
    }
  }
</script>