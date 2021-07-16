<!DOCTYPE html>
<html lang="en">
<?php include 'session.php'?>
<?php include 'includes/datatable-header.php'?>

<?php //include 'includes/sidebar.php' ?>

<body class="hold-transition layout-top-nav text-sm">
	<div class="wrapper">
		<?php include 'includes/navbar-header.php'?>
		<div class="content-wrapper">
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-1.5 mr-2">
							<h4> Item Category</h4>
						</div>
						<div class="col-sm-4 row">

							<div class="btn-group btn-sm row">
								<!-- <button class="btn btn-info btn-sm" style="margin-top: -3px; height: 30px;" onclick="prepareIssuanceNumber('mtt')" > New Category</button> -->
								<a  href="#modal-additemcategory" class="btn btn-info btn-sm add" data-toggle="modal" > Add Category</a>
							</div>

						</div>

						<div class="ml-auto">
							<a href="itemindex.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
						</div>

					</div>
				</div>
				<div id="itemcategory-list"></div>
			</div>
		</div>

		<?php include 'includes/datatable-footer.php'?>
		<?php include 'modals/itemcategory_modal.php'?>

	</div>

	<script>

		function load_itemindexcategory(){
			$.ajax({
				url: 'datatables/itemcategory_list.php',
				success: function (data) {
					$('#itemcategory-list').html(data);
				},
				error: function(){
					alert('Error: itemcategory List');
				}
			});
		}load_itemindexcategory();



	</script>
</body>
</html>
