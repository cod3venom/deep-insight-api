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
    public const BIRTH_DAY_FORMAT = 'd/m/Y';

    /**
     * @throws Exception
     */
    public static function birthDay(string $dateStr = "", DateTime $dateTime = null): DateTime
    {
        try{
            if (!empty($dateStr)){
                return self::slashToDash($dateStr);
            }
            if ($dateTime){
                $dateTime->format(self::BIRTH_DAY_FORMAT);
                return $dateTime;
            }
            return $dateTime;
        }
        catch (Exception $ex){
            throw new \InvalidArgumentException('Wrong date was provided');
        }
    }

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