<?php

ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '600');
require_once ('../php/PHPExcel.php');
include ('../php/PHPExcel/IOFactory.php');
require ("conn.php");
require ("islogged.php");
session_start();

$filename = $_SESSION['filename'];

//LECTOR DE CSV PARA PREPARARLO A XLS
$objReader = PHPExcel_IOFactory::createReader('CSV');
//CARGAMOS EL CSV DENTRO DEL XLS
$objPHPExcel = $objReader->load($filename);

//CONVERTIMOS EL VALOR DE PONUMBER A EXPLICITO PARA EVITAR QUE EXCEL CONVIERTA EL VALOR
$porowcount = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
$j = "2";
for ($j = 2; $j <= $porowcount; $j++) {
    $pvalor = $objPHPExcel->getActiveSheet()->getCell('P' . $j)->getValue();
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $j, $pvalor, PHPExcel_Cell_DataType::TYPE_STRING);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Order.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>