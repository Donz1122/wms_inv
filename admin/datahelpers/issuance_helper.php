<?php
include '../session.php';
include '../class/clslogs.php';

if(isset($_POST['x'])){
	$x = strtoupper($_POST['x']); 			// x value is mtt ot mct
	$return['title'] 		= "Edit Item";
	$return['event'] 		= "edit";
	$str1 					= $_SESSION["area"];

	$sql 					=
	"select count(inumber)+1 as cnt
	from tbl_issuance where inumber like '$x%' and date_format(idate,'%m') like date_format(now(),'%m')
	and date_format(idate,'%Y') like date_format(now(),'%Y')
	order by inumber asc
	limit 1;";
	$mctt 					= $db->query($sql)->fetch_assoc();

	$sql 					=
	"select count(rvno)+1 as cnt
	from tbl_issuance where rvno like 'MRV%' and date_format(idate,'%m') like date_format(now(),'%m')
	and date_format(idate,'%Y') like date_format(now(),'%Y')
	order by rvno asc
	limit 1;";
	$rvs 					= $db->query($sql)->fetch_assoc();
	// $num 					= $x.$str1.$mos.$year.'-'.$mctt['cnt'];
	$num 					= $x;			// x value is mtt ot mctt
	$num2 					= "MRV".$str1.$mos.$year.'-'.$rvs['cnt'];


	$return['var'] 		= $x;
	$return['num'] 		= $num;
	$return['rvno'] 	= $num2;
		// $return['empno']	= $id2['empno'];

	echo json_encode($return);
}

if(isset($_POST['id'])){
	$id 					= $_POST['id'];
	$sql 					=
	"select a.*, fullname from tbl_issuance a
	left outer join tbl_user b on b.iduser = a.iduser
	where a.active=0 and idissuance = '$id' limit 1";
	$query 					= $db->query($sql)->fetch_assoc();

	$return['idissuance'] 	= $query['idissuance'];
	$return['inumber'] 		= $query['inumber'];
	$return['idate'] 		= $query['idate'];

	$return['rvno'] 		= $query['rvno'];
	$return['requister'] 	= utf8_encode($query['requister']);
	$return['transferto'] 	= utf8_encode($query['transferto']);
	$return['jono'] 		= $query['jono'];
	$return['empno'] 		= $query['empno'];
	$return['status'] 		= $query['status'];
	$return['iduser'] 		= $query['iduser'];
	$return['purpose'] 		= utf8_encode($query['purpose']);

	$return['issuedby'] 	= utf8_encode($query['fullname']);
	$return['trans'] 		= substr(strtoupper($query['inumber']),0,3);

	echo json_encode($return);
}

/*if(isset($_POST['add']) || isset($_POST['edit'])) {
	if(!empty($_POST['inumber']))
		$inumber 			= $_POST['inumber'];
	if(!empty($_POST['idate']))
		$idate 				= $_POST['idate'];

	$purpose 				= utf8_encode($_POST['purpose']);
	$rvno 					= $_POST['rvno'];
	$requister 				= utf8_encode($_POST['requister']);

	$transferto 			= '';
	if(!empty($_POST['transferto']))
		$transferto 		= utf8_encode($_POST['transferto']);

	$empno 					= $_POST['empno'];
	$iduser 				= $_SESSION['iduser'];

	if(!empty($_POST['add'])) {
		$sql 				= "insert into tbl_issuance (inumber,idate,purpose,rvno,requister,transferto,iduser, empno) values ('$inumber','$idate','$purpose','$rvno','$requister','$transferto','$iduser','$empno')";
		$record->recordlogs($_SESSION['iduser'],"Issuance", "Add Issuance No.[".$rvno."] Purpose [".$purpose."]");
	} else
	if(!empty($_POST['edit'])) {
		$idissuance 		= $_POST['idissuance'];
		$sql 				= "update tbl_issuance set purpose='$purpose', requister='$requister', rvno='$rvno', transferto='$transferto',empno='$empno' where idissuance='$idissuance'";
		$record->recordlogs($_SESSION['iduser'],"Issuance", "Edit Issuance No.[".$rvno."] Purpose ".$purpose."]");
	}
	if($db->query($sql)){
		$_SESSION['success'] = 'Record Successfully saved!';
	} else {
		$_SESSION['error'] 	 = $db->error;
	}

	//$return['ok'] = $sql;
	//echo json_encode($return);
}*/

if(isset($_POST['add']) || isset($_POST['add_open'])) {
	$idate 					= $_POST['idate'];
	$rvno 					= $_POST['rvno'];
	$inumber 				= $_POST['inumber'];

	$purpose 				= utf8_encode($_POST['purpose']);
	$requister 				= utf8_encode($_SESSION['user']);
	$transferto 			= utf8_encode($_POST['transferto']);

	$empno 					= $_POST['empno'];
	$iduser 				= $_SESSION['iduser'];
	$last_id 				= '';


	$sql 					= "insert into tbl_issuance (idate,purpose,inumber,rvno,requister,transferto,iduser, empno) values ('$idate','$purpose','$inumber','$rvno','$requister','$transferto','$iduser','$empno')";

	if($db->query($sql)){
		$last_id = $db->insert_id;
		$_SESSION['success'] = 'Record Successfully saved!';
		$record->recordlogs($_SESSION['iduser'],"Issuance", "Add Issuance No.[".$rvno."] Purpose [".$purpose."]");
	} else {
		$_SESSION['error'] 	 = $db->error;
	}
	if(isset($_POST['add'])) {
		header('location: ../issuance.php');
	} else {
		if(intval($_SESSION['restriction']) == 1 && $_SESSION['area'] == 'DMO')  {
			header('location: ../issuancedetails.php?id='.$last_id);			
		} else {
			header('location: ../issuancedetails_addons.php?id='.$last_id);			
		}
	}
}

if(isset($_POST['edit'])) {

	$idissuance 			= $_POST['eidissuance'];
	$idate 					= $_POST['eidate'];
	$rvno 					= $_POST['ervno'];
	$purpose 				= utf8_encode($_POST['epurpose']);
	$transferto 			= utf8_encode($_POST['etransferto']);

	$empno 					= $_POST['empno'];


	$sql 					= "update tbl_issuance set idate='$idate', purpose='$purpose', transferto='$transferto' where idissuance='$idissuance'";
	$record->recordlogs($_SESSION['iduser'],"Issuance", "Edit Issuance No.[".$rvno."] Purpose ".$purpose."]");

	if($db->query($sql)){
		$_SESSION['success'] = 'Record Successfully updated!';
	} else {
		$_SESSION['error'] 	 = $db->error;
	}
	header('location: ../issuance.php');
}

if(isset($_POST['delete'])){
	$id = $_POST['didissuance'];
	$inumber = $_POST['dinumber'];

	$sql = "update tbl_issuance set active = 1 where idissuance = '$id'";
	if($db->query($sql)){
		$record->recordlogs($iduser,"Issuance","Delete Issuance No.".$inumber."]");
		$_SESSION['success'] = 'Issuance deleted successfully';
	}
	else{
		$_SESSION['error'] = $db->error;
	}
	header('location: ../issuance.php');
}


