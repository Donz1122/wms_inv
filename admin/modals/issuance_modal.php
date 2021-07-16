<?php
include "class/clsitems.php";
$employee = $item->employee();
//$empno = $_SESSION['empno'];
?>

<div class="modal fade" id="modal-addissuance">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title">New Material Charge Ticket</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <input type="hidden" id="idissuance" name="idissuance" />
      <!-- <input type="hidden" id="transferto" name="transferto" /> -->

      <form class="form-horizontal" method="POST" action="datahelpers/issuance_helper.php">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 control-label">Date</label>
            <div class="input-group input-group-sm col">
              <input type="date" class="form-control form-control-sm" id="idate" name="idate" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask value="<?= date('Y-m-d'); ?>">
            </div>
          </div>

          <!-- <div class="form-group row">
            <label class="control-label col-sm-3 ilabel"></label>
            <div class="col"> -->
              <input type="hidden" class="form-control form-control-sm" id="inumber" name="inumber" readonly>
            <!-- </div>
            </div> -->

            <div class="form-group row">
              <label class="control-label col-sm-3">MRV No.</label>
              <div class="col">
                <input type="text" class="form-control form-control-sm inputs" id="rvno" name="rvno">
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-sm-3">Purpose</label>
              <div class="col">
                <textarea class="form-control form-control-sm" id="purpose" name="purpose" rows="3" cols="4" required style="resize: none;"></textarea>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Requisitioner</label>
              <div class="col">
                <input type="hidden" class="form-control form-control-sm" id="empno" name="empno">
                <select onchange="selectEmployee('a')" class="form-control form-control-sm inputs" id="requister" name="requister" disabled>
                  <?php foreach($employee as $row): ?>
                    <option value="<?= $row['empnumber']; ?>"><?= utf8_decode($row['empname']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>


            <div class="form-group row" id="area">
              <label class="control-label col-sm-3">Transfer To</label>
              <div class="col">
                <select class="form-control form-control-sm select2" id="transferto" name="transferto" disabled>
                  <option value="DMO">Dipolog Main Office</option>
                  <option value="PAS"><?= utf8_decode('Piñan Area Services') ?></option>
                  <option value="SAS">Sindangan Area Services</option>
                  <option value="LAS">Liloy Area Services</option>
                </select>
              </div>
            </div>
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

  <div class="modal fade" id="modal-editissuance">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Material Charge Ticket</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form-horizontal" method="POST" action="datahelpers/issuance_helper.php">
          <input type="hidden" id="eidissuance" name="eidissuance" />
          <input type="hidden" id="etransferto" name="etransferto" />
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-sm-3 control-label">Date</label>
              <div class="col">
                <input type="date" class="form-control form-control-sm" id="eidate" name="eidate" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask >
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-sm-3">Purpose</label>
              <div class="col">
                <textarea class="form-control form-control-sm" id="epurpose" name="epurpose" rows="3" cols="4" required="true" autofocus="true" style="resize: none;"></textarea>
              </div>
            </div>

         <!--  <div class="form-group row">
            <label class="control-label col-sm-3 ilabel"></label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="einumber" name="einumber" readonly="true">
            </div>
          </div> -->

          <div class="form-group row">
            <label class="control-label col-sm-3">RV No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm inputs" id="ervno" name="ervno">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Requisitioner</label>
            <div class="col">
              <input type="hidden" class="form-control form-control-sm" id="eempno" name="eempno">
              <select onchange="selectEmployee('e')" class="form-control form-control-sm select2 inputs" id="erequister" name="erequister" disabled>
                <?php foreach($employee as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= utf8_decode($row['empname']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <?php
          $res     = $_SESSION['restriction'];
          $area    = $_SESSION['area'];
          if ($res == 1 && $area <> 'DMO') { ?>
            <div class="form-group row" id="earea">
              <label class="control-label col-sm-3">Transfer To</label>
              <div class="col">
                <select class="form-control form-control-sm" id="etransferto" name="etransferto" disabled>
                  <option value="DMO">Dipolog Main Office</option>
                  <option value="PAS"><?= utf8_decode('Piñan Area Services') ?></option>
                  <option value="SAS">Sindangan Area Services</option>
                  <option value="LAS">Liloy Area Services</option>
                </select>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" id="btn-edit" name="edit"> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-deleteissuance">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="">Delete!</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/issuance_helper.php">
          <input type="hidden" id="didissuance" name="didissuance">
          <input type="hidden" id="dinumber" name="dinumber">
          <p>Are you sure to delete this record?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger " name="delete"> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript">

  $('.select2').select2();
  $('.inputs').keydown(function (e) {
    if (e.which === 13) {
      var index = $('.inputs').index(this) + 1;
      $('.inputs').eq(index).focus();
    }
  });

  function selectEmployee(e) {
    if(e == 'a') {
      var empno = $('#requister').val();
      $('#empno').val(empno);
    } else if(e == 'e') {
      var empno = $('#erequister').val();
      $('#eempno').val(empno);
    }
  }


  // function saveIssuance() {
  //   var add         = "add";
  //   var inumber     = $('#inumber').val();
  //   var idate       = $('#idate').val();
  //   var purpose     = $('#purpose').val();
  //   var rvno        = $('#rvno').val();
  //   var requister   = $('#requister option:selected').html();
  //   var transferto  = $('#transferto').val();
  //   var empno       = $('#empno').val();
  //   $.ajax({
  //     url: 'datahelpers/issuance_helper.php',
  //     type: 'post',
  //     data: {
  //       add:add,
  //       inumber:inumber,
  //       idate:idate,
  //       purpose:purpose,
  //       rvno:rvno,
  //       requister:requister,
  //       transferto:transferto,
  //       empno:empno,
  //     },
  //     success: function(data){        
  //       $('#modal-addissuance').modal('hide');
  //       window.location="issuance.php";
  //     },
  //     error: function(){
  //       alert('error');
  //     }
  //   });
  // };

  // function updateIssuance() {
  //   var edit        = "edit";
  //   var idissuance  = $('#eidissuance').val();
  //   var purpose     = $('#epurpose').val();
  //   var rvno        = $('#ervno').val();
  //   var requister   = $('#erequister option:selected').html();
  //   var transferto  = $('#etransferto').val();
  //   var empno       = $('#eempno').val();
  //   $.ajax({
  //     url: 'datahelpers/issuance_helper.php',
  //     type: 'post',
  //     dataType: 'json',
  //     data: {
  //       edit:edit,
  //       idissuance:idissuance,
  //       purpose:purpose,
  //       rvno:rvno,
  //       requister:requister,
  //       transferto:transferto,
  //       empno:empno,
  //     },
  //     success: function(data){
  //       $('#modal-editissuance').modal('hide');
  //       window.location="issuance.php";
  //     },
  //     error: function(){
  //       alert('error');
  //     }
  //   });

  // };


</script>
