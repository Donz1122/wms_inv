<?php
include 'session.php';

$empno  = $_GET['id'];
$sql    = "select concat(lastname,', ',firstname,' ',middleinitial) empname from zanecopayroll.employee where empnumber = '$empno'";
$empstr = $db->query($sql)->fetch_assoc();

$sql    = "
select idempreceiptdetails, a.idempreceipts, a.empno, empname,
a.mrnumber, sum(c.unitcost * (if(c.active=0,c.quantity,0))) amount, datereceived, pdflocation, hasvalue
from zanecoinvphp.tbl_empreceipts a
left outer join tbl_empreceiptdetails c on c.idempreceipts = a.idempreceipts and c.active <> 1
where a.active = 0 and a.empno = '$empno'
group by a.idempreceipts
order by datereceived desc ";
$mrrows = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/datatable-header.php' ?>
<?php include 'includes/alertmsg.php' ?>

<style type="text/css">

  table.dataTable th.focus,
  table.dataTable td.focus { outline: none; }
  .gray   { color: gray;    }
  .red    { color: red; }
  .orange { color: orange;  }
  .green  { color: green;   }
  .blue   { background: blue;  color: white; }

</style>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php //include 'includes/navbar-header.php'; ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col">
              <!-- <h4><i style="font-size: 18px;">MR Details of</i> <?= ucwords(strtolower($empstr['empname'])); ?></h4> -->
            </div>
            <div class="ml-auto">
              <!-- <a href="memoreceipts.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a> -->
            </div>
          </div>
        </div>
        <section class="content">
          <div class="col-12 row">

            <div class="col-sm-4">
              <div class="card card-primary">
                <div class="card-header">
                  <input type="hidden" id="empno"   name="empno"    value="<?= $empno; ?>" />
                  <input type="hidden" id="empname" name="empname"  value="<?= ucwords(strtolower($empstr['empname'])); ?>" />
                  <div class="card-title" id="issuance-title">
                    MR Details of </i> <?= ucwords(strtolower($empstr['empname'])); ?>
                  </div>
                  <div class="ml-auto">
                    <!-- <button class="btn btn-info btn-sm add_mr_employees" href="#add_mr_employees" data-toggle="modal tooltip" title="Add MR"> Add MR</button>
                      <button class="btn btn-info btn-sm printsummary" data-toggle="tooltip" title="Print Summary"> Summary</button> -->
                    </div>
                  </div>
                  <div class="card-body">
                    <table id="table1" class="table table-sm text-nowrap table-hover table-striped ">
                      <thead>
                        <tr>
                          <th style="display: none;"></th>
                          <th><i class="fa fa-paperclip" style="color: #AEA3A3;" data-toogle="tooltip" title="Attachment"></i></th>
                          <th width="60px;">Date</th>
                          <th>MR Number</th>
                          <th>Amount</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($mrrows as $row): ?>
                          <tr align="center">
                            <td style="display: none;"><?= $row['idempreceipts'] ?></td>
                            <td><?php if(!empty($row['pdflocation'])) { ?> <i class="fa fa-paperclip faa-ring animated-hover"></i> <?php } ?></td>
                            <td align="right"><?= $row['datereceived'] ?></td>
                            <td align="left"><?= $row['mrnumber'] ?></td>
                            <td align="right"><?= number_format($row['amount'],2); ?></td>
                            <td>
                              <center>
                                <div class="btn-group">
                                  <button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_mr_employees('<?= $row['idempreceipts'] ?>')"><span style="font-size: 12px;">Edit</span></button>
                                  <?php if($_SESSION['restriction'] == '101') { ?>
                                    <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_mr_employees('<?= $row['idempreceipts'] ?>')"><i class="fa fa-trash-alt"></i></button>
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
                  <div class="col-12 col-sm-12 mb-1">
                    <button class="btn btn-info btn-sm add_mr_employees" href="#add_mr_employees" data-toggle="modal tooltip" title="Add MR"> Add MR</button>
                    <button class="btn btn-info btn-sm printsummary" data-toggle="tooltip" title="Print Summary"> Summary</button>
                    <button class="btn btn-info btn-sm add" title=""> Add MR Item</button>

                    <!-- <button class="btn btn-info btn-sm tab3-open ml-2" title="Retrieve from receipts">Add Item</button> -->
                    <button class="btn btn-info btn-sm print" onclick="print_harcopy()"> Hardcopy</button>
                    <a href="memoreceipts.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>

                    <div class=" float-right">
                      <form action="pdf/pdfhelper.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="mrnumber" name="mrnumber" />
                        <div class="btn-group">
                          <div class="btn btn-default btn-sm btn-file btn-append">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="file[]" size="250" multiple> Browse</input>
                          </div>
                          <button class="btn btn-info btn-sm mr-2" type="submit" value="Upload" name="upload" >Upload Attachment</button>
                        </div>
                      </form>
                    </div>

                  </div>
                  <div class="col-12 col-sm-12">
                    <div class="card card-primary">
                      <div class="card-header">
                        <a class="mt-3 ml-2" href="#" > <span id="selected_mrnumber" name="selected_mrnumber" style="margin: 4px 2px;">MR Number</span></a>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand" title="Maximize"></i></button>
                        </div>
                      </div>
                      <div class="card-body">
                        <table id="table2" class="table table-sm text-nowrap table-striped ">
                          <thead>
                            <tr>
                              <th></th>
                              <th width="100px">Item Code</th>
                              <th width="200px">Item Name</th>
                              <th width="200px">Specification</th>
                              <th width="300px">Status</th>
                              <th width="200px">Brand Name</th>
                              <th width="150px">Model</th>
                              <th>Serial No.</th>
                              <th>Qty</th>
                              <th>Unit Cost</th>
                              <th title="Depreciation Amount">Dep. Amount</th>
                              <th width="60px"></th>
                              <th style="display: none;"></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </section>
                <div id="loadpdf"></div>
              </div>

              <div class="col-sm-8 maximize" id="tab3" style="display: none;">
                <section class="content">
                  <div class="card card-warning card_receipts">
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
                            <th width="200px">Serials</th>
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

      <?php include 'includes/datatable-footer.php' ?>
      <?php include 'modals/mr_employees_modal.php' ?>
      <?php include 'modals/mr_details_modal.php' ?>
      <!-- <script type="text/javascript" src="../dist/css/selectdatatables/datatables.min.js"></script> -->
    </div>
  </body>
  </html>

<!--
<div class="modal fade" id="modal-mrto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="">Department</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></span>
        </button>
      </div>

      <form class="form-horizontal" method="POST" action="">
        <div class="modal-body">
          <div class="form-group row">
            <div class="col">
              <select onchange="" class="form-control form-control-sm select2" id="pdeptto" name="pdeptto" >
                <option value="0">Office of the General Manager</option>
                <option value="1">Internal Audit Department</option>
                <option value="2">Finance Services Department</option>
                <option value="3">Corporate and Planning Division</option>
                <option value="4">Consumer Accounts Department</option>
                <option value="5">Institutional Services Department</option>
                <option value="6">Engeneering Services Department</option>
                <option value="7">Construction, Operation and Maintenance Department</option>
                <option value="8">System Loss Reduction Division</option>
                <option value="9"><?= utf8_decode("Piñan Area Services")?></option>
                <option value="10">Sindangan Area Services</option>
                <option value="11">Liloy Area Services</option>
              </select>
            </div>
          </div>

        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info btn-sm" data-dismiss="static" onclick="printMR()"><i class="fa fa-print mr-1 faa-ring animated-hover"> </i>Print</button>
        </div>
      </form>
    </div>
  </div>
</d_harcopy>
-->

<script type="text/javascript">

  var id        = '';
  var mrnumber  = '';

  $('.tab3-open').click(function(e){
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
  $('.select2').select2();

  function load_mr_employees_details() {
    var table = $("#table1").DataTable({
      "lengthChange": true,
      "searching": false,
      "responsive": true,
      "autoWidth": false,
      "processing": true,
      // "select": true,
      "order":[2, "desc"],
      "columnDefs": [{ "targets": [ 0 ], "visible": false }],
      // "select": { "style": 'single' },
      // "initComplete": function(settings, json) {
      //   $('#table1').DataTable().rows( 0 ).nodes().to$().click();
      // },
      keys: true,
    });

    $('#table1').DataTable().rows('.selected').nodes().to$().removeClass( 'selected' );
    $('#table1').on('key-focus.dt', function(e, datatable, cell){
      $(table.row(cell.index().row).node()).addClass('selected');
      select_mrnumber();
    });
    $('#table1').on('key-blur.dt', function(e, datatable, cell){
      $(table.row(cell.index().row).node()).removeClass('selected');
    });

  }load_mr_employees_details();

  $(document).ready(function(){

  });

  function select_mrnumber() {
    id = $('#table1').DataTable().rows('.selected').data()[0][0];
    mrnumber = $('#table1').DataTable().rows('.selected').data()[0][3];
    $('#table2').DataTable().destroy();
    fetch_data_table2('yes', id);
    $('#selected_mrnumber').html(mrnumber);
    $('#mrnumber').val(mrnumber);
    loadPDF(mrnumber, $('#table1').DataTable().rows('.selected').data()[0][1]);
  }

  $('#table1 tbody').on( 'click', 'tr', function (e, datatable, cell) {
    $('#table1').DataTable().rows( '.selected' ).nodes().to$().removeClass( );
    $(this).toggleClass('selected');
    select_mrnumber();
  });

  // function select_mr() {
  //   $('#table2').DataTable().destroy();
  //   fetch_data_table2('yes', id);
  //   $('#selected_mrnumber').html($('#table1').DataTable().rows('.selected').data()[0][3]);
  //   $('#mrnumber').val($('#table1').DataTable().rows('.selected').data()[0][3]);
  // }

  $('.add_mr_employees').click(function(e){
    e.preventDefault();
    $('#a_empno').val($('#empno').val());
    $("#a_empname").select2().val($('#empno').val()).trigger('change.select2');
    $("#a_empname").prop( "disabled", true );
    $('#modal-add_mr_employees').modal('show');
  });

  function edit_mr_employees(id) {
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

        $('#modal-edit_mr_employees').modal('show');

      }
    });
  };

  function delete_mr_employees(id) {
    $.ajax({
      url: 'datahelpers/mr_employees_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
       $('#dempreceipts').val(data.idempreceipts);
       $('#dmrnumber').val(data.mrnumber);
       $('#modal-delete_mr_employees').modal('show');
     }
   });
  };

  fetch_data_table2('no');
  function fetch_data_table2(issearch, mrno='', idrr=''){
    var table = $('#table2').DataTable({
      "scrollX"       : true,
      // "lengthChange"  : true,
      "ordering"      : true,
      "info"          : true,
      "order"         : [[ 2,"asc" ]],
      "columnDefs"    : [{
        "targets"     : [ 12 ],
        "visible"     : false,
      },{
        "targets"     : [ 8,9,10 ],
        "className"   : "text-right",
      },{
        "targets"     : [ 0 ],
        "render"      : function ( data, type, row ) {
          return '<center>'+
          ' <div class="btn-group">'+
          '  <button class="btn btn-warning btn-xs text-sm" onclick="edit_details('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
          '  <?php if($_SESSION["restriction"] == "101") { ?>'+
          '    <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_details('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
          '  <?php } ?>'+
          ' </div>'+
          '</center>';
        }
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_employeereceipts_details.php",
        type: "post",
        data: {
          issearch:issearch,
          mrno:mrno
        },
        error: function(){}
      },
      "scrollCollapse": true,
      "paging"        : true,
      "fixedColumns"  : { "leftColumns" : 2 },
      // "autoWidth"     : false,
      // "rowReorder"    : {
      //   selector      : 'td:nth-child(2)'
      // },
      // "responsive"    : true,
      "createdRow": function( row, data, dataIndex ) {
        if ( data[12] > 0 ) {  //unit status
          $(row).addClass('gray');
        }
      }
    });

  };

  fetch_data_table3('no');
  function fetch_data_table3(issearch, rrno='', idrr=''){
    var table = $('#table3').DataTable({
      "lengthChange"  : true,
      "searching"     : true,
      // "select"        : true,
      "keys"          : true,
      "order"         : [ 0,"desc" ],
      "columnDefs"    : [{
        "targets"     : [ 0 ],
        "visible"     : false,
      },{
        "targets": [ 1 ],
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="Retrieve!" href="#" onclick="retreive_receipts('+row[0]+')">'+row[1]+'</a>';
        }
      },{
        "targets": [ 7,8,9 ],
        "className": "text-right",
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_empployeereceipts_retreiverr.php",
        type: "post",
        error: function(){}
      },
      "autoWidth"     : false,
      "rowReorder": {
        selector: 'td:nth-child(2)'
      },
      "responsive": true,
    });
  };

  $('.add').click(function(e){
    e.preventDefault();
    if(id != '') {
      $('#sidempreceipts').val(id);
      $('#smrnumber').val(mrnumber);

      $('#aempno_from').val($('#empno').val());
      $('#aempname_from').val($('#empname').val());
      $('#modal-addmrdetails_select').modal('show');
      // getRow(id);
    }
  });
  /*function save_details() {
    var idempreceipts     = $('#aidempreceipts').val();
    var mrnumber          = $('#amrnumber').val();

    var iditemindex       = $('#aiditemindex').val();
    var quantity          = $('#aquantity').val();
    var unitcost          = $('#aamount').val();
    var dateaquired       = $('#adateaquired').val();
    var serialnos         = $('#aserialnos').val();
    var idreceipts        = $('#aidreceipts').val();
    var idreceiptsdetails = $('#aidreceiptsdetails').val();

    var itemname          = $('#aitemname').val();
    var balqty            = $('#abalqty').val();

    if(Number(quantity)>0
      && Number(balqty) >= Number(quantity)) {
      $.ajax({
        url: 'datahelpers/mr_details_helper.php',
        type: 'post',
        dataType: 'json',
        data: {
          add:'add'
          iditemindex:iditemindex,
          quantity:quantity,
          unitcost:unitcost,
          idempreceipts:idempreceipts,
          mrnumber:mrnumber,
          dateaquired:dateaquired,
          serialnos:serialnos,
          idreceipts:idreceipts,
          idreceiptsdetails:idreceiptsdetails,
        },
        success: function (data) {
          msgAlert('success','Saved!','MR Successfully saved...');
        },
      });
    } else {
      alert('err: quantity');
    }
  }*/

  function edit_details(id) {
    if(id != '') {
      $.ajax({
        url: 'datahelpers/mr_details_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          if(data.active == 0) {
            if(data.idempreceiptdetails != '') {
              $('#eidempreceipts').val(id);
              $('#emrnumber').val(mrnumber);
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
              $('#eunitcost').val(data.unitcost);
              $('#emrstatus').val(data.mrstatus);
              $('#eunitstatus').val(data.unitstatus);

              $('#modal-editmrdetails').modal('show');

              $('#eempnofrom').val($('#empno').val());
              $('#eempnamefrom').val($('#empname').val());
              $('#prevmrnumber').val($('#mrnumber').val());
            }
          } else {
            // alert('This item has been salvage or transferred...');
          }

        }
      });
    }
  };

  function delete_details(id) {
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
            $('#modal-deletemrdetails').modal('show');
          }
        }
      });
    }
  };

  function retreive_receipts(idreceiptdetails){
    $('#tab2').show();
    $('#tab3').hide();
    $.ajax({
      url: 'datahelpers/mr_details_helper.php',
      type: 'post',
      dataType: 'json',
      data: { idreceiptdetails:idreceiptdetails },
      success: function (data) {
        $('#aidempreceipts').val(id);
        $('#amrnumber').val(mrnumber);

        $('#aidreceiptsdetails').val(data.idreceiptsdetails);
        $('#aidreceipts').val(data.idreceipts);
        $('#aiditemindex').val(data.iditemindex);

        $('#arrnumber').val(data.rrnumber);
        $('#adateaquired').val(data.receivedate);
        $('#aitemname').val(data.itemname);
        $('#abrandname').val(data.brandname);
        $('#amodel').val(data.model);
        $('#aserialnos').val(data.serialnos);
        $('#aspecs').val(data.specifications);

        $('#abalqty').val(data.quantity);
        $('#aquantity').val(data.quantity);
        $('#aunitcost').val(data.unitcost);

        $('#modal-addmrdetails').modal('show');
      },
      error: function(){
        alert('error');
      }
    });
  }

  $('.print').click(function(e){
    e.preventDefault();
    $('#modal-mrto').modal('show');
  });

  function print_harcopy() {
    $('#modal-mrto').modal('hide');
  //var dept = $("#pdeptto option:selected").text();
  var redirectWindow = window.open("printing/print_mr_hardcopy.php?"+"id="+id+"&mri=Employee", '_blank');
  redirectWindow.location;

  // window.location="printing/print_mr_hardcopy.php?"+"id="+id+"&mri=Employee";
};

$('.printsummary').click(function(e){
  e.preventDefault();
  var empno = $('#empno').val();
  var redirectWindow = window.open("printing/print_empmrsummary.php?"+"id="+empno+"&mri=Employee", '_blank');
  redirectWindow.location;
  // window.location="printing/print_empmrsummary.php?"+"id="+id+"&mri=Employee";
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

/*function uploadImage(e) {
  var fd    = new FormData();
  var files = $('#file')[0].files[0];
  fd.append('file',files);
  $.ajax({
    url: 'pdf/pdfhelper.php',
    type: 'post',
    data: fd,
    contentType: false,
    processData: false,
    success: function(response){
      if(response != 0){
          $('#filepath').val(response);
      } else {
        alert('file not uploaded');
      }
    }
  });
}*/
</script>


