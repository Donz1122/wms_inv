<?php
header('content-Type: text/html; charset=ISO-8859-1');

$userswitch = '';
if(isset($_GET['us'])) {
	$userswitch = $_GET['us'];
	$_SESSION['userswitch'] = $userswitch;
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php';
include 'includes/toastmsg.php';
?>
<body class="hold-transition sidebar-mini layout-fixed  layout-footer-fixed text-sm">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h4> ZANECO Warehouse Management System</h4>
						</div>
					</div>
				</div>
			</section>
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="card card-primary card-outline">
								<div class="card-body box-profile">
									<div class="text-center">
										<img class="profile-user-img img-fluid img-circle"
										src="<?php echo 'files/'.$_SESSION['img'] ?>"
										alt="User profile picture">
									</div>
									<h3 class="profile-username text-center"><?= utf8_decode($_SESSION['user']) ?></h3>
									<p class="text-muted text-center"><?= $_SESSION['position'] ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-9">
							<div class="card">
								<div class="card-header p-2">
									<ul class="nav nav-pills">
										<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
										<li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Reserved</a></li>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="active tab-pane" id="activity">
											<div class="post">
												<div class="user-block">
													<img class="img-circle img-bordered-sm" src="../dist/img/user1-128x128.jpg" alt="user image">
													<span class="username">
														<a href="#">Manny Constanino</a>
														<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
													</span>
													<span class="description">Colossians 3:23-24</span>
												</div>
												<p>
													Whatever you do, work at it with all your heart, as working for the Lord, not for men since you know that you will receive an inheritance from the Lord as a reward.<br>
													It is the Lord Christ you are serving.
												</p>
												<p>
													<a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
												</p>
												<hr>
												<div class="user-block">
													<img class="img-circle img-bordered-sm" src="../dist/img/avatar04.png" alt="user image">
													<span class="username">
														<a href="#">Kaloy Pedro</a>
														<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
													</span>
													<span class="description">John 3:16</span>
												</div>
												<p>
													For God loved the world so much that he gave his only Son, so that everyone who believes in him may not die but have eternal life.<br>
													For God loved the world in this way: He gave his one and only Son, so that everyone who believes in him will not perish but have eternal life.
												</p>
												<p>
													<a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
												</p>

												<!--input class="form-control form-control-sm" type="text" placeholder="Type a comment"-->
											</div>
										</div>
										<div class="tab-pane" id="timeline">
											<div class="timeline timeline-inverse">
												<!-- timeline time label -->
												<div class="time-label">
													<span class="bg-danger">
														01 Apr. 2020
													</span>
												</div>
												<!-- /.timeline-label -->
												<!-- timeline item -->
												<div>
													<i class="fas fa-envelope bg-primary"></i>

													<div class="timeline-item">
														<span class="time"><i class="far fa-clock"></i> 08:00</span>

														<h3 class="timeline-header"><a href="#">MIS Team</a> Orientation</h3>

														<div class="timeline-body">
															Orientation about the system to make. etc...
														</div>
														<div class="timeline-footer">
															<a href="#" class="btn btn-primary btn-sm">Read more</a>
															<a href="#" class="btn btn-danger btn-sm">Delete</a>
														</div>
													</div>
												</div>
												<!-- END timeline item -->
												<!-- timeline item -->
												<div>
													<i class="fas fa-user bg-info"></i>

													<div class="timeline-item">
														<span class="time"><i class="far fa-clock"></i> later...</span>

														<h3 class="timeline-header border-0"><a href="#">Albert Tan</a> ...
														</h3>
													</div>
												</div>
												<!-- END timeline item -->
												<!-- timeline item -->
												<div>
													<i class="fas fa-comments bg-warning"></i>

													<div class="timeline-item">
														<span class="time"><i class="far fa-clock"></i> later...</span>

														<h3 class="timeline-header"><a href="#">Albert Tan</a> ...</h3>

														<div class="timeline-body">
															etc...
														</div>
														<div class="timeline-footer">
															<a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
														</div>
													</div>
												</div>
												<!-- END timeline item -->
												<!-- timeline time label -->
												<div class="time-label">
													<span class="bg-success">
														14 Aug. 2020
													</span>
												</div>
												<!-- /.timeline-label -->
												<!-- timeline item -->
												<div>
													<i class="fas fa-camera bg-purple"></i>

													<div class="timeline-item">
														<span class="time"><i class="far fa-clock"></i> 10:00</span>

														<h3 class="timeline-header"><a href="#">MIS Team</a> Presented the system</h3>

														<div class="timeline-body">
															<img src="http://placehold.it/150x100" alt="...">
															<img src="http://placehold.it/150x100" alt="...">
															<img src="http://placehold.it/150x100" alt="...">
															<img src="http://placehold.it/150x100" alt="...">
														</div>
													</div>
												</div>
												<!-- END timeline item -->
												<div>
													<i class="far fa-clock bg-gray"></i>
												</div>
											</div>
										</div>

									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
									<h3><?= $_SESSION['reordercount'] ?></h3>

									<p>Reorder Items</p>
								</div>
								<div class="icon">
									<i class="ion ion-bag"></i>
								</div>
								<a href="reorderitems.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
									<h3><?= $_SESSION['expirethisyear'] ?><sup style="font-size: 20px"></sup></h3>

									<p>Warranty Expire this year</p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
								<a href="warranty_item.php?id=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
									<h3><?= $_SESSION['request'] ?><sup style="font-size: 20px"></sup></h3>

									<p>Material Request</p>
								</div>
								<div class="icon">
									<i class="ion ion-person-add"></i>
								</div>
								<a href="issuance.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
									<h3>0<sup style="font-size: 20px">%</sup></h3>

									<p>Reserved</p>
								</div>
								<div class="icon">
									<i class="ion ion-pie-graph"></i>
								</div>
								<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
					</div>

				</div>
			</section>
		</div>

		<?php //include 'modals/items_modal.php'?>
		<?php include 'includes/footer.php'?>
	</div>

	<script>
		$.widget.bridge('uibutton', $.ui.button)

	</script>

</body>
</html>
