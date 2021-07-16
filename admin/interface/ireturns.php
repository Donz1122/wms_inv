<?php
	interface ireturns{
		public function employee();

		public function all_items_return_to_suppliers($idreturns='');
		public function all_items_return_to_suppliers_details($returnedno='',$limit='');

		public function all_items_return_to_warehouse($idreturns='',$limit='');
		public function all_items_return_to_warehouse_details($idreturns='',$limit='');

		public function get_mr_and_issuance_of_employee($empno, $limit='');
		public function get_items_from_supplier($suppliercode, $limit='');
	}
?>