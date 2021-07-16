<?php
include '../session.php';
include '../fpdf182/fpdf.php';

$dbs = new dbs();

$dates = $_GET['id'];
$sql = "select idate, a.inumber, purpose, c.itemindexcode, itemname, quantity, b.unitcost, (quantity * b.unitcost) amount
from tbl_issuance a
inner join tbl_issuancedetails b on b.idissuance = a.idissuance
inner join tbl_itemindex c on c.iditemindex = b.iditemindex
inner join tbl_items d on d.iditems = c.iditems
where month(idate) = month('$dates') and year(idate) = year('$dates')
order by a.inumber";


class PDF extends FPDF{
    function Header(){
        $this->Image('../dist/img/logo2-1.2x1.2.png',10,6,30);
        $this->SetFont('Arial','B',12);
        $this->Cell(30);
        $this->Cell(30,10,'Zamboanga del Norte Electric Coop., Inc.',0,0);
        $this->SetFont('Arial','',12);
        $this->Ln(5);
        $this->Cell(30);
        $this->Cell(30,10,'ZANECO',0,0);
        $this->Ln(5);
        $this->Cell(30);
        $this->Cell(10,10,'General Luna St., Dipolog City, Zamboanga del Norte',0,0);  
        $this->Ln();
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function ImprovedTable()    {
        if(isset($_GET['id'])) {
            $header = array('Date','MCT No.','Particular','Item Code','Item Description','Qty','Unit Cost','Amount');
            $w = array(30, 30, 40, 45, 35, 40, 45, 40);
            for($i=0;$i<count($header);$i++) 
                $this->Cell($w[$i],7,$header[$i],1,0,'C');
            $this->Ln();


            //$rows = $dbs->query($sql);    

            /*$rows = $dbs->query($sql)->fetchAll();
            foreach($rows as $row) {
                $this->Cell($w[0],6,$row[0],'LR',0,'L');
                $this->Cell($w[1],6,$row[1],'LR',0,'L');
                $this->Cell($w[2],6,$row[2],'LR',0,'L');
                $this->Cell($w[3],6,$row[3],'LR',0,'L');
                $this->Cell($w[4],6,$row[4],'LR',0,'L');
                $this->Cell($w[5],6,$row[5],'LR',0,'L');
                $this->Cell($w[6],6,number_format($row[6]),'LR',0,'R');
                $this->Cell($w[7],6,number_format($row[7]),'LR',0,'R');
                $this->Ln();
            }
            $this->Cell(array_sum($w),0,'','T');*/
        }
    }
}


$pdf = new PDF();
//$pdf = new FPDF('l','mm','legal');
$pdf->AliasNbPages();
$pdf->AddPage('L','Legal');
$pdf->SetFont('Arial','',8);
$pdf->Ln(8);

//$pdf->ImprovedTable();

$header = array('Date','MCT No.','Particular','Item Code','Item Description','Qty','Unit Cost','Amount');
$w = array(30, 30, 60, 45, 60, 30, 30, 30);
for($i=0;$i<count($header);$i++) 
    $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
$pdf->Ln();

//while($row = $query->fetch_assoc()){
$rows = $dbs->query($sql)->fetchAll();

//$query = $db->query($sql);

//$lines = $rows;
//$data = array();
//foreach($lines as $line)
//    $data[] = explode('\n',trim($line));

foreach($rows as $row) {
    //$pdf->Cell($w[0],6,$row['idate'],'LR',0,'L');
    $pdf->Cell($w[0],6,date('M d, Y', strtotime($row['idate'])),'LR',0,'L');
    $pdf->Cell($w[1],6,$row['inumber'],'LR',0,'L');
    $pdf->MultiCell($w[2],6,$row['purpose'],1);
    $pdf->Cell($w[3],6,$row['itemindexcode'],'LR',0,'L');
    $pdf->MultiCell($w[4],6,$row['itemname'],1);
    $pdf->Cell($w[5],6,$row['quantity'],'LR',0,'L');
    $pdf->Cell($w[6],6,number_format($row['unitcost']),'LR',0,'R');
    $pdf->Cell($w[7],6,number_format($row['amount']),'LR',0,'R');

    //$pdf->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
    //$pdf->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
    $pdf->Ln();
}
   $pdf->Cell(array_sum($w),0,'','T');

//for($i=1;$i<=30;$i++)
//    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

$pdf->Output();

?>

