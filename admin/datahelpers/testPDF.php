<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<?php 
	$file  = "http://192.168.1.52/zanecowms/admin/files/MRDAN0612-405/1.pdf";
	$filename = "1.pdf";
	header("Content-type: application/pdf"); 
	header("Content-Disposition: inline; filename=".$filename);	
	header("Content-Transfer-Encoding: binary");	
	header("Accept-Ranges: bytes");
	@readfile($filename);
	ob_clean();      
	flush();
	?>
</body>
</html>

<!-- <iframe src="http://192.168.1.52/zanecowms/admin/files/MRDAN0612-405/1.pdf?" width="595" height="485" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%;" allowfullscreen> </iframe> -->

