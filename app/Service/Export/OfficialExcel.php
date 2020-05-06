<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/3
 * Time: 下午8:43
 */

namespace App\Service\Export;


use Libs\Time;
use App\Service\Export\Contracts\Exportable;

class OfficialExcel implements Exportable
{

    private $fileName;

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileNmae()
    {
        return $this->fileName ? $this->fileName : Time::date();
    }

    public function export($headers, $list, $save = false)
    {
        $phpExcel = new \PHPExcel();

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $row      = 1;
        $cell     = 0;

        foreach($headers as $title){
            $phpExcel->getActiveSheet(0)->setCellValue($cellName[$cell] . $row, $title);
            $cell++;
        }

        $row++;

        foreach($list as $data){
            $cell = 0;
            foreach($data as $cellData){

                $phpExcel->getActiveSheet(0)->setCellValue($cellName[$cell] . $row, $cellData);
                $cell++;
            }
            $row++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');

        return $writer->save($this->finalFile($save));
    }

    private function finalFile($save)
    {
        if($save){
            return $this->getFileNmae();
        }

        header('pragma:public');
        header("Content-Disposition:attachment;filename=" . $this->getFileNmae());

        return "php://output";
    }
}