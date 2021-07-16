<?php
include '../session.php';
if(isset($_POST['upload'])) {
	if(!empty($_POST['mrnumber'])) {
		$mrnumber 	= $_POST['mrnumber'];	

		$targetDIR 	= "../files/".$mrnumber."/";
		if (!is_dir($targetDIR)) { 	
			mkdir($targetDIR, 0777, true); 	
			$sql 	= "update tbl_empreceipts set pdflocation = '$targetDIR' where mrnumber = '$mrnumber'";
			$db->query($sql);
		}

		$count 		= count($_FILES['file']['name']);
		if($count > 0 )			
			$db->query("update tbl_empreceipts set pdflocation = '$targetDIR' where mrnumber = '".$mrnumber."'");
		for ($i=0; $i<$count; $i++) {

			$targetFile = $targetDIR.$_FILES['file']['name'][$i];
			$tempFile 	= $_FILES['file']['tmp_name'][$i];

			if(move_uploaded_file($tempFile, $targetFile))	{
				$_SESSION['success'] .= basename($targetFile). " is uploaded<br>";
			}
			else {
				$_SESSION['error'] .= basename($targetFile). " is upload failed!<br>";
			}
		}
	} else {
		$_SESSION['error'] = "Please Select MR Number...";
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}


if(is_array($_FILES)) {
	$filename 	= $_FILES['file']['name'];
	$id 		= $_SESSION['dummy_iduser'];
	$location 	= '../files/'.$filename;

	$file_extension = pathinfo($location, PATHINFO_EXTENSION);
	$file_extension = strtolower($file_extension);

	$image_ext = array("jpg","png","jpeg","gif");

	$response = 0;
	if(in_array($file_extension,$image_ext)){
		if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
			$response = $location;
		}		
	}
	if(!empty($id)) {
		$db->query("update tbl_user set img = '".$response."' where iduser = '$id'");
	}
	//$return['filename'] 		= $response;	

	//echo json_encode($return);
	echo basename($response);	
}

?>
