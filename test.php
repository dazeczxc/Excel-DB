<?php

$DocumentNo,
$PostingDate,
$SalesGroup,
$Group,
$PlantCode,
$Plant,
$ProfitCenter,
$PayerCode,
$Payer,
$ShipToCode,
$Shipto,
$MaterialGroup,
$MaterialSubGroup,
$MaterialNo,
$MaterialDesc,
$MaterialDesc2,
$Heads,             
$Packs,               
$Quantity,	
$QuantityUnit,
$Kilos,
$GrossAmount,
$SalesReturn,            
$Discount,
$OutputTax,
$NetAmount,
$NSP,
$GSP,
$MaterialGroup2,
$SalesConversion,
$CustGroup

'$DocumentNo',
'$PostingDate',
'$SalesGroup',
'$Group',
'$PlantCode',
'$Plant',
'$ProfitCenter',
'$PayerCode',
'$Payer',
'$ShipToCode',
'$Shipto',
'$MaterialGroup',
'$MaterialSubGroup',
'$MaterialNo',
'$MaterialDesc',
'$MaterialDesc2',
'$Heads',
'$Packs',
'$Quantity',
'$QuantityUnit',
'$Kilos',
'$GrossAmount',
'$SalesReturn',
'$Discount',
'$OutputTax',
'$NetAmount',
'$NSP',
'$GSP',
'$MaterialGroup2',
'$SalesConversion',
'$CustGroup'


DocumentNo,
PostingDate,
SalesGroup,
Group,
PlantCode,
Plant,
ProfitCenter,
PayerCode,
Payer,
ShipToCode,
Shipto,
MaterialGroup,
MaterialSubGroup,
MaterialNo,
MaterialDesc,
MaterialDesc2,
Heads,
Packs,
Quantity,
QuantityUnit,
Kilos,
GrossAmount,
SalesReturn,
Discount,
OutputTax,
NetAmount,
NSP,
GSP,
MaterialGroup2,
SalesConversion,
CustGroup






<?php
session_start();
include('dbconfig.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$month  = $_POST['month'];

if(isset($_POST['save_excel_data']) && $month != 0){
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext)){
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $deleteQuery = "DELETE FROM PROVINCIAL_RAW_DATA";
        $execute = mysqli_query($con, $deleteQuery);

        $count = "0";
        $totalRows = count($data) - 1;
        foreach($data as $rowIndex => $row){
            if($count > 0){

                $DocumentNo	= $row['9'];
                $PostingDate = $row['10'];
                $SalesGroup	= $row['0'];
                $Group = $row['8'];
                $PlantCode = $row['63'];
                $Plant = $row['2'];
                $ProfitCenter = $row['0'];	// cant find
                $PayerCode = $row['3'];
                $Payer = $row['15'];
                $ShipToCode	= $row['62'];
                $Shipto	= $row['16'];
                $MaterialGroup = $row['20'];
                $MaterialSubGroup = "";	//none
                $MaterialNo	= $row['18'];
                $MaterialDesc = $row['19'];
                $MaterialDesc2 = ""; //none

                if($row['23'] = "HD"){
                    $Heads = $row['22'];
                }else{
                    $Heads = "";
                }

                if($row['23'] = "PAC" || $row['23'] = "PC"){
                    $Packs = $row['22'];
                }else{
                    $Packs = "";
                }

                $Quantity = $row['22'];	
                $QuantityUnit = $row['23'];	
                $Kilos = $row['24'];
                $GrossAmount = $row['47'];	
                $SalesReturn = "";	//none

                $discounts = 0;
                for ($i = 29; $i <= 45; $i++) {
                    if (isset($row[$i])) {
                        $discounts += $row[$i];
                    }
                }
                $Discount = $discounts;

                $OutputTax = $row['27'];
                $NetAmount = $row['26'];

                if($row['24'] != 0) {
                    $NSP = $row['26'] / $row['24'];
                    $GSP = $row['47'] / $row['24'];
                }else{
                    $NSP = 0;
                    $GSP = 0;
                }
                $MaterialGroup2	= $row['21'];
                $SalesConversion = $row['0'];
                $CustGroup = $row['17'];


                $importQuery = "INSERT INTO PROVINCIAL_RAW_DATA (
                    DocumentNo,
                    PostingDate,
                    SalesGroup,
                    `Group`,
                    PlantCode,
                    Plant,
                    ProfitCenter,
                    PayerCode,
                    Payer,
                    ShipToCode,
                    Shipto,
                    MaterialGroup,
                    MaterialSubGroup,
                    MaterialNo,
                    MaterialDesc,
                    MaterialDesc2,
                    Heads,
                    Packs,
                    Quantity,
                    QuantityUnit,
                    Kilos,
                    GrossAmount,
                    SalesReturn,
                    Discount,
                    OutputTax,
                    NetAmount,
                    NSP,
                    GSP,
                    MaterialGroup2,
                    SalesConversion,
                    CustGroup
                    ) VALUES (
                    $DocumentNo,
                    '$PostingDate',
                    '$SalesGroup',
                    '$Group',
                    $PlantCode,
                    '$Plant',
                    '$ProfitCenter',
                    $PayerCode,
                    '$Payer',
                    $ShipToCode,
                    '$Shipto',
                    '$MaterialGroup',
                    '$MaterialSubGroup',
                    $MaterialNo,
                    '$MaterialDesc',
                    '$MaterialDesc2',
                    $Heads,
                    $Packs,
                    $Quantity,
                    '$QuantityUnit',
                    $Kilos,
                    $GrossAmount,
                    $SalesReturn,
                    $Discount,
                    $OutputTax,
                    $NetAmount,
                    $NSP,
                    $GSP,
                    '$MaterialGroup2',
                    $SalesConversion,
                    '$CustGroup'
                    )";
                $result = mysqli_query($con, $importQuery);
                $msg = true;

                $count++;
                // echo $rowIndex." ";
            }
            else{
                $count = "1";
            }
        }

         if(isset($msg)){
            $_SESSION['message'] = '<div class="alert alert-success">Successfully imported "'.$totalRows.'" records</div>';
            header('Location: index.php');
            exit(0);
        }
        else{
            $_SESSION['message'] = '<div class="alert alert-success">Not Imported</div>';
            header('Location: index.php');
            exit(0);
        }
    }
    else{
        $_SESSION['message'] = '<div class="alert alert-danger">Invalid File</div>';
        header('Location: index.php');
        exit(0);
    }
}else{
    $_SESSION['message'] = '<div class="alert alert-danger">Please select a valid month</div>';
    header('Location: index.php');
}

