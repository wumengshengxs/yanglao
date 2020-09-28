<?php
/*
*设备模板
*/
namespace app\index\model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
class Excel{

    /**
     * excel 导出
     * @param string $name=>文件名
     * @param array $data=>数据
     * @param array $head=>文件头列表
     * @param array $keys=>数据key
     */
    public function toExcel($name='', $data=[], $head=[],$keys=[])
    {
        $count = count($head);  //计算表头数量

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始，循环设置表头：
            $sheet->setCellValue(strtoupper(chr($i)) . '1', $head[$i - 65]);
            $num = strlen($head[$i- 65]) * 1.2;

            $spreadsheet->getActiveSheet()->getStyle(strtoupper(chr($i)).'1')->getFont()->setBold(true)->setSize(14);
            $spreadsheet->getActiveSheet()->getColumnDimension(strtoupper(chr($i)))->setWidth($num); //固定列宽

        }

        foreach ($data as $key => $item) {             //循环设置单元格：
            //$key+2,因为第一行是表头，所以写到表格时   从第二行开始写

            for ($i = 65; $i < $count + 65; $i++) {     //数字转字母从65开始：
                $sheet->setCellValue(strtoupper(chr($i)) . ($key + 2), $item[$keys[$i - 65]]);
                $sheet->setCellValueExplicit(strtoupper(chr($i)) . ($key + 2), $item[$keys[$i - 65]],DataType::TYPE_STRING);
            }

        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        //删除清空：
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }

    /**
     * 导入excel
     * @param $filename
     * @param string $suffix
     * @return array
     */
    public function importExcel($filename,$suffix = 'Xlsx')
    {
        $objReader = IOFactory::createReader($suffix);

        $objPHPExcel = $objReader->load($filename);  //$filename可以是上传的表格，或者是指定的表格

        $data = $objPHPExcel->getActiveSheet()->toArray();
        unset($data[0]);
        return $data;

    }
}