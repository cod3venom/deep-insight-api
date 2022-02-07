<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 07.02.2022
 * Time: 16:24
*/

namespace App\Modules\Sheet;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SheetReader
{
    /**
     * @param string $fileName
     * @return Spreadsheet
     */
    public static function xlsRead(string $fileName): Spreadsheet
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException($fileName . ' not found');
        }


        $reader = new Xls();
        $result = $reader->load($fileName);
        return $result;
    }

    /**
     * @param string $fileName
     * @return Spreadsheet
     */
    public static function xlsxRead(string $fileName): Spreadsheet
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException($fileName . ' not found');
        }


        $reader = new Xlsx();
        $result = $reader->load($fileName);
        return $result;
    }

    /**
     * @param string $fileName
     * @return Spreadsheet
     */
    public static function csvReader(string $fileName): Spreadsheet
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException($fileName . ' not found');
        }

        $reader = new Csv();
        $result = $reader->load($fileName);
        return $result;
    }
}
