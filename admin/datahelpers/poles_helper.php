
<?php
include '../session.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select * from tbl_department where iddepartment = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$poleno 	= $_POST['poleno'];	
	$category	= $_POST['category'];
	$poletype	= $_POST['poletype'];
	$address	= utf8_encode($_POST['address']);
	$street		= utf8_encode($_POST['street']);
	$latitude	= $_POST['latitude'];
	$longitude	= $_POST['longitude'];
	$length		= $_POST['length'];
	
	if($db->query("insert into tbl_poles (poleno, category, poletype, address, street, latitude, longitude, length) 
		values    ('$poleno', '$category', '$poletype', '$address', '$street', '$latitude', '$longitude', '$length')")){
		$last_id = $db->insert_id;
		$return['last_id']		= $last_id;
		$return['poleno']		= $poleno;
		$return['saved'] 		= 'saved';		
		$db->query("insert into tbl_poledetails (poleno, itemcode, description, specs, qty, unitcost) 
					select '$poleno', itemcode, description, specs, qty, unitcost
					  from tbl_poledetails pd
					  inner join tbl_poles p on p.poleno = pd.poleno
					  where category like '$category'
					 group by itemcode;");
	} else {
		$return['saved'] 		= 'not saved';					
	}		
	echo json_encode($return);	
}

if(isset($_POST['edit'])){
	$id = $_POST['iddepartment'];
	$odeptname = utf8_encode($_POST['odeptname']);
	$deptname = utf8_encode($_POST['edeptname']);
	$sql = "update tbl_department set deptname='$deptname' where iddepartment='$id'";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'],"Department", "Modify Department [".$odeptname."] to [".$deptname."]");
		$_SESSION['success'] = 'Department information successfully updated!';
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: suppliers.php');
}

if(isset($_POST['delete'])){
	$id = $_POST['id'];
	$deptname = utf8_encode($_POST['ddeptname']);
	$sql = "update tbl_department set active=1 where iddepartment= '$id'";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'],"Department", "Remove Department [".$deptname."]");
		$_SESSION['success'] = 'Department information successfully removed!';
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: suppliers.php');
}

?>