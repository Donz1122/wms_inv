<!-- mao2x lng...  -->


<?php
include '../session.php';
require '../class/clsitems.php';
?>
<?php if(isset($_POST['issuance']))  { $issuance = $_POST['issuance'];  ?>
<div class="form-group row">
  <label class="col-sm-3 col-form-label">Returned By</label>
  <div class="col">
    <input type="hidden" class="hidden" id="empno_returnedby" name="empno_returnedby">
    <select class="form-control form-control-sm select2" id="aReturnedBy" name="aReturnedBy" required>
      <?php foreach($empname as $row): ?>
        <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<?php } ?>

<?php if(isset($_POST['iditemindex'])) {
  $iditemindex = $_POST['iditemindex']; ?>
  <label class="col-sm-3 col-form-label">Brand Name</label>
  <div class="col">
    <select class="form-control form-control-sm select2" id="abrandname" name="abrandname">
      <?php
      $rows = $db->query("select distinct brandname from tbl_receiptsdetails where iditemindex = '$iditemindex'");
      foreach($rows as $row): ?>
        <option value="<?= $row['brandname']; ?>"><?= $row['brandname']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
<?php } ?>

<?php if(isset($_POST['idreceipts'])) {
  $id = $_POST['idreceipts'];
  $itemname = $item->get_rrdetails($id);
  ?>
  <label class="col-form-label col-sm-3">Item Name</label>
  <div class="col-sm-9">
    <select onchange="select_item()" class="form-control select2" id="iditemindex" name="iditemindex" >
      <?php foreach($itemname as $row): ?>
        <option value="<?= $row['idreceiptsdetails']; ?>"><?= $row['itemname'].' - '.$row['itemcode']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
<?php } ?>


<?php if(isset($_POST['serialnos'])) { ?>
  <label class="col-sm-3 col-form-label">Serial No's</label>
  <div class="col">
    <select class="form-control form-control-sm select2" id="sserialnos" name="sserialnos" >
      <?php
      $serials  = $_POST['serialnos'];
      $pieces   = explode(",", $serials);
      foreach ($pieces as $row): ?>
        <option value="<?= $row; ?>"><?= $row ?></option>
      <?php endforeach; ?>
    </select>
  </div>
<?php } ?>


<?php if(isset($_POST['mttnos'])) {
  $strfilter = $_POST['mttnos'];
  $str = "select a.* from tbl_issuance a
  left outer join tbl_user c on c.iduser = a.iduser
  where a.active = 0 and a.inumber like 'mtt%' and area like '".$_SESSION['area']."'
  order by idate desc ";
  if($strfilter == 'zero') $str = "select a.* from tbl_issuance a
  left outer join tbl_user c on c.iduser = a.iduser
  where 1 = 0;";
  $query    = $db->query($str); ?>
  <label class="col-sm-2 form-control-sm">MTT/Doc. No.</label>
  <div class="col">
    <select class="form-control form-control-sm select2" id="adocno" name="adocno" >
      <?php
      foreach ($query as $row): ?>
        <option value="<?= $row['inumber']; ?>"><?= $row['idate'].' - '.$row['inumber'].' - '.$row['purpose'] ?></option>
      <?php endforeach; ?>
    </select>
  </div>
<?php } ?>

<?php if(isset($_POST['allpurposeitem'])) {
  // $query    = $db->query("select distinct particulars from tbl_receiptsdetails order by particulars asc"); ?>
  <div class="form-group row">
    <label class="col-sm-3 form-control-sm" style="text-align: right;">All purpose item name</label>
    <div class="col">
      <input type="text" class="form-control form-control-sm" id="aitemname" name="aitemname" placeholder="particulars">
    </div>
  </div>
<?php } ?>

<?php if(isset($_POST['from'])) { ?>
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Returned By</label>
    <div class="col">
      <input type="hidden" class="hidden" id="empno_returnedby" name="empno_returnedby">
      <select class="form-control form-control-sm select2" id="aReturnedBy" name="aReturnedBy" required>
        <?php if(intval($_POST['from']) == 0) {
          $query = $db->query("select empnumber, concat(lastname,', ',firstname,' ',middleinitial) empname, title
            from zanecopayroll.employee
            where concat(lastname,', ',firstname,' ',middleinitial) is not null order by empname");
          foreach($query as $row):
            if($row['empnumber'] == $_POST['idfrom']) { ?>
              <option selected="selected" value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
            <?php } else { ?>
              <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
            <?php }
          endforeach; ?>
        <?php } else {
          $query = $db->query("select distinct a.suppliercode, trim(suppliername) supplier
            from tbl_returntosupplier a
            left join tbl_supplier s on s.suppliercode = a.suppliercode
            order by trim(suppliername) asc");
          foreach($query as $row):
            if($row['suppliercode'] == $_POST['idfrom']) { ?>
              <option selected="selected" value="<?= $row['suppliercode']; ?>"><?= $row['supplier']; ?></option>
            <?php } else { ?>
              <option value="<?= $row['suppliercode']; ?>"><?= $row['supplier']; ?></option>
            <?php }
          endforeach; ?>
        <?php } ?>
      </select>
    </div>
  </div>
<?php } ?>

<?php if(isset($_POST['showalert'])) {
  echo "<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000});
    </script>";

    echo "<script>
    Toast.fire({
      icon: 'success',
      title: 'Naay sulod...'
      });
      </script>";
    } ?>




    <script type="text/javascript">
      $('.select2').select2();
      $('#aitemname').editableSelect();
  //$('#abrandname').editableSelect({ effects: 'fade' });
</script>