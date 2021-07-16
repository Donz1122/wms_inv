<?php   
if (!file_exists('../class/clsitems.php')) {
  require_once 'class/clsitems.php';
}
else {
  require_once '../class/clsitems.php';
}


$id         = $db->query("select if(count(idempreceipts) is null,1,count(idempreceipts))+1 as cnt
  from tbl_empreceipts
  where year(datereceived) = year(now())
  limit 1;")->fetch_assoc();
$num1       = $id['cnt'];

//oldies loop but goodies to search id if exists
back:
$mrno       = 'MR'.$_SESSION["area"].$mos.$year.'D'.$num1;
$id         = $db->query("select mrnumber from tbl_empreceipts where mrnumber = '$mrno' limit 1"); 
if(mysqli_num_rows($id)>0) {
  $num1 += 1;
  goto back;
} 

$str        = 'select deptcode, deptname from tbl_department order by deptname asc ';
$rows       = $db->query($str.'limit 1')->fetch_assoc();
$department = $db->query($str);

?>

<!-- Add -->
<div class="modal fade" id="modal_add_mr_department">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Department Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_department_helper.php">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date Received</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="date" class="form-control form-control-sm" id="mrdate" name="mrdate" data-mask value="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">MR Number</label>
            <div class="col">
              <input title="MR - [Area] - [Month-Day-Year] - [Count]" type="text" class="form-control form-control-sm" id="mrnumber" name="mrnumber" value="<?= $mrno ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Dept. Name</label>
            <div class="col">              
              <input type="hidden" class="form-control form-control-sm" id="deptcode" name="deptcode" value="<?= $rows['deptcode']?>">
              <select onchange="select_department('a')" class="form-control form-control-sm select2" id="deptname" name="deptname" >
                <?php foreach($department as $row): ?>
                  <option value="<?= $row['deptcode']; ?>"><?= utf8_decode($row['deptname']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Remarks</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="remarks" name="remarks" style="resize: none;"></textarea>
            </div>
          </div>            
        </div> 
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-info" name="add"><i class="fa fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modal_edit_mr_department">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modify Department Receipt</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_department_helper.php">

        <input type="hidden" class="form-control form-control-sm" id="eidempreceipts" name="eidempreceipts">

        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date Received</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="date" class="form-control form-control-sm" id="emrdate" name="emrdate" data-mask>
              </div>
            </div>

          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">MR Number</label>
            <div class="col">
              <input title="MR - [Area] - [Month-Day-Year] - [Count]" type="text" class="form-control form-control-sm" id="emrnumber" name="emrnumber" value="<?= $mrno ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Dept. Name</label>
            <div class="col">
              <input type="hidden" class="form-control form-control-sm" id="edeptcode" name="edeptcode">
              <input type="hidden" class="form-control form-control-sm" id="edeptname2" name="edeptname2">
              <select onchange="select_department('e')" class="form-control form-control-sm select2" id="edeptname" name="edeptname" >
                <?php foreach($department as $row): ?>
                  <option value="<?= $row['deptcode']; ?>"><?= utf8_decode($row['deptname']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Remarks</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="eremarks" name="eremarks" style="resize: none;"></textarea>
            </div>
          </div>            
        </div> 
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-warning" name="edit"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="modal_delete_mr_department">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">Are you sure to delete this item?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
        <form class="form-horizontal" method="POST" action="datahelpers/mr_department_helper.php">
          <input type="hidden" id="dempreceipts" name="dempreceipts">
          <p id="dmrnumber"></p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();
  
  function select_department(e) {
    var id = '';
    if(e == 'a') {
      id = $('#deptname').val();
      $('#deptcode').val(id);
    } else {
      id = $('#edeptname').val();
      $('#edeptcode').val(id);
      $('#edeptname2').val($("#edeptname option:selected").html());
      //$selectedname = $("#e_empname option:selected").html();      
    }
  }

</script>