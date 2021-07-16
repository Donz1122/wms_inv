<?php 
	interface interfaces{		

		public function search_itemindex_summary($keys);
		public function search_itemindex_details($keys);
		public function itemindex();	
		public function itemindexyearendqty($keys, $year);
		public function categories();	
		public function units();			
		public function acctcharts();
		public function suppliers();
		public function employee();

		public function users();		

		public function get_purchase_order($limit='');
		public function get_rr($id);
		public function get_rrdetails($id);
		public function get_issuance($id);
		public function get_issuancedetails($id, $approved='', $filter='',$submitted='');


		public function UnitStatus();

		public function get_allmrdetails($id, $filter='');
		public function serialnos();

		public function equipment_history($id);
		
	}
?>