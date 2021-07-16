<?php
include "class/clsitems.php";
$employee = $item->employee();
?>
<!-- Add -->
<div class="modal fade" id="modal-adduserinfo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="">
        <input type="hidden" id="filepath" name="filepath"/>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <img class="img-fluid ml-2" src="files/avatar04.png" id="dummyimage">
                <div id="userimage"></div>
              </div>
              <div class="row mb-2">
                <div class="btn-group">
                  <div class="btn btn-default btn-sm btn-file btn-append ml-2">
                    <i class="fas fa-paperclip"></i>
                    <input type="file" id="file" name="file" single onchange="change_image('a')" enctype="multipart/form-data"> Browse</input> 
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Username</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm inputs" id="a_username" name="a_username" required>
                </div>
              </div>
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Password</label>
                <div class="col">
                  <input type="Password" class="form-control form-control-sm inputs" id="a_password" name="a_password">
                </div>
              </div>
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col">
                  <input type="Password" class="form-control form-control-sm inputs" id="a_cpassword" name="a_cpassword">
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Fullname</label>
            <div class="col">
              <input type="hidden" class="form-control form-control-sm" id="a_empno" name="a_empno">
              <select onchange="selectUser('a')" class="form-control form-control-sm inputs select2" id="a_fullname" name="a_fullname" >
                <?php foreach($employee as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= utf8_decode($row['empname']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Position</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm inputs" id="a_position" name="a_position">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Restriction</label>
            <div class="col-sm-3">
              <select class="form-control form-control-sm select2 inputs" id="a_restriction" name="a_restriction" >
                <option value="0" selected>Administrator</option>
                <option value="1">Stock Clerk</option>
                <option value="2">Field Officer/Requister</option>
              </select>
            </div>

            <label class="col-sm-2 col-form-label" style="text-align: right;">Doc. Code</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm inputs" id="a_doccode" name="a_doccode" style="text-transform: uppercase;">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">User Type</label>
            <div class="col-sm-3">
              <select class="form-control form-control-sm select2 inputs" id="a_usertype" name="a_usertype" >
                <option value="0" selected>Materials</option>
                <option value="1">Office</option>
              </select>
            </div>
            <label class="col-sm-2 col-form-label" style="text-align: right;">Area</label>
            <div class="col-sm-4">
              <select class="form-control form-control-sm select2 inputs" id="a_area" name="a_area" >
                <option value="DMO" selected>Dipolog Main Office</option>
                <option value="PAS"><?= utf8_decode('Piñan Area Services') ?></option>
                <option value="SAS">Sindangan Area Services</option>
                <option value="LAS">Liloy Area Services</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info save inputs mr-1" data-dismiss="static" onclick="saveUserInfo()"> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modal-edituserinfo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modify User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="">
        <div class="modal-body">
          <input type="hidden" id="e_filepath" name="e_filepath"/>
          <input type="hidden" class="form-control form-control-sm" id="e_iduser" name="e_iduser">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <img class="img-fluid ml-2" src="files/index2.jpg" id="e_dummyimage">
                <div id="e_userimage"></div>
              </div>
              <div class="row mb-2">
                <div class="btn-group">
                  <div class="btn btn-default btn-sm btn-file btn-append ml-2">
                    <i class="fas fa-paperclip"></i>
                    <input type="file" id="e_file" name="e_file" single onchange="change_image('e')" enctype="multipart/form-data"> Browse</input>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Username</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm inputs" id="e_username" name="e_username" required>
                </div>
              </div>
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Password</label>
                <div class="col">
                  <input type="Password" class="form-control form-control-sm inputs" id="e_password" name="e_password">
                </div>
              </div>
              <div class="form-group row ml-2">
                <label class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col">
                  <input type="Password" class="form-control form-control-sm inputs" id="e_cpassword" name="e_cpassword">
                </div>
              </div>
            </div>
          </div>


          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Fullname</label>
            <div class="col">
              <input type="hidden" class="form-control form-control-sm" id="e_empno" name="e_empno">
              <select onchange="selectUser('e')" class="form-control form-control-sm inputs select2" id="e_fullname" name="e_fullname" >
                <?php foreach($employee as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= utf8_decode($row['empname']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Position</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm inputs" id="e_position" name="e_position">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Restriction</label>
            <div class="col-sm-3">
              <select class="form-control form-control-sm select2 inputs" id="e_restriction" name="e_restriction" >
                <option value="0" selected>Administrator</option>
                <option value="1">Stock Clerk</option>
                <option value="2">Guest</option>
              </select>
            </div>
            <label class="col-sm-2 col-form-label" style="text-align: right;">Doc. Code</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm inputs" id="e_doccode" name="e_doccode">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">User Type</label>
            <div class="col-sm-3">
              <select class="form-control form-control-sm inputs select2" id="e_usertype" name="e_usertype" >
                <option value="0" selected>Materials</option>
                <option value="1">Office</option>
              </select>
            </div>
            <label class="col-sm-2 col-form-label" style="text-align: right;">Area</label>
            <div class="col-sm-4">
              <select class="form-control form-control-sm inputs select2" id="e_area" name="e_area" >
                <option value="" selected>-select-</option>
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
          <button type="button" class="btn btn-warning update" data-dismiss="static" onclick="updateUserInfo()"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="modal-deleteuserinfo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">Delete!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/user_helper.php">
          <input type="hidden" id="d_iduser" name="d_iduser">
          <p>Are you sure to delete this record?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt faa-ring animated-hover"></i> Delete</button>
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

  function selectUser(id) {
    if(id == 'a') {
      var empno = $('#a_fullname').val();
      $('#a_empno').val(empno);
      selectUserRows(id, empno);
    }
    if(id == 'e') {
      var empno = $('#e_fullname').val();
      $('#e_empno').val(empno);
      selectUserRows(id, empno);
    }
  }

  function change_image(e) {    
    var fd = new FormData();
    var files = '';
    var image = '';
    if (e == 'a') {
      files = $('#file')[0].files[0];
      image = document.getElementById('dummyimage');
      fd.append('file',files);
    } else {
      files = $('#e_file')[0].files[0];
      image = document.getElementById('e_dummyimage');
      fd.append('file',files);
    }
    $.ajax({
      url: 'pdf/pdfhelper.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,    
      success: function(response){        
        if(response != 0){
          if (e == 'a') {
            image.src = 'files/'+response
            $('#filepath').val(response);
          } else {
            image.src = 'files/'+response;
            $('#e_filepath').val(response);
          }
        } else {
          alert('file not uploaded');
        }
      }
    });
  }

  function selectUserRows(id, empno) {
    $.ajax({
      url: 'datahelpers/record_finder_helper.php',
      type: 'post',
      dataType: 'json',
      data: { empno:empno },
      success: function(data){
        if(id == 'a') {
          $('#a_empno').val(data.empno);
          $('#a_position').val(data.title);
        } else if(id == 'e') {
          $('#e_empno').val(data.empno);
          $('#e_position').val(data.title);
        }
      }
    });
  }

  function saveUserInfo() {
    var add         = "save";
    var username    = $('#a_username').val();
    var password    = $('#a_password').val();
    var cpassword   = $('#a_cpassword').val();
    var fullname    = $('#a_fullname option:selected').html();
    var position    = $('#a_position').val();
    var restriction = $('#a_restriction').val();
    var doccode     = $('#a_doccode').val();
    var usertype    = $('#a_usertype').val();
    var area        = $('#a_area').val();
    var empno       = $('#a_empno').val();
    var img         = $('#filepath').val();
    if(password === cpassword) {
      $.ajax({
        url: 'datahelpers/user_helper.php',
        type: 'post',           
        data: {
          add:add,
          username:username,
          password:password,
          fullname:fullname,
          position:position,
          restriction:restriction,
          doccode:doccode,
          usertype:usertype,
          area:area,
          empno:empno,
          img:img,
        },
        success: function(data){          
          $('#modal-adduserinfo').modal('hide');
          window.location="useraccount.php";
        },        
      });
    } else {
      alert('Password mismatch...');
    }
  };

  function updateUserInfo() {

    var edit        = "update";
    var iduser      = $('#e_iduser').val();
    var username    = $('#e_username').val();
    var password    = $('#e_password').val();
    var cpassword   = $('#e_cpassword').val();
    var fullname    = $('#e_fullname option:selected').html();
    var position    = $('#e_position').val();
    var restriction = $('#e_restriction').val();
    var doccode     = $('#e_doccode').val();
    var usertype    = $('#e_usertype').val();
    var area        = $('#e_area').val();
    var empno       = $('#e_empno').val();
    var img         = $('#e_filepath').val();  
    if(password === cpassword) {
      $.ajax({
        url: 'datahelpers/user_helper.php',
        type: 'post',      
        data: {
          edit:edit,
          iduser:iduser,
          username:username,
          password:password,
          fullname:fullname,
          position:position,
          restriction:restriction,
          doccode:doccode,
          usertype:usertype,
          area:area,
          empno:empno,
          img:img,
        },
        success: function(data){
          $('#modal-edituserinfo').modal('hide');
          window.location="useraccount.php";
        },
        error: function(){
          alert('error');
        }
      });

    } else {
      alert('Password mismatch...');
    }
  };



</script>