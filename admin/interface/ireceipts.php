<?php 
	interface ireceipts{		
		public function all_receipts();
		public function rr_list($idreceiptsdetails='');
		public function search_receipts($supplier, $date);
	}
?>