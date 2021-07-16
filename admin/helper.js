// $(function () {
//   $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
//   $('[data-mask]').inputmask();
// });


$('.select2').select2();

// var curCharLenght = 0;
// var floatOptions = {
//   onKeyPress: function(cur, e, currentField, floatOptions) {
//     var mask = createMask(cur);
//     var field = currentField
//     .parent()
//     .find(".input-float[data-field=" + currentField.attr("data-field") + "]");
//     if (cur.length - curCharLenght < 0 && cur.indexOf(".") == -1) {
//       field.mask(mask + " 000", floatOptions);
//       curCharLenght = cur.length;
//     } else if (event.data == "," || event.data == ".") {
//       curCharLenght = mask.length + 1;
//       mask += ".0000";
//       field.mask(mask, floatOptions);
//     } else {
//       if (cur.indexOf(".") == -1) {
//         mask = mask + " 000.0000";
//         field.mask(mask, floatOptions);
//         if (isNaN(e.originalEvent.data) || e.originalEvent.data == " ") {
//           field.val(field.val().slice(0, -1));
//         }
//       }
//       curCharLenght = cur.length;
//     }
//   }
// };

// function createMask(val) {
//   var mask = "";
//   var num = val.split(".")[0];
//   num = num.replace(/ /g, "");
//   for (var i = 1; i <= num.length; i++) {
//     mask += "0";
//     if ((num.length - i) % 3 === 0 && i != num.length) {
//       mask += " ";
//     }
//   }
//   return mask;
// }

// $(".input-float").each(function(index, el) {
//   var item = $(this);
//   item.attr("data-field", "field-" + index);

//   var mask = createMask(item.val());
//   if (item.val().indexOf(".") !== -1) {
//     var splitedVal = item.val().split(".");
//     if (splitedVal.length > 1 && splitedVal[1].length > 2) {
//       if (splitedVal[1].length == 3) {
//         mask += ".000";
//       } else {
//         mask += ".0000";
//       }
//     } else {
//       mask += ".00";
//     }
//   }

//   item.mask(mask, floatOptions);
// });

// $('.inputs').keydown(function (e) {
//   if (e.which === 13) {
//     var index = $('.inputs').index(this) + 1;
//     $('.inputs').eq(index).focus();
//   }
// });
/*
receipt details

insert into tbl_receiptsdetails(iditemindex, itemcode, rrnumber, quantity, unitcost, iduser)
select b.iditemindex, sccode, scdocument, screceipts, screceiptscost, 11  from zanecoinv.stockcard a
  inner join tbl_itemindex b on b.itemcode = a.sccode
 where scdocument like 'rr%';

mr receipts
insert into tbl_empreceiptdetails (iditemindex, itemcode, specification, quantity, unitcost, mrnumber, iduser)
select iditemindex, a.itemcode, description, quantity, amount, ref, iduser from zanecoinv.erdetail a
  inner join tbl_itemindex b on b.iditemindex = a.iditemindex;


$(function() {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });


  /*$('.toastrDefaultSuccess') {
    toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
  };
  $('.toastrDefaultInfo').click(function() {
    toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
  });
  $('.toastrDefaultError').click(function() {
    toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
  });
  $('.toastrDefaultWarning').click(function() {
    toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
  });*/



/*
  global function
  */

  /*$(document).ready(function($){
    var url = window.location.href;
    $('.nav li a[href="'+url+'"]').addClass('active');
  });*/

//---------------------------------------------------------------------------
// start items
//---------------------------------------------------------------------------



//---------------------------------------------------------------------------
// end items
//---------------------------------------------------------------------------


//---------------------------------------------------------------------------
// start itemindex
//---------------------------------------------------------------------------

// load itemindex
/*function load_itemindexlist(){
  $.ajax({
    url: 'itemindex_list.php',
    success: function (data) {
      $('#itemindex-list').html(data);
    },
    error: function(){
      alert('Error: itemindex List');
    }
  });
}load_itemindexlist();

function search_itemindex()  {
  var search = $('#search').val();
  $.ajax({
    url: 'itemindex_list.php',
    type: 'post',
    data: { search:search },
    success: function (data) {
      $('#itemindex-list').html(data);
    },
    error: function(){
    }
  });
}*/

//---------------------------------------------------------------------------
// end items
//---------------------------------------------------------------------------



//---------------------------------------------------------------------------
// for receipts
//---------------------------------------------------------------------------

// load receipts
/*function load_receiptslist(){
  $.ajax({
    url: 'receipts_list.php',
    success: function (data) {
      $('#receipts-list').html(data);
    },
    error: function(){
      alert('Error: Receipts List');
    }
  });
}load_receiptslist();


function date_filter_receipts()  {
  var curdate = $('#curdate').val();
  $.ajax({
    url: 'receipts_list.php',
    type: 'post',
    data: {
      curdate:curdate
    },
    success: function (data) {
      $('#receipts-list').html(data);
    },
    error: function(){
    }
  });
}

function search_receipts()  {
  var search = $('#search').val();
  $.ajax({
    url: 'receipts_list.php',
    type: 'post',
    data: {
      search:search
    },
    success: function (data) {
      $('#receipts-list').html(data);
    },
    error: function(){
    }
  });
}  */


//date filter receipts



//---------------------------------------------------------------------------
//  end receipts
//---------------------------------------------------------------------------


//---------------------------------------------------------------------------
// for receipt details
//---------------------------------------------------------------------------

// load receipts details
/*function load_receiptdetailslist(){
  var e = $('#idreceipts').val();
  alert('e');
  alert(e);
  $.ajax({
    url: 'receiptdetails_list.php',
    type: 'post',
    data: { e:e },
    success: function (data) {
    alert(e+':'+data);
      $('#receiptdetails-list').html(data);
    },
    error: function(){
      alert('Error: Receipts List');
    }
  });
}load_receiptdetailslist();*/

//date filter receipts


//---------------------------------------------------------------------------
//  end receipt details
//---------------------------------------------------------------------------


//---------------------------------------------------------------------------
// for issuance
//---------------------------------------------------------------------------

/* load issuance
function load_issuancelist(){
  $.ajax({
    url: 'issuance_list.php',
    type: 'post',
    success: function (data) {
      $('#issuance-list').html(data);
    },
    error: function(){
      alert('Error: Issuance List');
    }
  });
}load_issuancelist();

// modal issuance
function prepareIssuanceNumber(x)  {
  $('#idissuance').val('');
  $('#inumber').val('');
  $('#purpose').val('');
  $('#amount').val('0.00');
  $('#jono').val('');
  var y = document.getElementById("area");
  $.ajax({
    url: 'issuance_helper.php',
    dataType: 'json',
    type: 'post',
    data: { x:x },
    success: function (data) {
      if(x == 'mct') {
        y.style.display = 'none';
        $('#addnew').find('.modal-title').text('Material Charge Ticket');
        $('#addnew').find('.ilabel').text('MCT No.');
            //$('#addnew').modal('show');
            $('#inumber').val(data.num);
            $('#rvno').val(data.rvno);
          } else {
            y.style.display = '';
            $('#addnew').find('.modal-title').text('Material Transfer Ticket');
            $('#addnew').find('.ilabel').text('MTT No.');
            //$('#addnew').modal('show');
            $('#inumber').val(data.num);
            $('#rvno').val('');
          }
        },
        error: function(){
        }
      });
}

//date filter issuance
function date_filter_issuance()  {
  var startdate = $('#startdate').val();
  var enddate = $('#enddate').val();
  $.ajax({
    url: 'issuance_list.php',
    type: 'post',
    data: {
      startdate:startdate,
      enddate:enddate
    },
    success: function (data) {
      $('#issuance-list').html(data);
    },
    error: function(){
    }
  });
}
//---------------------------------------------------------------------------
// end issuace
//---------------------------------------------------------------------------

//---------------------------------------------------------------------------
// issuance details
//---------------------------------------------------------------------------

// load issuance
function load_issuancedetailslist(){
  var e = $('#inumber').val();
  $.ajax({
    url: 'issuancedetails_list.php',
    type: 'post',
    data: { e:e },
    success: function (data) {
      $('#issuancedetails-list').html(data);
    },
    error: function(){
      alert('Error: Issuance List');
    }
  });
}load_issuancedetailslist();

//---------------------------------------------------------------------------
// end issuance details
//---------------------------------------------------------------------------

//---------------------------------------------------------------------------
// supplier
//---------------------------------------------------------------------------
/*function load_supplierslist(){
  $.ajax({
    url: 'suppliers_list.php',
    type: 'post',
    success: function (data) {
      $('#suppliers-list').html(data);
    },
    error: function(){
      alert('Error: load_supplierslist');
    }
  });
}load_supplierslist();
/*
      function search_suppliers()  {
        var search = $('#searchs').val();
          $.ajax({
              url: 'suppliers_list.php',
              type: 'post',
              data: {
                search:search
              },
              success: function (data) {
                $('#suppliers-list').html(data);
              },
              error: function(){
              }
            });
        }
        */

//---------------------------------------------------------------------------
// end supplier
//---------------------------------------------------------------------------



//---------------------------------------------------------------------------
// start memo receipts
//---------------------------------------------------------------------------



// details





//---------------------------------------------------------------------------
// end memo receipts
//---------------------------------------------------------------------------


//---------------------------------------------------------------------------
// start pole
//---------------------------------------------------------------------------

$('.select2').select2();

$(document).ready(function() {

  var x = document.getElementById("sb_otherinfo");
  var y = document.getElementById("sb_otherinfo_b");
  var z = document.getElementById("sb_poles");
  x.className = 'nav-item has-treeview menu-open';
  y.className = 'nav-link active';
  z.className = 'nav-link active';

});

function load_poles(){
  $.ajax({
    url: 'datatables/poles_list.php',
    type: 'post',
    success: function (data) {
      $('#load_poles-list').html(data);
    },
    error: function(){
      alert('Error: load_poles List');
    }
  });
}load_poles();

$('.select2').select2();
$('#address').editableSelect({ effects: 'fade' });
$('#category').editableSelect({ effects: 'fade' });
$('#length').editableSelect({ effects: 'fade' });

function save_poles(e) {
  var add           = "save";
  var poleno        = $('#poleno').val();
  var category      = $('#category').val();
  var poletype      = $('#poletype').val();
  var address       = $('#address').val();
  var street        = $('#street').val();
  var latitude      = $('#lat').val();
  var longitude     = $('#long').val();
  var length        = $('#length').val();

  $.ajax({
    url: 'datahelpers/poles_helper.php',
    type: 'post',
    dataType: 'json',
    data: {
      add:add,
      poleno:poleno,
      category:category,
      poletype:poletype,
      address:address,
      street:street,
      latitude:latitude,
      longitude:longitude,
      length:length,
    },
    success: function(data){
      $('#add').modal('hide');
      if(e == 'a') {
        window.location = "poles.php";
      } else {
        window.location = "polesdetails.php?id=" + data.last_id + "&pole=" + data.last_id;
      }
    },
    error: function(data){
      alert('error!');
    },
  });

}

//---------------------------------------------------------------------------
// start pole details
//---------------------------------------------------------------------------

$('.select2').select2();
$('[data-mask]').inputmask();

function load_poledetails() {
  var table1 = $('#table1').DataTable( {
    "paging"    : true,
    "ordering"  : true,
    "info"      : true,
    "autoWidth" : false,
    "responsive": true,
    "columnDefs": [
    {
      "targets" : [ 0 ],"visible" : false,
    },{
      "targets" : [ 8 ],"orderable"  : false,
    },{
      "targets"  : [ 5,7 ],"className": "text-right",
    },{
      "targets": [ 8 ],
      "render": function ( data, type, row ) {
        return '<center>'+
        '<div class="btn-group">'+
        ' <button class="btn btn-warning btn-xs" title="Edit" onclick="edit_pole_details('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
        '   <?php if($_SESSION["restriction"] == 101) { ?>'+
        '     <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_pole_details('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
        '   <?php } ?>'
        ' </div>'+
        '</center>';
      }
    }
    ],
    "processing": true,
    "serverSide": true,
    "ajax"      :
    {
      url       : "serverside/ss_poledetails.php",
      type      : "post",
      data      : { poleno:poleno },
      error     : function() { }
    },
  });
}load_poledetails();

function load_polehistory() {
  var table2 = $('#table2').DataTable( {
    "paging"    : true,
    "ordering"  : true,
    "info"      : true,
    "autoWidth" : false,
    "responsive": true,
    "columnDefs": [  ],
    "processing": true,
  } );
}load_polehistory();

function select_pole_itemindex() {
  var selectitemid = document.getElementById("iditemindex").value;

  $.ajax({
    url     : 'datahelpers/receiptdetails_helper.php',
    type    : 'post',
    dataType: 'json',
    data: { selectitemid:selectitemid },
    success: function (data) {
      $("#itemcode").val(data.itemcode);
      $("#unit").val(data.unit);
    },
    error: function(){}
  });
}

function save_pole_details() {

  var add           = "save";
  var itemcode      = $('#itemcode').val();
  var description   = $('#description').val();
  var specs         = $('#specs').val();
  var qty           = $('#qty').val();
  var unitcost      = $('#unitcost').val();

  $.ajax({
    url: 'datahelpers/poledetails_helper.php',
    type: 'post',
    dataType: 'json',
    data: {
      add:add,
      poleno:poleno,
      itemcode:itemcode,
      description:description,
      specs:specs,
      qty:qty,
      unitcost:unitcost,
    },
    success: function(data){
      $('#add_details').modal('hide');
      reload_poledetails();
      msg('Pole Details','Record Successfully saved!');
    },
    error: function(data){
      alert('error!');
    },
  });
}

function reload_poledetails(e) {
  $('#table1').DataTable().destroy();
  load_poledetails();
};

function msg(header,remarks){
  $(document).Toasts('create', {
    class    : 'bg-success',
    title    : '<span style="font-size: 12px;"> '+ header +' </span>',
    position : 'topRight',
    icon     : 'fas fa-envelope fa-lg',
    body     : '<span style="font-size:13px;">'+ remarks +'<span>',
  })
};

function edit_pole_details(id) {
  $.ajax({
    url     : 'datahelpers/poledetails_helper.php',
    type    : 'post',
    dataType: 'json',
    data    : { id },
    success: function (data) {
      $('#idpoledetails').val(data.idpoledetails);
      $('#eitemname').val(data.itemname);

      $('#edescription').val(data.description);
      $('#especs').val(data.specs);
      $('#eqty').val(data.qty);
      $('#eunit').val(data.unit);
      $('#eunitcost').val(data.unitcost);

      $('#edit_details').modal('show');

    },
    error: function(){}
  });
}
function update_pole_details() {

  var edit          = $('#idpoledetails').val();
  var description   = $('#edescription').val();
  var specs         = $('#especs').val();
  var qty           = $('#eqty').val();
  var unitcost      = $('#eunitcost').val();

  $.ajax({
    url: 'datahelpers/poledetails_helper.php',
    type: 'post',
    dataType: 'json',
    data: {
      edit:edit,
      description:description,
      specs:specs,
      qty:qty,
      unitcost:unitcost,
    },
    success: function(data){
      $('#edit_details').modal('hide');
      reload_poledetails();
      msg('Pole Details','Record Successfully updated!');
    },
    error: function(data){
      alert('error!');
    },
  });
}

function delete_pole_details(id){
  var ok =confirm("Are you sure to delete this item?");
  if (ok == true) {
    $.ajax({
      url       : 'datahelpers/poledetails_helper.php',
      type      : 'post',
      dataType  : 'json',
      data      : { delete:id },
      success   : function(data){
        reload_poledetails();
        msg('Pole Details','Record Successfully deleted!');
      }
    });
  }
};





//---------------------------------------------------------------------------
// end pole details
//---------------------------------------------------------------------------



//---------------------------------------------------------------------------
// start warehouse details
//---------------------------------------------------------------------------
// function load_warehouse_details() {
//   var dataTable = $('#table2').DataTable( {
//     "paging"    : true,
//     "ordering"  : true,
//     "info"      : true,
//     "rowReorder": {
//       "selector"  : 'td:nth-child(2)'
//     },
//     "responsive": true,
//     "autoWidth" : false,
//     "processing": true,
//     "columnDefs": [
//     {
//       "targets" : [ 0 ],
//       "visible" : false,
//     }],
//   } );
// }load_warehouse_details();



//---------------------------------------------------------------------------
// end warehouse details
//---------------------------------------------------------------------------

