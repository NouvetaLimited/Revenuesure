<?php
//SHOW A DATABASE ON A PDF FILE
//CREATED BY: Carlos Vasquez S.
//E-MAIL: cvasquez@cvs.cl
//CVS TECNOLOGIA E INNOVACION
//SANTIAGO, CHILE

include 'includes/DB.php';
include 'helpers/Response.php';
include 'helpers/ConfirmationCode.php';
include "helpers/AfricasTalkingGateway.php";
require 'helpers/fpdf/fpdf.php';



//Select the Products you want to show in your PDF file
$sql1 = "SELECT `payments`.`Transaction_code`,`payments`.`Date`,`payments`.`Amount`,`revenue_sources`.`RevSource`\n"

    . "FROM `payments`\n"

    . "LEFT JOIN `revenue_sources` on `revenue_sources`.`RevID`=`payments`.`RevID`";

$result=DB::instance()->executeSQL($sql1);
$number_of_products = mysqli_num_rows($result);;

//Initialize the 3 columns and the total
$column_Transaction_code = "";
$column_date = "";
$column_Amount = "";
$column_name= "";
$total = 0;

//For each row, add the field to the corresponding column
while($row = mysqli_fetch_array($result))
{
    $code = $row["Transaction_code"];
    $name = $row["RevSource"];
    $date = $row["Date"];
    $Amount=$row["Amount"];

    $column_Transaction_code = $column_Transaction_code.$code."\n";
    $column_name = $column_name.$name."\n";
    $column_Amount = $column_Amount.$Amount."\n";
    $column_date = $column_date.$date."/n";

    //Sum all the Prices (TOTAL)
    $total = $total+$Amount;
}


//Convert the Total Price to a number with (.) for thousands, and (,) for decimals.
//$total = number_format($total,',','.','.');

//Create a new PDF file
$pdf=new FPDF();
$pdf->AddPage();

//Fields Name position
$Y_Fields_Name_position = 20;
//Table position, under Fields Name
$Y_Table_Position = 26;

//First create each Field Name
//Gray color filling each Field Name box
$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',12);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(45);
$pdf->Cell(20,6,'CODE',1,0,'L',1);
$pdf->SetX(45);
$pdf->Cell(100,6,'NAME',1,0,'L',1);
$pdf->SetX(135);
$pdf->Cell(30,6,'Amount',1,0,'R',1);
$pdf->Ln();

//Now show the 3 columns
$pdf->SetFont('Arial','',12);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(30);
$pdf->MultiCell(30,6,$column_Transaction_code,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(45);
$pdf->MultiCell(100,6,$column_name,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(65);
$pdf->MultiCell(30,6,$column_Amount,1,'R');
$pdf->SetX(65);
$pdf->MultiCell(30,6,'KSH'.$total,1,'R');

//Create lines (boxes) for each ROW (Product)
//If you don't use the following code, you don't create the lines separating each row
$i = 0;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_products)
{
    $pdf->SetX(45);
    $pdf->MultiCell(120,6,'',1);
    $i = $i +1;
}

$pdf->Output();
?>