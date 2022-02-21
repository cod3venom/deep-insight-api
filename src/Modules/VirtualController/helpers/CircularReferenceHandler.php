<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 21.02.2022
 * Time: 17:38
*/

namespace App\Modules\VirtualController\helpers;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}
