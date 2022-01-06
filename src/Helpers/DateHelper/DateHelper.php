<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 06.01.2022
 * Time: 18:32
*/


namespace App\Helpers\DateHelper;

use DateTime;
use Exception;

class DateHelper
{
    /**
     * @throws Exception
     */
    public static function slashToDash(string $dateStr): \DateTime
    {
       try {
           $date = str_replace('/', '-', $dateStr);
           return new DateTime($date);
       }
       catch (Exception $ex){
           throw new \InvalidArgumentException('Wrong date was provided');
       }
    }

    public static function dashToSlash(string $dateStr): DateTime
    {
        try {
            $date = str_replace('-', '/', $dateStr);
            return new DateTime($date);
        } catch (Exception $e) {
            throw new \InvalidArgumentException('Wrong date was provided');
        }
    }

}