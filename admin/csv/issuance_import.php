<?php
	//if(!empty($_FILES['csv_file']['name'])) {
		//$file_data = fopen($_FILES['csv_file']['name'], 'r');
		$file_data = fopen('issuance_data.csv', 'r');
		fgetcsv($file_data);
		while ($row = fgetcsv($file_data)) {
			//idate','inumber','purpose','rvno','idissuance','requester
			$data[] = array(
				'idate' => $row[0],
				'inumber' => $row[1],
				'purpose' => $row[2],
				'rvno' => $row[3],
				'idissuance' => $row[4],
				'requester' => $row[5]
			);
		}
		echo json_encode($data);
	//}
?>