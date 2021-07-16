<?php

$id       = $db->query("select count(idreceipts)+1 as cnt from tbl_receipts where year(receivedate) = year(now()) and month(receivedate) = month(now()) limit 1;")->fetch_assoc();;
$num1     = $id['cnt'];

back:
$rno      = 'RR'.$_SESSION["area"].$mos.$year.'-'.$num1;
$id       = $db->query("select rrnumber from tbl_receipts where rrnumber like '$rno' limit 1;")->fetch_assoc();
if($id>0) {
  $num1 += 1;
  goto back;
}

$rows         = $item->get_purchase_order(1)->fetchArray();
$rvnumber     = $rows['rvnumber'];
$ponumber     = $rows['ponumber'];
$suppliercode = $rows['suppliercode'];
$suppliername = $rows['suppliername'];
$address      = $rows['address'];

?>

<!-- Add -->
<div class="modal fade" id="modal-add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" >New Material Receipts</h4>
        <button type="button" class="close btn-xm" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"> 
        <form class="form-horizontal" method="POST" action="datahelpers/receipts_helper.php">

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">RR Number</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="rrno" name="rrno" readonly value="<?= $rno ?>" placeholder="0">
            </div>

            <label class="col-sm-2 col-form-label" style="text-align: right;">Date</label>
            <div class="col">
              <div class="input-group input-group-sm">                
                <input type="date" class="form-control form-control-sm" id="receivedate" name="receivedate" data-mask value="<?= $enddate; ?>">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">PO No/Supplier</label>
            <div class="col">

              <input type="hidden" class="form-control form-control-sm" id="pono"         name="pono"          value="<?= $ponumber ?>">
              <input type="hidden" class="form-control form-control-sm" id="suppliercode" name="suppliercode"  value="<?= $suppliercode ?>">
              <input type="hidden" class="form-control form-control-sm" id="suppliername" name="suppliername"  value="<?= $suppliername ?>">
              <input type="hidden" class="form-control form-control-sm" id="address"      name="address"       value="<?= $address ?>">

              <select class="form-control form-control-sm select2" id="idpo" name="idpo" onchange="select_purchase_order()" required>
                <option value="0">DMO - Dipolog Main Office</option>
                <?php $ponumber = $item->get_purchase_order('')->fetchAll();
                foreach($ponumber as $row):
                 if (!empty($row['ponumber'])) { ?>
                  <option value="<?= $row['ponumber']; ?>"><?= $row['ponumber'].' - '.$row['suppliername']; ?></option>
                <?php } else { ?>
                  <option value="<?= $row['ponumber']; ?>"><?= $row['suppliername']; ?></option>
                <?php } endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">RV Number</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="arvno" name="arvno" >
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">DR Number</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="drno" name="drno">
            </div>

            <label class="col-sm-2 form-control-sm" style="text-align: right;">SI No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="sino" name="sino">
            </div>
          </div>
          <div class="form-group row" id="mtts">
            <label class="col-sm-2 form-control-sm">MTT/Doc. No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="adocno" name="adocno" required title="For area use only...">
            </div>            
          </div>
          <div class="form-group row">           
            <label for="bys" class="col-sm-2 form-control-sm">Receive By</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="receiveby" name="receiveby" value="<?= utf8_decode($_SESSION['user']); ?>" readonly>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="add"> Save and Close</button>
          <button type="submit" class="btn btn-info" name="add_open"> Save and Open Details</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modal-edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Material Receipts</h4>
        <button type="button" class="close btn-xm" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/receipts_helper.php">

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">RR Number</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="errno" name="errno" readonly>
            </div>

            <label class="col-sm-2 col-form-label" style="text-align: right;">Date</label>
            <div class="col">
              <div class="input-group input-group-sm">                
                <input type="date" class="form-control form-control-sm" id="ereceivedate" name="ereceivedate" data-mask>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">PO No/Supplier</label>
            <div class="col">

              <input type="hidden" class="form-control form-control-sm" id="eidreceipts" name="eidreceipts" readonly>
              <input type="hidden" class="form-control form-control-sm" id="epono" name="epono" readonly>
              <input type="text" class="form-control form-control-sm" id="esuppliername" name="esuppliername" readonly>
              
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">RV Number</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="ervno" name="ervno" readonly placeholder="0">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">DR Number</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="edrno" name="edrno">
            </div>

            <label class="col-sm-2 form-control-sm" style="text-align: right;">SI No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="esino" name="esino" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 form-control-sm">MTT/Doc. No.</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="edocno" name="edocno" required>
            </div>                
          </div>
          <div class="form-group row">              
            <label class="col-sm-2 form-control-sm">Receive By</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="ereceiveby" name="ereceiveby" readonly>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" name="edit"> Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="modal-delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/receipts_helper.php">
          <input type="hidden" id="del_idreceipts" name="del_idreceipts">
          <p>Are you sure to delete this item?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" name="delete"> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();
  $('[data-mask]').inputmask();

  function select_purchase_order() {
    var supplier  = '';
    var area      = '<?php echo $_SESSION['area'] ?>'    
    var idpo      = document.getElementById("idpo").value;
    if(idpo == '') supplier = $('#idpo option:selected').html();
    $.ajax({
      url: 'datahelpers/receipts_helper.php',
      type: 'post',
      dataType: 'json',
      data: { idpo:idpo, supplier:supplier }, 
      success: function (data) {                    
        $('#arvno').val(data.rvnumber);
        $('#pono').val(data.ponumber);

        $('#ervno').val(data.rvnumber);
        $('#epono').val(data.ponumber);

        $('#suppliercode').val(data.pcode);
        $('#suppliername').val(data.suppliername);
        $('#address').val(data.address);

        if(data.area == 'area'){
          load_mtts('one');
          $('#adocno').attr('required', false);
          $('#edocno').attr('required', false);
          document.getElementById("adocno").disabled = false;
          document.getElementById("edocno").disabled = false;
        } else {
          load_mtts('zero')          
          $('#adocno').attr('required', true);
          $('#edocno').attr('required', true);
          document.getElementById("adocno").disabled = true;
          document.getElementById("edocno").disabled = true;    
        }

      },
      error: function(){
        alert('error');
      }
    });
  };

  function load_mtts(e){        
    $.ajax({  
      url: 'contents/content.php',
      type: 'post',
      data: { mttnos:e },
      success: function (data) {                 
        $('#mtts').html(data);
      },
      error: function(){
        alert('Error: load_mtts');
      }
    });
  }; 

</script>

