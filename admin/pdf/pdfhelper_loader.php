
<?php
include '../session.php';


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
  $images = glob("../files/".$_POST['mrnumber']."/*.*");
  foreach($images as $file) {
    $filename = "files/".$_POST['mrnumber']."/".basename($file); ?>

    <li>
      <div class="info-box">

        <a class="users-list-name" href="<?= $filename ?>"><img src="../dist/img/user1-128x128.jpg"></a>
        <span class="users-list-name badge mt-2"><?= basename($file) ?></span>
        <span class="badge mt-2"><?= formatBytes(filesize($file)) ?></span>
        <div class="btn-group btn-group-sm">
          <a href="<?= $filename ?>" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-cloud-download-alt" style="color: blue;"></i></a>
          <button class="btn btn-default btn-sm" onclick="confirm_delete()" data-toogle="modal"><i class="fas fa-trash-alt" style="color: red;"></i></button>
        </div>
      </div>
    </li>
  <?php } 
}
?>

<script type="text/javascript">
  function confirm_delete(e) {
    var del       = 'del';
    var filename  = $('#filename').val();
    var mrnumber  = $('#mrnumber').val();
    var ok        = confirm('Are you sure to remove this file?');
    if ( ok == true ) {
      $.ajax({
        url       : 'datahelpers/pdfhelper_loader.php',
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


  <!-- <div class="product-img">
          <img src="../dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
        </div>
        <div class="product-info">
          <a href="javascript:void(0)" class="product-title">'.basename($file).'
            <span class="badge badge-warning float-right">'.formatBytes(filesize($file)).'</span>
          </a>
          <span class="product-description">
            <input type="hidden" id="filename" name="filename" value="'.realpath($file).'"/>
            <input type="hidden" name="mrnumber" value="'.$_POST['mrnumber'].'" />
            <div class="card-tool btn-group">
              <a href="'.$filename.'" target="_blank" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt" style="color: blue;"></i></a>
              <button class="btn btn-default btn-sm float-right" onclick="confirm_delete()" data-toogle="modal"><i class="fas fa-trash-alt" style="color: red;"></i></button>
            </div>
          </span>
        </div>
      -->

