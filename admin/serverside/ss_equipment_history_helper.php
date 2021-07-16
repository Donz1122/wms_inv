<?php

$time 	= date("hi");
$query 	= $db->multi_query("
				DROP table if EXISTS x_equipment_history;

				CREATE TABLE `x_equipment_history` (
				  `iditemindex` 	int(10) 			unsigned NOT NULL DEFAULT '0',
				  `itemcode` 			varchar(45) 	CHARACTER SET utf8 NOT NULL,
				  `itemname` 			varchar(145) 	CHARACTER SET utf8 DEFAULT NULL,
				  `tag_no` 				varchar(45) 	CHARACTER SET utf8 DEFAULT NULL,
				  `parts` 				text 					CHARACTER SET utf8
				) ENGINE = InnoDB DEFAULT CHARSET = latin1; 
		
				insert into x_equipment_history (iditemindex, itemcode, itemname, tag_no, parts)
				select ii.iditemindex, ii.itemcode, ii.itemname, tag_no, group_concat(distinct ii2.itemname order by ii2.itemname asc separator ', ') parts from tbl_itemindex ii
				  left join tbl_empreceiptdetails erd on erd.iditemindex = ii.iditemindex
				  left join tbl_issuancedetails id on id.tag_no = erd.serialnos and approved = 1
				  left join tbl_itemindex ii2 on ii2.iditemindex = id.iditemindex
				  where trim(tag_no) != ''
	   			group by ii.iditemindex;  ");
    


?>