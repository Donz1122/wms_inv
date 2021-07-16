<?php
include 'session.php';

$deptcode = $_GET['id'];
$sql = "select a.idempreceipts, datereceived, deptcode, deptname, a.mrnumber, amount, remarks, pdflocation
from tbl_empreceipts a
inner join tbl_department b on b.deptcode = a.empno
left outer join (select idempreceipts, sum(amount) amount from zanecoinvphp.tbl_empreceiptdetails group by idempreceipts) c on c.idempreceipts = a.idempreceipts
where a.active = 0 and b.deptcode = '$deptcode'
order by datereceived desc";
$rows = $db->query($sql)->fetch_assoc();
$query = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en"> 
<?php include 'includes/header.php' ?>
<?php include 'includes/alertmsg.php' ?>

<style type="text/css">

  table.dataTable th.focus,
  table.dataTable td.focus { outline: none; }
  .gray   { color: gray;    }
  .red    { color: red;     }
  .orange { color: orange;  }
  .green  { color: green;   }
  .blue   { background: blue;  color: white; }
</style>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php include 'includes/navbar-header.php'; ?>   
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col">
              <h4><i style="font-size: 18px;">MR Details of</i> <?= ucwords(strtolower($rows['deptname'])); ?></h4>
            </div>
            <div class="ml-auto">
              <a href="memoreceipts.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
            </div>
          </div>
        </div>
        <section class="content">
          <div class="col-12 row">
            <div class="col-sm-4">
              <div class="card card-primary card-outline">
                <div class="card-header">                  
                  <div class="card-title" id="issuance-title">
                  </div>
                  <button class="btn btn-info btn-sm add_mr_department" href="#add_mr_department" data-toggle="modal tooltip" title="Add MR"> Add MR</button>
                  <button class="btn btn-info btn-sm printsummary" data-toggle="tooltip" title="Print Summary"> Summary</button>
                </div>
                <div class="card-body">

                  <table id="table1" class="table table-bordered table-sm">
                    <thead> 
                      <tr>
                        <th style="display: none;"></th>
                        <th><i class="fa fa-paperclip" style="color: #AEA3A3;" data-toogle="tooltip" title="Attachment"></i></th>
                        <th>Date</th>
                        <th>MR Number</th>
                        <th>Amount</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($query as $row): ?>
                        <tr align="center" title="<?= $row['remarks'] ?>">
                          <td style="display: none;"><?= $row['idempreceipts'] ?></td>
                          <td><?php if(!empty($row['pdflocation'])) { ?> <i class="fa fa-paperclip faa-ring animated-hover"></i> <?php } ?></td>
                          <td align="right"><?= $row['datereceived']; ?></td>
                          <td align="left"><?= $row['mrnumber']; ?></td>
                          <td align="right"><?= number_format($row['amount'],2); ?></td>
                          <td>
                            <center>
                              <div class="btn-group">
                                <button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_mr_department('<?= $row['idempreceipts'] ?>')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>
                                <?php if($_SESSION['restriction'] == '101') { ?>
                                  <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_mr_department('<?= $row['idempreceipts'] ?>')"><i class="fa fa-trash-alt"></i></button>
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
            </div>

            <div class="col-sm-8" id='tab2' >
              <section class="content">
                <div class="card card-warning card-outline">
                  <div class="card-header">                      
                    <button class="btn btn-info btn-sm tab3-open ml-2" title="Retrieve from receipts">Add Item</button>                      
                    <button class="btn btn-info btn-sm print" onclick="print_hardcopy()"> Hardcopy</button>
                    <a class="mt-3 ml-2" href="#" > <strong id="selected_mrnumber" name="selected_mrnumber" style="margin: 4px 2px; color: green;">MR Number</strong></a>
                    <div class="card-tools row">
                      <form action="pdf/pdfhelper.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="mrnumber" name="mrnumber" />
                        <div class="btn-group">
                          <div class="btn btn-default btn-sm btn-file btn-append">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="file[]" size="250" multiple> Browse</input>
                          </div>
                          <button class="btn btn-info btn-sm mr-2" type="submit" value="Upload" name="upload"> Upload Attachment</button>
                        </div>
                      </form>
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand" title="Maximize"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <table id="table2" class="table table-bordered table-hover table-striped table-sm">
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
                          <th>Amount</th>
                          <th title="Depreciation Amount">Dep. Amount</th>
                          <!-- <th>Unit Status</th>
                            <th>RR Number</th> -->
                            <th width="60px"></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </section>
                  <div id="loadpdf"></div>
                </div>
                
                <div class="col-sm-8 maximize" id="tab3" style="display: none;">
                  <section class="content">
                    <div class="card card-warning card-outline card_receipts">
                      <div class="card-header">
                        <div class="btn-group col-sm-10">
                          <strong> Material Receipts List</strong>
                        </div>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand" title="Maximize"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-tool tab3-close" ><i class="fas fa-times"></i></button>
                        </div>
                      </div>
                      <div class="card-body">
                        <table id="table3" class="table table-bordered table-hover table-striped table-sm">
                          <thead>
                            <tr>
                              <th style="display: none;"></th>
                              <th width="100px">RR Number</th>
                              <th width="200px">Item Name</th>
                              <th width="150px">Brand Name</th>
                              <th>Model</th>
                              <th width="200px">Specs</th>
                              <th width="200px">Supplier</th>
                              <th style="text-align: right;">Quantity</th>
                              <th style="text-align: right;">Cost</th>
                              <th style="text-align: right;">Amount</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </section>
                </div>

              </div>
            </section>
          </div>
        </div>


        <?php include 'includes/footer.php' ?>
        <?php include 'modals/mr_department_modal.php' ?>
        <?php include 'modals/mr_details_modal.php' ?>
      </div>
    </body>
    </html>

    <script type="text/javascript">

      $('.tab3-open').click(function(e){
        var id = $('#table1').DataTable().rows('.selected').data()[0][0];
        if(id != '') {           
          $('#tab2').hide();
          $('#tab3').show();
          $('.card_receipts').CardWidget('maximize');
        }
      });
      $('.tab3-close').click(function(e){
        $('#tab2').show();
        $('#tab3').hide();
      });
      

      function load_mr_department_details(){
        var table = $("#table1").DataTable({       
          "responsive": true,
          "lengthChange": true,
          "searching": false,
          "autoWidth": false,
          "processing": true,
          "select": true,
          "keys": true,
          "order":[2, "desc"],
          "keys": { "keys": [ 38,40,13 ], },
          "columnDefs": [{ "targets": [ 0 ], "visible": false }],
          "select": { "style": 'single' },
          "initComplete": function(settings, json) {
            $('#table1').DataTable().rows( 0 ).nodes().to$().click();
          }            
        });   
      }load_mr_department_details();

      $('#table1 tbody').on( 'click', 'tr', function (e, datatable, cell) {
        $('#table1').DataTable().rows( '.selected' ).nodes().to$().removeClass( );
        $(this).toggleClass('selected');

        var id = $('#table1').DataTable().rows('.selected').data()[0][0];
        $('#table2').DataTable().destroy();
        fetch_data_table2('yes', id);
        $('#selected_mrnumber').html($('#table1').DataTable().rows('.selected').data()[0][3]);
        $('#mrnumber').val($('#table1').DataTable().rows('.selected').data()[0][3]);
        $('#table1').DataTable().rows( '.selected' ).nodes().to$().addClass( 'blue' );
        loadPDF($('#table1').DataTable().rows('.selected').data()[0][3], $('#table1').DataTable().rows('.selected').data()[0][1]);
      });
      
      function select_mr() {
        $('#table2').DataTable().destroy();
        fetch_data_table2('yes', id);
        $('#selected_mrnumber').html($('#table1').DataTable().rows('.selected').data()[0][3]);
        $('#mrnumber').val($('#table1').DataTable().rows('.selected').data()[0][3]);
      }

      $('.add_mr_department').click(function(e){
        e.preventDefault();        
        $('#deptcode').val(<?= $deptcode ?>);
        $("#deptname").select2().val(<?= $deptcode ?>).trigger('change.select2');
        $("#deptname").prop( "disabled", true );
        $('#modal_add_mr_department').modal('show');
      });

      function edit_mr_department(id) {
        $.ajax({
          url: 'datahelpers/mr_department_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){      
            $('#eidempreceipts').val(data.idempreceipts);
            $('#emrdate').val(data.datereceived);
            $('#edeptcode').val(data.deptcode);        
            $("#edeptname").select2().val(data.deptcode).trigger('change.select2');
            $("#edeptname").prop( "disabled", true );
            $('#emrnumber').val(data.mrnumber);
            $('#eremarks').val(data.remarks);        
            $('#modal_edit_mr_department').modal('show');
          }
        });
      };

      function delete_mr_department(id) {
        $.ajax({
          url: 'datahelpers/mr_department_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
           $('#dempreceipts').val(data.idempreceipts);
           $('#dmrnumber').html(data.mrnumber);             
           $('#modal_delete_mr_department').modal('show');
         }
       });          
      };

      fetch_data_table2('no');
      function fetch_data_table2(issearch, mrno='', idrr=''){
        var table = $('#table2').DataTable( {      
          "lengthChange"    : true,
          "paging"          : true,
          "ordering"        : true,
          "info"            : true,      
          "order"           : [ 2,"asc" ],
          "columnDefs"      : [{
            "targets"       : [ 0 ],
            "visible"       : false,      
          },{
            "targets"       : [ 1 ],
            "render"        : function ( data, type, row ) {
              return '<a data-toggle="tooltip" title="'+
              'Item Name  : '+row[2]+'&#013'+
              'Specs: '+row[3]+'" >'+row[1]+'</a>';
            }
          },{
            "targets"       : [ 7,8,9 ],
            "className"     : "text-right",
          },{
            "targets"       : [ 10 ],
            "render"        : function ( data, type, row ) {
              return '<center>'+
              '<div class="btn-group">'+
              '<button class="btn btn-warning btn-xs text-sm" onclick="edit_details('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
              '<?php if($_SESSION["restriction"] == "101") { ?>'+
              '<button class="btn btn-danger btn-xs" title="Delete" onclick="delMRDetails('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
              '<?php } ?>'+
              '</div>'+
              '</center>';
            }
          }],
          "processing"      : true,
          "serverSide"      : true,
          "ajax"            : {
            url             : "serverside/ss_employeereceipts_details.php",
            type            : "post",
            data            : {
              issearch      : issearch,
              mrno          : mrno
            },
            error           : function(){},
          },
          "autoWidth"     : false,      
          "rowReorder": {
            selector      : 'td:nth-child(2)'
          },
          "responsive"    : true,          
        });
      };

      fetch_datas('no');
      function fetch_datas(issearch, rrno='', idrr=''){
        var table = $('#table3').DataTable({                
          "lengthChange"    : true,
          "searching"       : true,                    
          "select"          : true,
          "keys"            : true,       
          "order"           : [ 0,"desc" ],
          "columnDefs"      : [{
            "targets"       : [ 0 ],
            "visible"       : false,
          },{
            "targets"       : [ 1 ],
            "render"        : function ( data, type, row ) {
              return '<a data-toggle="tooltip" title="Retrieve!" href="#" onclick="retreive_receipts('+row[0]+')">'+row[1]+'</a>';
            }
          },{
            "targets"       : [ 7,8,9 ],
            "className"     : "text-right",
          }],
          "processing"      : true,
          "serverSide"      : true,
          "ajax":{
            url             :"serverside/ss_empployeereceipts_retreiverr.php",
            type            : "post",
            error           : function(){},
          },
          "autoWidth"     : false,      
          "rowReorder": {
            selector      : 'td:nth-child(2)'
          },
          "responsive"    : true,          
        });
      };

      function getRow(id){        
        $.ajax({
          url: 'datahelpers/mr_employees_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            $('#e_idempreceipts').val(data.idempreceipts);
            $('#e_itemcode').val(data.itemcode);
            $('#e_empno').val(data.empno);
            $('#e_empname').val(data.empno);
            $('#e_mrnumber').val(data.mrnumber);

            $('#e_empno_a').val(data.empno);
            $('#e_empname_a').val(data.empname);

            $('#e_empno_b').val(data.empno);
            $('#e_empname_b').val(data.empname);

            $('#aempno_d').val(data.empno);
            $('#aempname_d').val(data.empname);

            $('#e_datereceived').val(data.datereceived);
            $('#e_status').val(data.status);
            $('#e_remarks').val(data.remarks);

            $('#e_transfers').val(data.transfers);

          }
        });
      }
      
      function retreive_receipts(idreceiptdetails){   
        $('#tab2').show();
        $('#tab3').hide(); 
        var id = $('#table1').DataTable().rows('.selected').data()[0][0];
        var mrno = $('#table1').DataTable().rows('.selected').data()[0][3];
        $.ajax({
          url: 'datahelpers/mr_details_helper.php',
          type: 'post',
          dataType: 'json',
          data: { idreceiptdetails:idreceiptdetails },
          success: function (data) {

            $('#aidreceiptsdetails').val(data.idreceiptsdetails);
            $('#aidreceipts').val(data.idreceipts);
            $('#aiditemindex').val(data.iditemindex);
            $('#aidempreceipts').val(id);

            $('#arrnumber').val(data.rrnumber);
            $('#adateaquired').val('<?php echo $enddate ?>');
            $('#aitemname').val(data.itemname);
            $('#abrandname').val(data.brandname);
            $('#amodel').val(data.model);
            $('#aspecs').val(data.specifications);

            $('#abalqty').val(data.quantity);
            $('#aquantity').val(data.quantity);
            $('#aamount').val(data.unitcost);

            $('#modal-addmrdetails').modal('show');
          },
          error: function(){
            alert('error');
          }
        });
      }

      function edit_details(id) {
        if(id != '') {
          $.ajax({
            url: 'datahelpers/mr_details_helper.php',
            type: 'post',
            dataType: 'json',
            data: { id:id },
            success: function(data){                    
              if(data.idempreceiptdetails != '') {
                $('#eidempreceiptdetails').val(data.idempreceiptdetails);           

                $('#errnumber').val(data.rrnumber);
                $('#edateaquired').val(data.dateaquired);
                $('#eitemname').val(data.itemname);
                $('#ebrandname').val(data.brandname);
                $('#emodel').val(data.model);
                $('#especs').val(data.specifications);
                $('#eserialnos').val(data.serialnos);              
                $('#ebalqty').val(data.quantity);
                $('#equantity').val(data.quantity);
                $('#eamount').val(data.amount);
                $('#emrstatus').val(data.mrstatus);
                $('#eunitstatus').val(data.unitstatus);

                $('#modal-editmrdetails').modal('show');

                $('#eempnofrom').val($('#empno').val());
                $('#eempnamefrom').val($('#empname').val());               
                $('#prevmrnumber').val($('#mrnumber').val());               

              }

            }
          });          
        }
      };

      function delMRDetails(id) {
        if(id != '') {
          $.ajax({
            url: 'datahelpers/mr_details_helper.php',
            type: 'post',
            dataType: 'json',
            data: { id:id },
            success: function(data){            
              if(data.idempreceiptdetails != '') {
                $('#didempreceiptdetails').val(data.idempreceiptdetails);           
                $('#ditemname').html(data.itemname); 
                $('#ditemname2').val(data.itemname); 
                $('#modal-deletemrdetails').modal('show');
              }
            }
          });                    
        }
      };

  // $('.print').click(function(e){
  //   e.preventDefault();
  //   $('#modal-mrto').modal('show');
  // });

  function print_hardcopy() {
    $('#modal-mrto').modal('hide');
    //var dept = $("#pdeptto option:selected").text();
    var id = $('#table1').DataTable().rows('.selected').data()[0][0];
    var redirectWindow = window.open("printing/print_mr_hardcopy.php?"+"id="+id+"&mri=Department", '_blank');
    redirectWindow.location;

    // window.location="printing/print_mr_hardcopy.php?"+"id="+id+"&mri=Department";
  };

  $('.printsummary').click(function(e){
    e.preventDefault();
    var id = <?php echo $deptcode?>;
    var redirectWindow = window.open("printing/print_empmrsummary.php?"+"id="+id+"&mri=Department", '_blank');
    redirectWindow.location;
    
    // window.location="printing/print_empmrsummary.php?"+"id="+id+"&mri=Department";
  });

  function loadPDF(mrnumber, attachment){
    if(attachment != '') {
      $.ajax({
        url: 'pdf/pdf_file_loader.php',
        type: 'post',
        data: { mrnumber:mrnumber },
        success: function (data) {
          $('#loadpdf').html(data);
        },
        error: function(){            
        }
      });
    } else {
      $('#loadpdf').html('');
    }
  };

  
</script>


