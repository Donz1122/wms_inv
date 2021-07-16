<?php

include '../session.php';
include '../class/clsitems.php';
$userquery = $item->users();
?>

<input type="hidden" id="idissuance" value=""/>

<section class="content">
  <div class="row">
    <?php include '../includes/toastmsg.php'; ?>
    <div class="col-sm">
      <section class="content">
        <div class="card">
          <div class="card-body">
            <table id="table2" class="table table-bordered table-hover table-striped table-sm">
              <thead>
                <tr>
                  <th style="display: none;"></th>
                  <th>User Name</th>
                  <th>Position</th>
                  <th>Restriction</th>
                  <th>Area</th>
                  <th style="width: 60px;"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($userquery as $row):  ?>
                  <tr align="center">
                    <td style="display: none;"><?= $row['iduser'] ?></td>
                    <td align="left"><?= $row['username'] ?></td>
                    <td align="left"><?= $row['position'] ?></td>
                    <td align="left"><?= ucwords(strtolower($row['restriction'])) ?></td>
                    <td align="left"><?= $row['area'] ?></td>                    
                    <td>
                      <center>
                        <div class="btn-group">
                          <button class="btn btn-warning btn-xs" title="Edit" onclick="editUser('<?= $row['iduser'] ?>')">
                            <i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
                          </button>
                          <?php if ($_SESSION['restriction'] == 101) { ?>
                            <button class="btn btn-danger btn-xs" title="Delete" onclick="deleteUser('<?= $row['iduser'] ?>')">
                              <i class="fa fa-trash-alt"></i>
                            </button>
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

<script type="text/javascript">

    //$('.select2').select2();

    $(function(){
      $("#table1").DataTable({
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "columnDefs": [
        {
          "targets": [ 0 ],
          "visible": false
        }]
      });

      $('#table2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "columnDefs": [
        {
          "targets": [ 0 ],
          "visible": false
        }],
      });


      /*var table = $('#table2').DataTable();
      $('#table2 tbody').on( 'click', 'tr', function () {
        table.rows( '.selected' ).nodes().to$().removeClass( 'selected' );
        $(this).toggleClass('selected');
        var id = $('#table2').DataTable().rows('.selected').data()[0][0];
        LoadUserList(id)
      });*/
    });

    function LoadUserList(id) {
      $.ajax({
        url: 'datahelpers/user_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          document.getElementById("un").value = data.username;
        }
      });
    }

    $('.add').click(function(e){
      e.preventDefault();
      $('#modal-adduserinfo').modal('show');
    });

    function editUser(id) {
      $.ajax({
        url: 'datahelpers/user_helper.php',
        type: 'post', 
        dataType: 'json',
        data: {id:id},
        success: function(data){

          $('#modal-edituserinfo').modal('show');

          $('#e_iduser').val(data.iduser);
          $('#e_username').val(data.username);
          $('#e_password').val(data.password);
          $('#e_cpassword').val(data.password);
          //$('#e_fullname').val(data.fullname);
          $('#e_position').val(data.position);
          $('#e_doccode').val(data.doccode);
          $('#e_filepath').val(data.img);

          $("#e_fullname").select2().val(data.empno).trigger('change.select2');

          $("#e_restriction").select2().val(data.restriction).trigger('change.select2');
          $("#e_usertype").select2().val(data.usertype).trigger('change.select2');
          $("#e_area").select2().val(data.area).trigger('change.select2');

          $('#e_dummyimage').val(data.doccode);          

          image = document.getElementById('e_dummyimage');
          if(data.image != '') image.src = 'files/'+data.img;

        }
      });
    };

    function deleteUser(id) {
      $.ajax({
        url: 'datahelpers/user_helper.php',
        type: 'post',
        dataType: 'json',
        data: {id:id},
        success: function(data){
          if(Number(data.restriction) == 3){
            alert("Please contact website developer...");
          } else {
            $('#modal-deleteuserinfo').modal('show');

            $('#d_iduser').val(data.iduser);
            $('#d_username').html(data.username);
          }
        }
      });

    };

    function getRow(id){
    //clearForm();
    $.ajax({
      url: 'datahelpers/user_helper.php',
      type: 'post',
      dataType: 'json',
      data: {id:id},
      success: function(data){

        $('#d_iduser').val(data.iduser);
        $('#d_username').html(data.username);

        $('#e_iduser').val(data.iduser);
        $('#e_username').val(data.username);
        $('#e_password').val(data.password);
        $('#e_cpassword').val(data.password);
        $('#e_fullname').val(data.fullname);
        $('#e_position').val(data.position);
        $('#e_doccode').val(data.doccode);

        $("#e_restriction").select2().val(data.restriction).trigger('change.select2');
        $("#e_usertype").select2().val(data.usertype).trigger('change.select2');
        $("#e_area").select2().val(data.area).trigger('change.select2');

      }
    });
  }

  function clearForm() {
   $('#d_iduser').val('');
   $('#d_username').html('');

   $('#e_iduser').val('');
   $('#e_username').val('');
   $('#e_password').val('');
   $('#e_cpassword').val('');
   $('#e_fullname').val('');
   $('#e_position').val('');
   $('#e_doccode').val('');
 }

</script>