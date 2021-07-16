<?php
$db = new mysqli('localhost', 'root', 'pass', 'invphp');

if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}

// alternative db connection...
class dbs {

	protected $connection;
	protected $query;
	protected $show_errors = TRUE;
	protected $query_closed = TRUE;
	public $query_count = 0;
	public $user3 = 'x';

	public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = 'pass', $dbname = 'invphp', $charset = 'utf8') {
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
			$this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
	}

	public function query($query) {
		if (!$this->query_closed) {
			$this->query->close();
		}
		if ($this->query = $this->connection->prepare($query)) {
			if (func_num_args() > 1) {
				$x = func_get_args();
				$args = array_slice($x, 1);
				$types = '';
				$args_ref = array();
				foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
						$types .= $this->_gettype($args[$k]);
						$args_ref[] = &$arg;
					}
				}
				array_unshift($args_ref, $types);
				call_user_func_array(array($this->query, 'bind_param'), $args_ref);
			}
			$this->query->execute();
			if ($this->query->errno) {
				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
			}
			$this->query_closed = FALSE;
			$this->query_count++;
		} else {
			$this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
		}
		return $this;
	}


	public function fetchAll($callback = null) {
		$params = array();
		$row = array();
		$meta = $this->query->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		call_user_func_array(array($this->query, 'bind_result'), $params);
		$result = array();
		while ($this->query->fetch()) {
			$r = array();
			foreach ($row as $key => $val) {
				$r[$key] = $val;
			}
			if ($callback != null && is_callable($callback)) {
				$value = call_user_func($callback, $r);
				if ($value == 'break') break;
			} else {
				$result[] = $r;
			}
		}
		$this->query->close();
		$this->query_closed = TRUE;
		return $result;
	}

	public function fetchArray() {
		$params = array();
		$row = array();
		$meta = $this->query->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		call_user_func_array(array($this->query, 'bind_result'), $params);
		$result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
		$this->query->close();
		$this->query_closed = TRUE;
		return $result;
	}

	public function close() {
		return $this->connection->close();
	}

	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	public function lastInsertID() {
		return $this->connection->insert_id;
	}

	public function error($error) {
		if ($this->show_errors) {
			exit($error);
		}
	}

	private function _gettype($var) {
		if (is_string($var)) return 's';
		if (is_float($var)) return 'd';
		if (is_int($var)) return 'i';
		return 'b';
	}

}


//






/*
How To Use

Connect to MySQL database:

include 'db.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'example';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);


Fetch a record FROM a database:

$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', 'test', 'test')->fetchArray();
echo $account['name'];

Or you could do:

$account = $db->query('SELECT * FROM accounts WHERE username = ? AND password = ?', array('test', 'test'))->fetchArray();
echo $account['name'];


Fetch multiple records FROM a database:

$accounts = $db->query('SELECT * FROM accounts')->fetchAll();

foreach ($accounts as $account) {
	echo $account['name'] . '<br>';
}

You can specify a callback if you do not want the results being stored in an array (useful for large amounts of data):

$db->query('SELECT * FROM accounts')->fetchAll(function($account) {
    echo $account['name'];
});

If you need to break the loop you can add:

return 'break';

Get the number of rows:

$accounts = $db->query('SELECT * FROM accounts');
echo $accounts->numRows();


Get the affected number of rows:

$insert = $db->query('INSERT INTO accounts (username,password,email,name) VALUES (?,?,?,?)', 'test', 'test', 'test@gmail.com', 'Test');
echo $insert->affectedRows();


Get the total number of queries:

echo $db->query_count;


Get the last insert ID:

echo $db->lastInsertID();


Close the database:

$db->close();
*/

/*

truncate tbl_itemindex;
insert into tbl_itemindex(itemcode,itemname,area,unit,category,acct_in, acct_out,iduser)
select iicode,iiname,iicategory,iiunit,iiitemtype,IIAccountCodeIn,IIAccountCodeOut,11 from zanecoinv.itemindex where iiname is not null;

truncate tbl_receipts;
insert into tbl_receipts(rrnumber, ponumber, rvnumber, drnumber, sinumber, idsupplier, iduser,receivedate)
select rnumber, rponumber, RVref, rdrnumber, rsinumber, idsupplier, idUsers, rdate from zanecoinv.receipts a
inner join zanecoinv.supplier b on b.suppliercode = a.rsuppliercode;


*/
?>


<?php
// public declaration here...
date_default_timezone_set('Asia/Manila');
$xstartdate 		= date('Y-01-01');
$startdate 			= date('Y-m-01');
$enddate 			= date('Y-m-d');
$year 				= date("y",strtotime("now"));
$year2 				= date("y",strtotime("now")) - 1;
$mos 				= date("m",strtotime("now"));
$today 				= date('Y-m-d');
$day 				= date("d");

$times 				= date("m",strtotime("now"));
$date 				= date('Y-m-d H:i:s', time());

$hm 				= date('Hi', time());
//$date = date('Y-m-d h:i:s a', time());

$msg_not_allowed 	= " You're not allowed to delete/modify this record!";
$msg_save 			= " Successfully saved!";
$msg_edit 			= " Successfully modified!";
$msg_delete 		= " Successfully deleted!";

define('Tan','Albert Tan');
define('encryption_iv', '0929626405700000');
define('encryption_key', openssl_digest('LamdagZANECO', 'MD5', TRUE));

/*define('ciphering', 'BF-CBC');
$iv_length = openssl_cipher_iv_length(ciphering);
$options = 0;
define('encryption_iv', random_bytes($iv_length));
define('encryption_key', openssl_digest(php_uname(), 'MD5', TRUE));*/


function encryptStr($string) {
	$ciphering 		= 'AES-128-CTR';
	$iv_length 		= openssl_cipher_iv_length($ciphering);
	$options 		= 0;
	$encryption_iv 	= '0929626405700000';
	$encryption_key = openssl_digest('LamdagZANECO', 'MD5', TRUE);
	$string 		= openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);
	return $string;
}


function decryptStr($string) {
	$ciphering 		= 'AES-128-CTR';
	$iv_length 		= openssl_cipher_iv_length($ciphering);
	$options 		= 0;
	$decryption_iv 	= '0929626405700000';
	$decryption_key = openssl_digest('LamdagZANECO', 'MD5', TRUE);
	$string 		= openssl_decrypt($string,  $ciphering, $decryption_key, $options, $decryption_iv);
	return $string;
}


function getFirstLetterOnly($str){
	$acronym 		= substr($str,0,1);
	return $acronym;
};

function getFirstLetterInWords($str){
	$words = preg_split("/(\&|\/|\s|\.)/", $str);///[\s,_-]+///$words = preg_split("/[A-Z]/", $str);
	$word='';
	$acronym='';
	foreach($words as $w) {
		$acronym .= substr($w,0,1);
	}
	$word = $word . $acronym ;
	return $word;
};

function base64_url_encode($input){
	return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input){
	return base64_decode(strtr($input, '-_,', '+/='));
}

function formatBytes($bytes, $precision = 2) {
	$unit = [" B", " KB", " MB", " GB"];
	$exp = floor(log($bytes, 1024)) | 0;
	return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
}

function cleanStr($string) {
	$string = str_replace("'", "''", $string);
	return $string;
}

$HEADER_QUERY_STRING_SINGLE = "SELECT a.active, transactiondate, a.iditemindex, a.itemcode, itemname, docno, particulars, category, a.unit, if(type=0,'Non Consumable','Consumable') type, reorderpoint rop, b.quantity qin, b.quantityout qout, b.quantity - b.quantityout qty, a.unit
FROM tbl_itemindex a";
$HEADER_QUERY_STRING_SUM = "SELECT transactiondate, a.iditemindex, a.itemcode, itemname, docno, particulars, category, a.unit, if(type=0,'Non Consumable','Consumable') type, reorderpoint rop, sum(b.quantity) qin,  sum(b.quantityout) qout, sum(b.quantity) - sum(b.quantityout) qty, a.unit, sum(averagecost) averagecost, a.active, sum(if(b.quantity is NULL,0,b.quantity)) SuggestedQty
FROM tbl_itemindex a";
$BODY_QUERY_STRING = "
LEFT OUTER JOIN
(SELECT tbl_receipts.idreceipts id, iditemindex, receivedate transactiondate, suppliername particulars, tbl_receipts.rrnumber docno, sum(if(quantity is null,0,quantity)) quantity, '0' as quantityout, sum(if(unitcost is null,0,unitcost))/count(iditemindex) averagecost, datelogs FROM tbl_receiptsdetails
LEFT OUTER JOIN tbl_receipts on tbl_receipts.idreceipts = tbl_receiptsdetails.idreceipts
LEFT OUTER JOIN tbl_supplier on tbl_supplier.idsupplier = tbl_receipts.idsupplier AND tbl_receiptsdetails.active = 0
WHERE tbl_receipts.active = 0
GROUP BY iditemindex
UNION
SELECT tbl_returns.idreturns id, iditemindex, returneddate transactiondate, particulars, tbl_returns.returnedno docno, sum(if(quantity is null,0,quantity)) quantity, '0' as quantityout, '0' as averagecost, datelogs FROM tbl_returnsdetails
LEFT OUTER JOIN tbl_returns on tbl_returns.idreturns = tbl_returnsdetails.idreturns AND tbl_returnsdetails.active = 0
WHERE tbl_returns.active = 0
GROUP BY iditemindex
UNION
SELECT tbl_salvage.idsalvage id, iditemindex, returneddate transactiondate, description particulars, tbl_salvage.salvageno docno, sum(if(quantity is null,0,quantity)) quantity, '0' as quantityout, '0' as averagecost, datelogs FROM tbl_salvagedetails
LEFT OUTER JOIN tbl_salvage on tbl_salvage.idsalvage = tbl_salvagedetails.idsalvage AND tbl_salvagedetails.active = 0
WHERE tbl_salvage.active = 0
GROUP BY iditemindex
UNION
SELECT tbl_empreceipts.idempreceipts id, iditemindex, datereceived transactiondate, concat(lastname,', ',firstname,' ',middleinitial) particulars, tbl_empreceipts.mrnumber docno, '0' as quantity, sum(if(quantity is null,0,quantity)) quantityout, '0' as averagecost, datelogs FROM tbl_empreceiptdetails
LEFT OUTER JOIN tbl_empreceipts on tbl_empreceipts.idempreceipts = tbl_empreceiptdetails.idempreceipts AND tbl_empreceiptdetails.active = 0
LEFT OUTER JOIN zanecopayroll.employee on zanecopayroll.employee.empnumber = tbl_empreceipts.empno
WHERE tbl_empreceipts.active = 0
GROUP BY tbl_empreceipts.idempreceipts
UNION
SELECT tbl_issuance.idissuance id, iditemindex, idate transactiondate, purpose particulars, tbl_issuance.inumber docno, '0' as quantity, sum(if(quantity is null,0,quantity)) quantityout, '0' as averagecost, datelogs FROM tbl_issuancedetails
LEFT OUTER JOIN tbl_issuance on tbl_issuance.idissuance = tbl_issuancedetails.idissuance AND tbl_issuancedetails.active = 0
where tbl_issuance.active = 0
GROUP BY iditemindex) b on b.iditemindex = a.iditemindex ";

//$sqlquery = $db->query("SELECT iditemindex, itemcode, itemname, unit FROM tbl_itemindex where active = 0 order by itemname asc limit 1")->fetch_assoc();

/*$sqlquery = $db->query(
	$HEADER_QUERY_STRING_SUM.
	$BODY_QUERY_STRING.
	"GROUP BY a.iditemindex HAVING active = 0 ORDER BY itemname ASC LIMIT 1")->fetch_assoc();
$iditemindex = "";
$itemcode = "";
$qty =0;
$unit = "";
$averagecost = 0;
if($sqlquery) {
	$iditemindex = $sqlquery['iditemindex'];
	$itemcode = $sqlquery['itemcode'];
	$qty = $sqlquery['qty'];
	$unit = $sqlquery['unit'];
	$averagecost = number_format($sqlquery['averagecost'],2);
}*/

?>

