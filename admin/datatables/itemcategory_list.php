<?php
include '../session.php';
include '../class/clsitems.php';
include '../includes/alertmsg.php';
?>

<section class="content">
  <div class="row">
    <div class="col">
      <div class="card card-warning card-outline">
        <div class="card-body">
          <table id="table3" class="table table-nowrap table-hover table-striped table-sm">
            <thead>
              <tr>
                <th style="display: none;"></th>
                <th>Category</th>
                <th width="90px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $category = $item->categories();
              foreach($category as $row): ?>
                <tr>
                  <td style="display: none;"><?= $row['iditemcategory'] ?></td>
                  <td><?= $row['category'] ?></td>
                  <td>
                    <center>
                      <div class="btn-group">
                        <button class="btn btn-warning btn-xs" title="Edit" onclick="editCategory('<?= $row['iditemcategory']?>')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
                        </button>
                        <button class="btn btn-danger btn-xs mr-1" title="Edit" onclick="delCategory('<?= $row['iditemcategory']?>')"><i class="fa fa-trash-alt"></i>
                        </button>
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
  </div>
</section>


<script>

 $(document).ready(function() {

   var dataTable = $('#table3').DataTable( {
    "paging": true,
      //"lengthChange": false,
      //"searching": false,
      //"ordering": true,
      "order"         : [ 1,"asc" ],
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      }],
      "processing": true,
    });

 });

 function editCategory(id) {
  if(id != '')  {
    $('#modal-edititemcategory').modal('show');
    getRow(id);
  }
}

function delCategory(id) {
  if(id != '') {
    $('#modal-deleteitemcategory').modal('show');
    getRow(id);
  }
}

function getRow(id){
  $.ajax({
    url: 'datahelpers/itemcategory_helper.php',
    type: 'post',
    dataType: 'json',
    data: { id:id },
    success: function(data){
      $('#eiditemcategory').val(id);
      //$('#category').val(data.category);
      $('#ecategory').val(data.category);

      $('#diditemcategory').val(id);
      $('#dcategory').html(data.purpose);

    }
  });
}





</script>

