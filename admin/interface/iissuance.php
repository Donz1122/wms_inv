<?php 
	interface iissuance{		
		public function all_issuance();
		public function search_issuance($idissuance);
		public function search_issuance_by_date($from, $to);
		public function issuance_details($idissuance);
	}
?>