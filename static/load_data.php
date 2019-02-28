<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

$data = json_decode($data['data']);

// Подключаем класс для работы с excel
require_once('PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('PHPExcel/Writer/Excel5.php');

// Создаем объект класса PHPExcel
//$xls = new PHPExcel();
$xls = PHPExcel_IOFactory::load('template.xls');
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
//$sheet->setTitle('Таблица умножения');

// Вставляем текст в ячейку A1
//$sheet->setCellValue("A1", 'Таблица умножения');
//$sheet->getStyle('A1')->getFill()->setFillType(
//    PHPExcel_Style_Fill::FILL_SOLID);
//$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
//$sheet->mergeCells('A1:H1');

// Выравнивание текста
//$sheet->getStyle('A1')->getAlignment()->setHorizontal(
//    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//for ($i = 2; $i < 10; $i++) {
//    for ($j = 2; $j < 10; $j++) {
//         Выводим таблицу умножения
//        $sheet->setCellValueByColumnAndRow(
//            $i - 2,
//            $j,
//            $i . "x" .$j . "=" . ($i*$j));
//         Применяем выравнивание
//        $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
//        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//    }
//}
//

$i=12;
foreach ($data as $item) {
    $sheet->setCellValue("E".$i, ($i-11));
    $sheet->setCellValue("D".$i, $item->name_group);
    $sheet->setCellValue("H".$i, $item->minus_word);
    $sheet->setCellValue("J".$i, $item->header1);
    $sheet->setCellValue("K".$i, $item->header2);
    $sheet->setCellValue("L".$i, $item->info_text);
    $sheet->setCellValue("P".$i, $item->url);
    $sheet->setCellValue("Q".$i, $item->os);
    $sheet->setCellValue("X".$i, $item->quick_link_value);
    $sheet->setCellValue("Y".$i, $item->quick_link_url);
    $sheet->setCellValue("Z".$i, $item->quick_link_text);
    $sheet->setCellValue("AE".$i, $item->quick_link_note);
    $i++;
//    echo $item->minus_word."<br/>\n";
}

$filename = time().'.xls';
// Выводим содержимое файла
$objWriter = new PHPExcel_Writer_Excel5($xls);
$objWriter->save('files/'.$filename);

echo json_encode(['filename' => $filename]);

?>
