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
							<h4> Material Issuance</h4>
						</div>
						<div class="col-sm-4 row">

							<div class="btn-group btn-sm row">
								<?php if(isset($_GET['id'])) {
									$_SESSION['area_request'] = $_GET['id']; ?>
									<button class="btn btn-info btn-sm" style="margin-top: -3px; height: 30px;" onclick="prepareIssuanceNumber('mtt')" > New MRV</button>
								<?php } else { ?>
									<button class="btn btn-info btn-sm" style="margin-top: -3px; height: 30px;" onclick="prepareIssuanceNumber('mct')" > New MRV</button>
								<?php }  ?>
							</div>

							<!-- <div class="input-group-prepend ml-2" style="height: 40px;">
								<button class="btn btn-sm btn-info dropdown-toggle status_label" data-toggle="dropdown" style="height: 31px">Status</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="#" onclick="selected_status(0)">Pending</a>
									<a class="dropdown-item" href="#" onclick="selected_status(1)">Approved</a>
									<a class="dropdown-item" href="#" onclick="selected_status(2)">Cancelled</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" onclick="selected_status(3)">All</a>
								</div>
							</div> -->

						</div>

						<div class="row ml-auto">
							<div class="btn-group ">
								<p class="col-form-label" style="text-align: right; padding-right: 10px;">Period Covered From  </p>
								<div class="col">
									<div class="input-group input-group-sm">
										<input type="date" class="form-control form-control-sm" id="fromdate" name="fromdate" data-mask value="<?= $startdate; ?>">
									</div>
								</div>
							</div>
							<div class="btn-group ">
								<p class="col-form-label" style="text-align: right; padding: 5px 10px 0 10px;">To</p>
								<div class="col">
									<div class="input-group input-group-sm">
										<input type="date" class="form-control form-control-sm" id="todate" name="todate" data-mask value="<?= $enddate; ?>">
										<div class="input-group-append ">
											<div class="btn btn-info" onclick="search_daterange_issuancelist()">
												<i class="fas fa-search"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="input-group-prepend mr-2" style="height: 39px;">
								<button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" style="height: 31px">Printing</button>
								<div class="dropdown-menu">
									<a class="dropdown-item " onclick="print_issuance('MCT')">Material Charge Ticket</a>
									<a class="dropdown-item " onclick="print_issuance('MTT')">Material Transfer Ticket</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" onclick="print_issuance('All')">All Material Issuance Ticket</a>
								</div>
							</div>

						</div>

					</div>
				</div>
				<div id="issuance-list"></div>
			</div>
		</div>

		<?php include 'includes/datatable-footer.php'?>
		<?php include 'modals/issuance_modal.php'?>

	</div>

	<script>

		var empno = "<?= $_SESSION['empno'] ?>";
		var area  = "<?= $_SESSION['area'] ?>";

		function load_issuancelist(){
			$.ajax({
				url: 'datatables/issuance_list.php',
				success: function (data) {
					$('#issuance-list').html(data);
				},
				error: function(){
					alert('Error: Issuance List');
				}
			});
		}load_issuancelist();

		function search_daterange_issuancelist(){
			var startdate = $('#fromdate').val();
			var enddate = $('#todate').val();
			$('#table2').DataTable().destroy();
			fetch_data('yes',startdate,enddate);
		};


		function prepareIssuanceNumber(x)  {
			$('#idissuance').val('');
			$('#inumber').val('');
			$('#purpose').val('');
			$('#amount').val('0.00');
			$('#jono').val('');
			var y = document.getElementById("area");

			$.ajax({
				url: 'datahelpers/issuance_helper.php',
				type: 'post',
				dataType: 'json',
				data: { x:x },
				success: function (data) {
					if(x == 'mct') {
						y.style.display = 'none';
						$('#modal-addissuance').find('.modal-title').text('Material Charge Ticket');
						$('#modal-addissuance').find('.ilabel').text('MCT No.');
						$('#inumber').val(data.num);
						$('#rvno').val(data.rvno);
					} else {
						y.style.display = '';
						$('#modal-addissuance').find('.modal-title').text('Material Transfer Ticket');
						$('#modal-addissuance').find('.ilabel').text('MTT No.');
						$('#inumber').val(data.num);
						$('#rvno').val(data.rvno);
					}

					$("#requister").select2().val(empno).trigger('change.select2');
					$('#empno').val(empno);
					$('#modal-addissuance').modal('show');

					$("#transferto").select2().val(area).trigger('change.select2');

				},
				error: function(ex){
					alert('issuance line: 122');
				}
			});
		};

		function selected_status(num) {
			if(num == 0) $('.status_label').html('Pending');
			if(num == 1) $('.status_label').html('Approved');
			if(num == 2) $('.status_label').html('Cancelled');
			if(num == 3) $('.status_label').html('All');

			$('#table2').DataTable().destroy();
			fetch_data('no','','',num);
		};

		function print_issuance(trans) {
			var from  = $('#fromdate').val();
			var to    = $('#todate').val();

			var redirectWindow = window.open('printing/issuance_print_ticket_summary.php?id='+trans+'&from='+from+'&to='+to, '_blank');
			redirectWindow.location;
		};

		// alert

		var iduser  = "<?= $_SESSION['iduser'] ?>";
		setInterval(function(){
			$.ajax({
				url     : 'datahelpers/alert_helper.php',
				type    : 'post',
				dataType: 'json',
				data    : { searchid:iduser },
				success: function(data){
					showalert(data.requestid, data.remarks, 'Approved By: ' + data.approveby, data.area);
					remove_alerted(data.idalert);
				},
				error: function(data){
				},
			});
		}, 3000);

		function remove_alerted(index) {
			$.ajax({
				url     : 'datahelpers/alert_helper.php',
				type    : 'post',
				dataType: 'json',
				data    : { remove:index },
				success: function(data) {  }
			});
		}

		function showalert(id,remarks, by, area){
			$(document).Toasts('create', {
				class    : 'bg-white',
				title    : '<span style="font-size: 12px;"> Material Request </span>',
				subtitle : '<a href="" class="nav-link" id="">'+id+'</a>',
				position : 'bottomRight',
				icon     : 'fas fa-envelope fa-lg',
				body     : '<span style="font-size:13px;">'+remarks+'<span><strong><hr>' + '<span style="font-size:11px;">'+ by +' - '+ area +'</span></strong>',
				allowToastClose: true,
			})
		};


	</script>
</body>
</html>
