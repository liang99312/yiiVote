<?php

namespace backend\utils;

require dirname(dirname(dirname(__FILE__))) . '/excel/PHPExcel.php';

class ExcelToArrary {
	
	/**
	 * 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
	 * 以下基本都不要修改
	 */
	public static function read($filename, $encode = 'utf-8') {
		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		return $excelData;
	}

	public static function push($data, $name = 'Excel') {
		$objPHPExcel = new \PHPExcel();
		/* 以下是一些设置 ，什么作者  标题啊之类的 */
		$objPHPExcel->getProperties()->setCreator("admin")
			->setLastModifiedBy("admin")
			->setTitle("数据EXCEL导出")
			->setSubject("数据EXCEL导出")
			->setDescription("导入示例数据")
			->setKeywords("excel")
			->setCategory("result file");
		/* 以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改 */
		foreach ($data as $k => $v) {
			$num = $k + 1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $num, $v->u_code)
				->setCellValue('B' . $num, $v->u_name)
				->setCellValue('C' . $num, $v->u_dept)
                                ->setCellValue('D' . $num, $v->u_zhiwu)
                                ->setCellValue('E' . $num, $v->u_zhiji);
		}
		$objPHPExcel->getActiveSheet()->setTitle('被评人');
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel charset=utf-8');
		header('Content-Disposition: attachment;filename="' . $name . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

}
