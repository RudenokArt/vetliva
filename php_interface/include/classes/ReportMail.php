<?php

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use travelsoft\booking\Utils as U;

require_once('PHPExcel/PHPExcel.php');

class ReportMail
{
	private static $entity_data_class;

    private static function initialize()
    {
        $hlblock = HLBT::getById(BOOKING_HL_BLOCK)->fetch();  
	    $entity = HLBT::compileEntity($hlblock);
	    self::$entity_data_class = $entity->getDataClass();
    }

	public static function sendDaily()
	{	
		$date = date('d.m.Y', strtotime("-1 days"));

		$data = self::getData($date, $date)->fetchAll();

		$table = self::generateTable($data);

		//заполняем поля шаблона письма
		$arFields = Array(
			"EMAIL_THEME" => 'Ежедневный отчет по бронированиям',
			"TABLE" => $table			
		);	

		$filepath = self::createExcel($data);

		CEvent::Send(REPORT_EVENT_TYPE, "s1", $arFields, "N", REPORT_EVENT_MESSAGE_ID, array($filepath));

	    return "ReportMail::sendDaily();";
	}	

	public static function sendMonthly()
	{
		$dateStart = date('d.m.Y', strtotime('first day of previous month'));
		$dateEnd = date('d.m.Y', strtotime('last day of previous month'));

		$data = self::getData($dateStart, $dateEnd)->fetchAll();

		$table = self::generateTable($data);

		//заполняем поля шаблона письма
		$arFields = Array(
			"EMAIL_THEME" => 'Ежемесячный отчет по бронированиям',
			"TABLE" => $table			
		);	

		$filepath = self::createExcel($data, 1);

		CEvent::Send(REPORT_EVENT_TYPE, "s1", $arFields, "N", REPORT_EVENT_MESSAGE_ID, array($filepath));

		CAgent::RemoveAgent("ReportMail::sendMonthly();");
		CAgent::AddAgent("ReportMail::sendMonthly();", "", "Y", 86400, "", "Y", date('d.m.Y', strtotime('first day of next month'))." 09:00:00");
	}

	private function generateTable($data)
	{
		$table = '<table>' .
					'<tr>' .
						'<th>Номер договора</th>'.
						'<th>Название услуги</th>'.
						'<th>Гражданство туриста</th>'.
						'<th>Цена</th>'.
						'<th>Цена в нац. валюте</th>'.
					'</tr>';

		$sumPrice = 0;			

		foreach ($data as $item)
		{
			$serviceInfo = json_decode($item['UF_SERVICE_JSON'], true);
			$touristsInfo = json_decode($item['UF_TOURISTS_JSON'], true);
			$originalPrice = $serviceInfo["brutto"] - $serviceInfo["discount"];
			$price = $item['UF_CONVERTED_PRICE'];

			$table .= '<tr>' .
						'<td>'. $item['UF_DOGOVOR_CODE'] .'</td>'.
						'<td>'. $item['UF_SERVICE_NAME'] .'</td>'.
						'<td>'. $touristsInfo[0]['citizenship'] .'</td>'.
						'<td>'. $originalPrice.' '.$serviceInfo["currency"].'</td>'.
						'<td>'. $price .' BYN</td>'.
					'</tr>';	

			$sumPrice += $price;
		}

		if($sumPrice > 0){
			$table .= '<tr><td colspan="4"></td><td>'. $sumPrice .' BYN</td></tr>';	
		} else {
			$table .= '<tr><td colspan="5">Нет данных</td></tr>';	
		}
		
		$table .= '</table>';

		return $table;
	}

	private function getData($dateStart, $dateEnd)
	{
		self::initialize();

		//получаем записи заказов за указанный период
	   	return self::$entity_data_class::getList(array(
		    "select" => array("*"),
		    "order" => array("ID" => "ASC"),
		    "filter" => array(		    	
		    	'>=UF_DATE_CREATE' => $dateStart,
				'<=UF_DATE_CREATE' => $dateEnd
		    ),
		));
	}

	private function createExcel($data, $monthly = 0)
	{	
		// Создаем объект класса PHPExcel
		$xls = new PHPExcel();

		// Устанавливаем индекс активного листа
		$xls->setActiveSheetIndex(0);

		// Получаем активный лист
		$sheet = $xls->getActiveSheet();

		//делаем шапку файла жирной
		$sheet->getStyle("A1:E1")->getFont()->setBold( true );

		//заполняем шапку файла
		$sheet->setCellValue("A1", "Номер договора");
		$sheet->setCellValue("B1", "Название услуги");
		$sheet->setCellValue("C1", "Гражданство туриста");
		$sheet->setCellValue("D1", "Цена");
		$sheet->setCellValue("E1", "Цена в нац. валюте");

		$sumPrice = 0;
		$numRow = 2;

		//заполняем строки файла
		foreach ($data as $item)
		{	
			$serviceInfo = json_decode($item['UF_SERVICE_JSON'], true);
			$touristsInfo = json_decode($item['UF_TOURISTS_JSON'], true);
			$originalPrice = $serviceInfo["brutto"] - $serviceInfo["discount"];
			$price = $item['UF_CONVERTED_PRICE'];

			$sheet->setCellValue("A".$numRow, $item['UF_DOGOVOR_CODE']);
			$sheet->setCellValue("B".$numRow, $item['UF_SERVICE_NAME']);
			$sheet->setCellValue("C".$numRow, $touristsInfo[0]['citizenship']);
			$sheet->setCellValue("D".$numRow, $originalPrice.' '.$serviceInfo["currency"]);
			$sheet->setCellValue("E".$numRow, $price.' BYN');

			$sumPrice += $price;
			$numRow++;
		}

		//выводим сумму либо надпись нет данных
		if($sumPrice){
			$sheet->mergeCells('A'.$numRow.':D'.$numRow);
			$sheet->setCellValue("E".$numRow, $sumPrice.' BYN');
		} else {
			$sheet->getStyle('A'.$numRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->setCellValue('A'.$numRow, 'Нет данных');
			$sheet->mergeCells('A'.$numRow.':E'.$numRow);
		}
		 
		//устанавливаем автоматическую ширину колонок
		foreach(range('A','E') as $columnID) {
		    $sheet->getColumnDimension($columnID)
		        ->setAutoSize(true);
		}

		$styleArray = array(
		    'borders' => array(
		    	'allborders' => array(
		      		'style' => PHPExcel_Style_Border::BORDER_THIN
		    	)
		  	)
		);

		//устанавливаем границы колонок
		$sheet->getStyle('A1:E'.$numRow)->applyFromArray($styleArray);		

		// Выводим содержимое файла
		$objWriter = new PHPExcel_Writer_Excel5($xls);

		$filename = $monthly ? 'monthly.xls' : 'daily.xls';
		$path = $_SERVER['DOCUMENT_ROOT'].'/upload/reports/'. $filename;

		$objWriter->save($path);

		return $path;		
	}
}