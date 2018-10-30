<?php
require('fpdf.php');
include('session.php');
include 'Cart.php';

class PDF extends FPDF
{

function Header()
{
    // Logo
    // $this->Image('logo.png',20,10,60);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'Awesome Shopping Store Order Summary',0,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','B',15);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

// Colored table
function FancyTable($header, $data)
{
        $this->Cell(40);

    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(80, 40, 40, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],10,$header[$i],1,0,'C',true);
    $this->Ln();

    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    $total = 0;

    foreach($data as $row)
    //$total = 0;
    {
        // $this->Ln();
        $this->Cell(40);
        $this->Cell($w[0],6,$row["name"],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row["qty"],'LR',0,'L',$fill);
        $total = $total + $row["subtotal"];
        $this->Ln();


        $fill = !$fill;
    }
           // $this->Ln();

    // Closing line


    $this->SetFillColor(0,100,255);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(80, 40);
    $newheader = array('', 'Total $'.$total.' USD');

    $this->Cell(40);
    for($i=0;$i<count($newheader);$i++)
        $this->Cell($w[$i],10,$newheader[$i],1,0,'C',true);


    // $this->Cell(array_sum($w),10,$total,'T');
}


}

$cart = new Cart;
$pdf = new PDF();
// Column headings
$header = array('Name', 'Quantity');
// Data loading
$data = array(array(1,2,3,4),array(1,2,3,4));
$cartItems = $cart->contents();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->FancyTable($header,$cartItems);
$pdf->Output();
?>