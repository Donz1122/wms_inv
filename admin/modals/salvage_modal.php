<?php
include "class/clsitems.php";
$id         = $db->query("select count(idsalvage)+1 as cnt from tbl_salvage where year(returneddate) = year(now()) limit 1;")->fetch_assoc();;
$num1       = $id['cnt'];

back:
$salvageno  = 'MSC'.$_SESSION["area"].$mos.$year.'-'.$num1;
$id = $db->query("select salvageno from tbl_salvage where salvageno like '$salvageno' limit 1;")->fetch_assoc();
if(mysqli_num_rows($id)>0) {
  $num1 += 1;
  goto back;
}

$empname = $item->employee();

?>
<!-- Add -->
<div class="modal fade" id="add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Salvage</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/salvage_helper.php">
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
                <input type="date" class="form-control form-control-sm" id="transactiondate" name="transactiondate" data-mask value="<?= $enddate; ?>">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Control No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="salvageno" name="salvageno" value="<?= $salvageno; ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Purpose</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" col="2" id="description" name="description" required></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Returned By</label>
            <div class="col">
              <input type="hidden" class="hidden" id="empno_returnedby" name="empno_returnedby">
              <select class="form-control form-control-sm select2" id="returnedby" name="returnedby" required>
                <?php foreach($empname as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
                <?php endforeach; ?>
              </select>
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

<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Salvage</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/salvage_helper.php">
        <input type="hidden" id="eidsalvage" name="eidsalvage" />
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
                <input type="date" class="form-control form-control-sm" id="etransactiondate" name="etransactiondate" data-mask>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Control No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="esalvageno" name="esalvageno" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Purpose</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" col="2" id="edescription" name="edescription" required></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Returned By</label>
            <div class="col">
              <select class="form-control form-control-sm select2" id="ereturnedby" name="ereturnedby" required>
                <?php foreach($empname as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= $row['empname']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
            <button type="submit" class="btn btn-warning" name="edit"><i class="fa fa-save"></i> Update</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">Are you sure to delete this record?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/salvage_helper.php">
        <div class="modal-body">
          <input type="hidden" id="didsalvage" name="didsalvage">
          <p id="ddescription"></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt faa-ring animated-hover"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

