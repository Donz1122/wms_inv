<?php 
header('content-Type: text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html>
<html lang="en"> 
<?php 
include 'session.php';
include 'includes/header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php';
include 'includes/toastmsg.php';
?>
<body class="hold-transition sidebar-mini layout-fixed  layout-footer-fixed text-sm">
  <div class="wrapper">

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4> ZANECO Warehouse Inventory System</h4>
            </div>            
          </div>
        </div> 
      </section>

      <?php 

      $rows = $db->query("select * from tbl_signatory")->fetch_assoc();  
      
      ?>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">Receiving Report</h3>                 
                  <div class="card-tools">                    
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Noted By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="rr_notedby" name="rr_notedby" value="<?= $rows['RRNotedby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="rr_position" name="rr_position" value="<?= $rows['RRNotedPos']?>">
                    </div>
                  </div>
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" onclick="update_receiving()"> Update</button>                  
                </div>
              </div>  

              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">Return Signatories</h3>                 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Noted By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['ReturnsNotedby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['ReturnsNotedPos']?>">
                    </div>
                  </div>
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" > Update</button>                  
                </div>
              </div>    

              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">Salvage Signatory</h3>                 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Salvage By:</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['SalvageVerified']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['SalvageVerpos']?>">
                    </div>
                  </div>                  
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" > Update</button>                  
                </div>
              </div>  

              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">Gatepass Signatories</h3>                 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Approved By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['GPApproved']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['GPAppPos']?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Verified By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['GPVerified']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="suppliername" class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['GPVerPos']?>">
                    </div>
                  </div>
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" > Update</button>                  
                </div>
              </div>    

            </div>
            <div class="col-md-6">
              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">MTT/Requisition</h3>                 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">MTT Noted By:</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTNotedby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTNotedPos']?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Recommended By:</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTRecommededby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTrecompos']?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Approved By:</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTApproved']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MCTApprovedPos']?>">
                    </div>
                  </div>                  
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" > Update</button>                  
                </div>                
              </div>  

              <div class="card card-primary card-outline">
                <div class="card-header"> 
                  <h3 class="card-title ml-2">Employee Receipt Signatories</h3>                 
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>                    
                  </div>
                </div>
                <div class="card-body">                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Approved By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRApprovedby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRapprovedpos']?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Reviewed By</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRreviewedby']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRreviewpos']?>">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Verified By:</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRverified']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Position</label>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" value="<?= $rows['MRverifiedpos']?>">
                    </div>
                  </div>
                </div>
                <div class="card-footer justify-content-left">                  
                  <button type="button" class="btn btn-warning btn-xs" > Update</button>                  
                </div>
              </div>  

            </div>
          </div>



        </div> 
      </section>
    </div>

    <?php //include 'modals/items_modal.php'?>
    <?php include 'includes/footer.php'?>
  </div>

  <script>   
    $.widget.bridge('uibutton', $.ui.button)

    function update_receiving() {
      alert('receiving');
    }

  </script>

</body>
</html>
