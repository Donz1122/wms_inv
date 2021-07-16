
<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/alertmsg.php';
$id     = $_GET['id'];
$poleno = $_GET['pole'];
$query  = $db->query("select * from tbl_poles where idpoles = '$id' ")->fetch_assoc();


?>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <input type="hidden" id="idsalvage" name="idsalvage" value="<?= $idsalvage ?>">
    <div class="content-wrapper">
      <div class="content-header">
        <!-- <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6"><h4><i style="font-size: 18px;"> Pole Details</i></h4></div>
            <div class="ml-auto">
              <a href="poles.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
            </div>
          </div>
        </div> -->
        <section class="content">
          <div class="row">
           <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Details</h3>
              </div>

              <div class="card-body box-profile">
                <strong> Pole No.</strong>
                <p class="text-muted float-right"><?= $query['poleno'] ?></p>
                <hr>
                <strong> Category</strong>
                <p class="text-muted float-right"><?= $query['category'] ?></p>
                <hr>
                <strong> Street</strong>
                <p class="text-muted"><?= $query['street'] ?></p>
                <hr>
                <strong> Address</strong>
                <p class="text-muted"><?= $query['address'] ?></p>
                <hr>
                <strong> Latitude</strong>
                <p class="text-muted float-right"><?= $query['latitude'] ?></p>
                <hr>
                <strong> Longitude</strong>
                <p class="text-muted float-right"><?= $query['longitude'] ?></p>
                <hr>
                <strong> Length</strong>
                <p class="text-muted float-right"><?= $query['length'] ?></p>
              </div>

            </div>
          </div>





          <div class="col">
            <div class="col-12 col-sm-12">
              <div class="col mb-1" style="margin-left: -7px">
                <a href="#add_details" class="btn btn-info btn-sm" data-toggle="modal" title="New Item Index" style="height: 31px;" >
                  <i class="fa fa-plus-circle mr-1"></i> Add
                </a>
                <a href="poles.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
              </div>
            </div>
            <div class="col-12 col-sm-12">
              <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-one-dressing-tab" data-toggle="pill" href="#custom-tabs-one-dressing" role="tab" aria-controls="custom-tabs-one-dressing" aria-selected="true">Pole Dressing</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-history-tab" data-toggle="pill" href="#custom-tabs-one-history" role="tab" aria-controls="custom-tabs-one-history" aria-selected="false">Dressing History</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body ">
                  <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-dressing" role="tabpanel" aria-labelledby="custom-tabs-one-dressing-tab">
                      <hr>
                      <table id="table1" class="table table-sm text-nowrap ">
                        <thead>
                          <tr>
                            <th>id</th>
                            <th width="100px">Item Code</th>
                            <th width="200px">Item Name</th>
                            <th>Description</th>
                            <th>Specs</th>
                            <th style="text-align: right; width: 40px;">Qty</th>
                            <th>Unit</th>
                            <th style="text-align: right; width: 40px;">Unit Cost</th>
                            <th></th>
                          </tr>
                        </thead>
                      </table>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-history" role="tabpanel" aria-labelledby="custom-tabs-one-history-tab">
                     <table id="table2" class="table table-sm text-nowrap">
                      <thead>
                        <tr>
                          <th width="100px">Transaction Date</th>
                          <th width="100px">Item Code</th>
                          <th width="200px">Item Name</th>
                          <th style="text-align: right; width: 40px;">Qty</th>
                          <th>Unit</th>
                          <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $tab2   = $db->query("
                          select p.poleno, requestdate, ii.itemcode, itemname, quantity, unit, tag_remarks from tbl_poles p
                          left join tbl_issuancedetails id on id.tag_no = p.poleno and approved = 1
                          left join tbl_itemindex ii on ii.iditemindex = id.iditemindex
                          where poleno = '$poleno'; ");
                          foreach($tab2 as $row): ?>
                            <tr align="center">
                              <td><?= date('M d, Y', strtotime($row['requestdate'])) ?></td>
                              <td align="left"><?= $row['itemcode']; ?></td>
                              <td align="left"><?= $row['itemname']; ?></td>
                              <td align="right"><?= $row['quantity']; ?></td>
                              <td align="left"><?= $row['unit']; ?></td>
                              <td align="left"><?= $row['tag_remarks']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card-footer">

                </div>
              </div>
            </div>
          </div>


        </div>
      </section>
    </div>
  </div>

  <?php include 'includes/datatable-footer.php'?>

  <?php

  include 'class/clsitems.php';
  $itemname = $item->itemindex();

  ?>

  <div class="modal fade" id="add_details">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Material Receipts Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="itemcode" name="itemcode" readonly placeholder="Item Code" >
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-12 col-md-10">
              <select onchange="select_pole_itemindex('a')" class="form-control select2" id="iditemindex" name="iditemindex" >
                <?php foreach($itemname as $row): ?>
                  <option value="<?= $row['iditemindex']; ?>"><?= $row['itemname'] .' - '.$row['itemcode']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Description</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="description" name="description" rows="2" style="resize: none;" placeholder="Description" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Specification</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="specs" name="specs" rows="2" style="resize: none;" placeholder="Specification" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="unit" name="unit" readonly >
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-4">
              <input type="numeric" class="form-control form-control-sm" id="qty" name="qty" required placeholder="0" data-inputmask-alias="numeric" data-inputmask-inputformat="999999999.99" data-mask>
            </div>

            <label class="col-form-label col-sm-2" style="text-align: right;">Unit Cost</label>
            <div class="col">
              <input type="decimal" class="form-control form-control-sm" id="unitcost" name="unitcost" required placeholder="0.00" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_pole_details()"> Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="edit_details">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Material Receipts Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control form-control-sm" id="idpoledetails" name="idpoledetails" readonly >
          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-12 col-md-10">
              <input type="text" class="form-control form-control-sm" id="eitemname" name="eitemname" readonly >
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Description</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="edescription" name="edescription" rows="2" style="resize: none;" placeholder="Description" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Specification</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="especs" name="especs" rows="2" style="resize: none;" placeholder="Specification" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="eunit" name="eunit" readonly >
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-4">
              <input type="numeric" class="form-control form-control-sm" id="eqty" name="eqty" required placeholder="0" data-inputmask-alias="numeric" data-inputmask-inputformat="999999999.99" data-mask>
            </div>

            <label class="col-form-label col-sm-2" style="text-align: right;">Unit Cost</label>
            <div class="col">
              <input type="decimal" class="form-control form-control-sm" id="eunitcost" name="eunitcost" required placeholder="0.00" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info" data-dismiss="static" onclick="update_pole_details()"> Update </button>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript"> var poleno = '<?= $query['poleno'] ?>'; </script>
  <script type="text/javascript" src="helper.js"></script>
</body>
</html>
