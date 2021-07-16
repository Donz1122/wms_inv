<?php
include 'class/clsitems.php';
$id 				= $db->query("select if(max(iditemindex) is null,0,max(iditemindex))+1 as cnt from tbl_itemindex order by iditemindex desc limit 1")->fetch_assoc();
$category 	= $item->categories();
$units 			= $item->units();

$newitemcode= $mos.$year.$id['cnt'];

?>
<!-- Add -->
<div class="modal fade" id="modal-addnew">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">New Item Index</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<form class="form-horizontal" method="post" action="datahelpers/itemindex_helper.php" id="itemindex-form">
				<input type="hidden" id="x_itemcode" name="x_itemcode" value="<?= $newitemcode ?>" />
				<input type="hidden" id="x_area" name="x_area"  />
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-2" >Item Code</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="aitemcode" name="aitemcode" readonly="readonly" value="<?= $newitemcode ?>" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Item Name</label>
						<div class="col-sm-10">
							<input class="form-control form-control-sm" type="text" id="itemname" name="itemname" placeholder="Item Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Category</label>
						<div class="col-sm-4">
							<select onchange="selectedCategory()" class="form-control form-control-sm select2" id="category" name="category" placeholder="Category">
								<?php foreach($category as $row): ?>
									<option value="<?= ucwords(strtolower(trim($row['category']))); ?>"><?= ucwords(strtolower(trim($row['category']))); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<label class="col-form-label col-sm-2" style="text-align: right;">Unit</label>
						<div class="col-sm-4">
							<select class="form-control form-control-sm" id="unit" name="unit" >
								<?php foreach($units as $row): ?>
									<option value="<?= ucwords(strtolower(trim($row['unit']))); ?>"><?= ucwords(strtolower(trim($row['unit']))); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Type</label>
						<div class="col-sm-4">
							<select onchange="select_type()" class="form-control select2" id="consumable" name="consumable" >
								<option value="0" selected>Non-Consumable</option>
								<option value="1">Consumable</option>
							</select>
						</div>
						<label class="col-form-label col-sm-2" style="text-align: right;">Depreciation</label>
						<div class="col-sm-4">
							<input type="numeric" class="form-control form-control-sm" id="adepyear" name="adepyear" value="0"
							data-inputmask-alias="numeric" data-inputmask-inputformat="999" data-mask/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2" >Reorder Point</label>
						<div class="col-sm-4">
							<input type="numeric" class="form-control form-control-sm" id="reorderpoint" name="reorderpoint" value="0"
							data-inputmask-alias="numeric" data-inputmask-inputformat="999" data-mask/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Acct. Code-In</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="acct_in" name="acct_in" />
						</div>

						<label class="col-form-label col-sm-2" style="text-align: right;">Acct. Code-Out</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="acct_out" name="acct_out" />
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-left">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-info" name="add"> Save</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!--Edit-->
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Modify Item Index</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<form class="form-horizontal" method="post" action="datahelpers/itemindex_helper.php" id="itemindex-form">
				<input type="hidden" id="xe_itemindex" name="xe_itemindex"  />
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Area</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="e_area" name="e_area" readonly="readonly" />
						</div>

						<label class="col-form-label col-sm-2" style="text-align: right;">Item Code</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="e_itemcode" name="e_itemcode" readonly="readonly" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Item Name</label>
						<div class="col-sm-10">
							<input class="form-control form-control-sm" type="text" id="e_itemname" name="e_itemname" placeholder="Item Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Category</label>
						<div class="col-sm-4">
							<select class="form-control form-control-sm select2" id="e_category" name="e_category" >
								<?php foreach($category as $row): ?>
									<option value="<?= ucwords(strtolower(trim($row['category']))); ?>"><?= ucwords(strtolower(trim($row['category']))); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<label class="col-form-label col-sm-2" style="text-align: right;">Unit</label>
						<div class="col-sm-4">
							<select class="form-control form-control-sm" id="e_unit" name="e_unit" >
								<?php foreach($units as $row): ?>
									<option value="<?= ucwords(strtolower(trim($row['unit']))); ?>"><?= ucwords(strtolower(trim($row['unit']))); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Type</label>
						<div class="col-sm-4">
							<select class="form-control select2" id="e_consumable" name="e_consumable" onchange="select_type()">
								<option value="0" selected>Non-Consumable</option>
								<option value="1">Consumable</option>
							</select>
						</div>
						<label class="col-form-label col-sm-2" style="text-align: right;">Depreciation</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="edepyear" name="edepyear" placeholder="Year(s) of Depreciation"
							data-inputmask-alias="numeric" data-inputmask-inputformat="9999" data-mask/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Reorder Point</label>
						<div class="col-sm-4">
							<input type="numeric" class="form-control form-control-sm" id="ereorderpoint" name="ereorderpoint" placeholder="0"
							data-inputmask-alias="numeric" data-inputmask-inputformat="999" data-mask/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Acct. Code In</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="e_acct_in" name="e_acct_in" />
						</div>

						<label class="col-form-label col-sm-2" style="text-align: right;">Acct. Code Out</label>
						<div class="col-sm-4">
							<input type="text" class="form-control form-control-sm" id="e_acct_out" name="e_acct_out" />
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
				<h5 class="modal-title">Delete!</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></span>
				</button>
			</div>

			<form class="form-horizontal" method="POST" action="datahelpers/itemindex_helper.php">
				<div class="modal-body">
					<input type="hidden" id="ditemindex" name="ditemindex">
					<input type="hidden" id="ditemname" name="ditemname">
					<p>Area you sure to delete this item?</p>

				</div>
				<div class="modal-footer justify-content-left">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger " name="delete"> Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>

	$('.select2').select2();
	$('[data-mask]').inputmask();
	// $('#category').editableSelect();
	$('#unit').editableSelect();

	$(document).ready(function() {

		// $('#e_category').editableSelect();
		// $('#e_unit').editableSelect();

	});


	/*function getAreas(e) {
		this.form.elements['itemindexcode'].value =  this.value;
		this.form.elements['xitemindexcode'].value =  this.value;
	}

	(function() {
		var form = document.getElementById('itemindex-form');
		var sz = form.elements['area'];
		for (var i=0, len=sz.length; i<len; i++) {
			sz[i].onclick = getAreas;
		}
	})();  */

	function selectedArea() {
		$itemcode = $("#selectArea").val()
		$("#aitemcode").val($itemcode);
		$("#x_itemcode").val($itemcode);
		//$selectedname = $("#e_empname option:selected").html();
	}

	function selectedCategory() {
		var category = $("#category").val();
		var itemcode =  $("#x_itemcode").val();
		$.ajax({
			url: 'datahelpers/itemindex_helper.php',
			dataType: 'json',
			type: 'post',
			data: { itemcode:itemcode, category:category },
			success: function (data) {
				$("#aitemcode").val(data.newitemcode);
				$("#x_area").val(data.newarea);
			},
			error: function(){
				alert('err');
			}
		});
	}

	function select_type(e) {
		if (Number($("#consumable").val()) == 1 || Number($("#e_consumable").val()) == 1) {
			$("#adepyear").attr( "disabled", true );
			$("#edepyear").attr( "disabled", true );
		} else {
			$("#adepyear").attr( "disabled", false );
			$("#edepyear").attr( "disabled", false );
		}
	}
	/*function selectedItemName() {
		var iditems = document.getElementById("itemname").value;
		var itemindexcode = document.getElementById("xitemindexcode").value;
		$.ajax({
			url: 'datahelpers/itemindex_helper.php',
			dataType: 'json',
			type: 'post',
			data: {iditems:iditems, itemindexcode:itemindexcode},
			success: function (data) {
				document.getElementById("category").value = data.category;
				document.getElementById("itemindexcode").value = data.newitemindexcode;
				document.getElementById("itemcode").value = data.itemcode;
				document.getElementById("consumable").value = data.consumable;
			},
			error: function(){
			}
		});
	}*/



</script>