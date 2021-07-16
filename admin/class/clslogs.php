<?php

if (!file_exists('../interface/ilogs.php')) {
    require 'interface/ilogs.php';
}
else {
    require '../interface/ilogs.php';
}

class logs extends dbs implements ilogs {
	public function recordlogs($id, $process, $details) {		
		$date = date('Y-m-d h:i:s a', time());		
		$sql = "insert into tbl_logs (iduser, forms, transactions) values ('".$id."','".$process."','".$details."')";		
		return $this->query($sql)->affectedRows();	
	}
}
$record = new logs();
?>