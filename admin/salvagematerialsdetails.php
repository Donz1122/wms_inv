
<!DOCTYPE html>
<html lang="en">
<?php 
include 'session.php';
include 'includes/header.php';
include 'includes/alertmsg.php';
$idsalvage = $_GET['id'];    
$query = $db->query("SELECT a.*, concat(lastname,', ',firstname,' ',middleinitial) empname, empno                
  FROM tbl_salvage a
  LEFT OUTER JOIN tbl_salvagedetails b ON b.idsalvage = a.idsalvage and b.active = 0
  LEFT OUTER JOIN zanecopayroll.employee c on c.empnumber = a.empno    
  WHERE a.idsalvage = '$idsalvage' LIMIT 1")->fetch_assoc(); 
$_SESSION['xempno'] = $query['empno'];

$rows = $db->query("SELECT idsalvagedetails, salvageno, ii.itemcode, itemname, brandname, specifications, model, sd.serialnos, sd.quantity, unit, mrd.unitcost, sd.remarks
  FROM tbl_salvagedetails sd
  LEFT JOIN tbl_empreceiptdetails mrd on mrd.idempreceiptdetails = sd.idempreceiptdetails
  LEFT JOIN tbl_receiptsdetails rd ON rd.idreceiptsdetails = mrd.idreceiptsdetails
  LEFT JOIN tbl_itemindex ii ON ii.iditemindex = mrd.iditemindex
  WHERE idsalvage = '$idsalvage'
  ORDER BY itemname;");
  ?>

  <body class="hold-transition layout-top-nav text-sm">
    <div class="wrapper">
      <input type="hidden" id="idsalvage" name="idsalvage" value="<?= $idsalvage ?>">
      <div class="content-wrapper">
        <div class="content-header">    
          <div class="container-fluid">
            <div class="row mb-2"> 
              <div class="col-sm-6"><h4><i style="font-size: 18px;">Salvage Material Details</i></h4></div>   
              <div class="ml-auto">
                <a href="salvagematerials.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>  
              </div>         
            </div>
          </div>
          <section class="content">         
            <div class="row">
             <div class="col-md-3">
              <div class="card card-primary card-outline">              
                <div class="card-body">
                  <strong><i class="fas fa-user-alt mr-1"></i>Returned By</strong>
                  <p class="text-muted float-right"><?= utf8_decode($query['empname']) ?></p>
                  <hr>
                  <strong><i class="fas fa-calendar-alt mr-1"></i>Date</strong>
                  <p class="text-muted float-right"><?= date('M d, Y', strtotime($query['returneddate'])) ?></p>
                  <hr>
                  <strong><i class="fas fa-server mr-1"></i>Salvage No.</strong>
                  <p class="text-muted float-right"><?= $query['salvageno'] ?></p>
                  <hr>
                  <strong><i class="fas fa-sign mr-1"></i>Purpose</strong>
                  <p class="text-muted"><?= $query['description'] ?></p>
                  <!-- <hr>
                  <strong><i class="fas fa-money-bill-wave-alt mr-1"></i>Amount</strong>
                  <p class="text-muted float-right"><?= number_format($query['amount'], 2) ?></p> -->
                </div>
              </div>
            </div>

            <div class="col-sm-9">
              <section class="content">
                <div class="card card-warning card-outline">
                  <div class="card-header">                  
                    <button class="btn btn-info btn-sm add"> Add Item</button>   
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand" title="Maximize"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <table id="table2" class="table table-bordered table-hover table-striped">
                      <thead>                
                        <tr>
                          <th style="display: none;"></th>
                          <th width="100px">Item Code</th>
                          <th width="200px">Item Name</th>
                          <th width="200px">Brand Name</th>
                          <th width="200px">Specification</th>
                          <th width="150px">Model</th>
                          <th>Serial No.</th>
                          <th>Qty</th>
                          <th>Remarks</th>
                          <th width="90px"></th>
                        </tr>
                      </thead>            
                      <tbody>
                        <?php foreach($rows as $row): ?> 
                          <tr align="center">   
                            <td style="display: none;"><?= $row['idsalvagedetails'] ?></td>
                            <td align="left"><?= $row['itemcode']; ?></td>
                            <td align="left"><?= $row['itemname']; ?></td>
                            <td align="left"><?= $row['brandname']; ?></td>
                            <td align="left"><?= $row['model']; ?></td>
                            <td align="left"><?= $row['specifications']; ?></td>
                            <td align="left"><?= $row['serialnos']; ?></td>
                            <td align="right"><?= $row['quantity']; ?></td>
                            <td align="left"><?= $row['remarks']; ?></td>
                            <td>
                              <center>
                                <div class="btn-group">
                                  <?php if($_SESSION['restriction'] == '101') { ?>
                                  <button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="editSalvageMaterialsDetails('<?= $row['idsalvagedetails'] ?>')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="delSalvageMaterialsDetails('<?= $row['idsalvagedetails'] ?>')"><i class="fa fa-trash-alt"></i></button>
                                  <?php } ?>
                                </div> 
                              </center>
                            </td>                      
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>  
            </div>
          </div>
        </section>
      </div>
    </div>

    <?php include 'includes/footer.php'?>
    <?php include 'modals/salvagematerialsdetails_modal.php'?>  


    <script type="text/javascript">

      var empno = '<?php echo $query['empno'] ?>'
      var dataTable = $('#table2').DataTable( {
        "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [
        {
          "targets": [ 0 ],
          "visible": false,
          "searchable": false
        }],
        "processing": true,         
      });

      $('.add').click(function(e){ e.preventDefault();            
        $('#add').modal('show');                
        $('#empno').val(empno);
        $('#aidsalvage').val('<?= $idsalvage ?>');
        $('#salvageno').val('<?= $query['salvageno']; ?>');
      });

      function editSalvageMaterialsDetails(id) {        
        if (id != '') {
          $('#edit').modal('show');
          getRow(id);
        }
      };

      function delSalvageMaterialsDetails(id) {                
        if (id != '') {
          $('#delete').modal('show');
          getRow(id);
        }
      };

      function getRow(id){
        $.ajax({
          url: 'datahelpers/salvagematerialsdetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            $('#eidsalvagedetails').val(data.idsalvagedetails);

            $('#esalvageno').val(data.salvageno);
            $('#eiditemindex').val(data.iditemindex);          
            $('#eitemcode').val(data.itemcode);
            $("#eitemname").select2().val(data.itemcode).trigger('change.select2');
            $('#eunit').val(data.unit); 
            $('#eqty').val(data.quantity); 
            $('#eunitcost').val(data.unitcost);

            $('#didsalvagedetails').val(data.idsalvagedetails);            
            $('#ditemname').html(data.itemname); 
          }
        });
      }

    </script>    
  </body>
  </html>
