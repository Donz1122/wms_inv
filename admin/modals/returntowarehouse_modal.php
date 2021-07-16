<?php
require "class/clsreturns.php";
$empname  = $returns->employee();
//yearly reset to 1
$id       = $db->query("select count(idreturns)+1 as cnt from tbl_returns where year(returneddate) = year(now()) limit 1;")->fetch_assoc();;
$num1     = $id['cnt'];

//oldies loop but goodies to search id if exists
back:
$rno      = 'MTw'.$_SESSION["area"].$mos.$year.'-'.$num1;
$id = $db->query("select returnedno from tbl_returns where returnedno like '$rno' limit 1;")->fetch_assoc();
if($id>0) {
  $num1 += 1;
  goto back;
}
?>
<!-- Add -->
<div class="modal fade" id="modal-addreturn">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Returns</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/returnedmaterials_helper.php">
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
                <input type="date" class="form-control form-control-sm" id="aReturnedDate" name="aReturnedDate" data-mask value="<?= $enddate; ?>">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Particulars</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="aParticulars" name="aParticulars" rows="3" style="resize: none;" required></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Control No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="aMCrTNo" name="aMCrTNo" value="<?= $rno; ?>" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Returned From</label>
            <div class="col">
              <select class="form-control form-control-sm select2" id="returnfrom" name="returnfrom" onchange="change_from()" required>
                <option value="0">Employee</option>
                <option value="1">Supplier</option>
              </select>
            </div>
          </div>  

          <div id="from"></div>
            <!-- <label class="col-sm-3 col-form-label">Returned By</label>
            <div class="col">
              <input type="hidden" class="hidden" id="empno_returnedby" name="empno_returnedby">
              <select class="form-control form-control-sm select2" id="aReturnedBy" name="aReturnedBy" required>
                <?php foreach($empname as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group row">
            <label class="col-sm-3 col-form-label" id="label-reference">Reference MCT</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="aRefNo" name="aRefNo" required>
            </div>
          </div> -->
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>          
          <button type="submit" class="btn btn-info" name="add"> Save and Close</button>
          <button type="submit" class="btn btn-info" name="add_open"> Save and Open Details</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal fade" id="modal-editreturn">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Returns</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/returnedmaterials_helper.php">
        <div class="modal-body">
          <input type="hidden" class="form-control form-control-sm" id="eidreturned" name="eidreturned"  readonly>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date Received</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="date" class="form-control form-control-sm" id="eReturnedDate" name="eReturnedDate" data-mask >
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Particulars</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="eParticulars" name="eParticulars" rows="3" style="resize: none;" required></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Control No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="eMCrTNo" name="eMCrTNo" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Returned From</label>
            <div class="col">
              <select class="form-control form-control-sm select2" id="ereturnfrom" name="ereturnfrom" onchange="echange_from()" required>
                <option value="0">Employee</option>
                <option value="1">Supplier</option>
              </select>
            </div>
          </div>  

          <div id="efrom"></div>
          <!-- <div class="form-group row">
            <label class="col-sm-3 col-form-label">Returned By</label>
            <div class="col">
              <input type="hidden" class="hidden" id="empno_ereturnedby" name="empno_ereturnedby">
              <select class="form-control form-control-sm select2" id="eReturnedBy" name="eReturnedBy" required>
                <?php foreach($empname as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div> -->

         <!--  <div class="form-group row">
            <label class="col-sm-3 col-form-label">Reference MCT</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="eRefNo" name="eRefNo">
            </div>
          </div> -->
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
<div class="modal fade" id="modal-deletereturn">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">Are you sure to delete this record?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/returnedmaterials_helper.php">
          <input type="hidden" id="didreturned" name="didreturned">
          <p id="dParticulars"></p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
          <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  function change_from() {
    var from = $('#returnfrom').val();
    $.ajax({  
      url: 'contents/content.php',
      type: 'post',
      data: { from },
      success: function (data) {                 
        $('#from').html(data);
      },
      error: function(){
        alert('Error: from');
      }
    });
  }change_from();

  function echange_from(e, idfrom='') {    
    var from = $('#ereturnfrom').val();
    $.ajax({  
      url: 'contents/content.php',
      type: 'post',
      data: { from:from, idfrom:idfrom },
      success: function (data) {                    
        $('#efrom').html(data);        
      },
      error: function(){
        alert('Error: from');
      }
    });
    loadidfrom(idfrom);
  }

  function loadidfrom(idfrom) {
    $("#aReturnedBy").select2().val(idfrom).trigger('change.select2');
  }

</script>

