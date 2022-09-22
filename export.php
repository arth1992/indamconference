<?php 

require('config.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fileName = date("Y-m-d").".xlsx";
// (C) CREATE A NEW SPREADSHEET + WORKSHEET
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator("INDAM Conference")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription(
        "INDAM Registrations as of the date.".date('F j, Y').""
    )
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Registrations file");
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Registrations");


$stmt = $db->query("SELECT * FROM `registrations_master`")->fetchAll();


$sheet->setCellValue("A1", "Registration ID");
$sheet->setCellValue("B1", "Full name");
$sheet->setCellValue("C1", "Email");
$sheet->setCellValue("D1", "Mobile");
$sheet->setCellValue("E1", "Dial Code");
$sheet->setCellValue("F1", "Country");
$sheet->setCellValue("G1", "Affiliation");
$sheet->setCellValue("H1", "Designation");
$sheet->setCellValue("I1", "Registration type");
$sheet->setCellValue("J1", "Nationality");
$sheet->setCellValue("K1", "Is INDAM Member?");
$sheet->setCellValue("L1", "Member ID");
$sheet->setCellValue("M1", "Registration Date Time");

$i = 2;
foreach($stmt as $key => $value) {
    $sheet->setCellValue("A".$i, $value["full_name"]);
    $sheet->setCellValue("B".$i, $value["email"]);
    $sheet->setCellValue("C".$i, $value["mobile"]);
    $sheet->setCellValue("D".$i, $value["dial_code"]);
    $sheet->setCellValue("E".$i, $value["country"]);
    $sheet->setCellValue("F".$i, $value["affiliation"]);
    $sheet->setCellValue("G".$i, $value["designation"]);
    $sheet->setCellValue("H".$i, $value["registration_type"]);
    $sheet->setCellValue("I".$i, ucfirst($value["nationality"]));
    $sheet->setCellValue("J".$i, ucfirst($value["is_member"]));
    $sheet->setCellValue("K".$i, $value["member_id"]);
    $sheet->setCellValue("L".$i, date("F j, Y, g:i a",strtotime($value["registered_on"])));
    $i++;
}
// (E) SAVE FILE
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');
 