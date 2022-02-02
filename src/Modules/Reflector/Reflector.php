<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 02.02.2022
 * Time: 11:10
*/

namespace App\Modules\Reflector;

use ReflectionException;

final class Reflector
{
    /**
     * @param $entity
     * @param array $input
     * @return mixed
     * @throws ReflectionException
     */
    public static function arrayToEntity(&$entity, array $input): mixed
    {
        $reflector = new \ReflectionClass($entity);

        foreach ($input as $k=>$v){
            $methodName = 'set' . ucfirst($k);
            if (!$reflector->hasMethod($methodName)) {
                continue;
            }

            $entity->{$methodName}($v);
        }
        return $entity;
    }
}
