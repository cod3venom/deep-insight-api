<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 02.02.2022
 * Time: 11:10
*/

namespace App\Modules\Reflector;

use DateTime;
use Exception;
use phpDocumentor\Reflection\DocBlock\StandardTagFactory;
use ReflectionClass;
use ReflectionException;

final class Reflector
{
    /**
     * @param $entity
     * @param array $input
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public static function arrayToEntity(&$entity, array $input): mixed
    {
        $reflector = new \ReflectionClass($entity);

        foreach ($input as $k=>$v){
            $methodName = 'set' . ucfirst($k);
            if (!$reflector->hasMethod($methodName)) {
                continue;
            }

            $method = new \ReflectionMethod($entity, $methodName);
            $methodParams = $method->getParameters();
            foreach ($methodParams as $methodParam) {

                $paramType = $methodParam->getType();
                if (!is_null($paramType)) {
                    $paramType = $paramType->getName();
                }

                if ($paramType === 'string') {
                   $v= (string)$v;
                }

                if ($paramType === 'int') {
                    $v= (int)$v;
                }

                if ($paramType === 'bool') {
                    $v= (bool)$v;
                }

                if ($paramType === 'DateTime') {
                    $v = new DateTime($v);
                }
            }

            $entity->{$methodName}($v);
        }
        return $entity;
    }

    /**
     * @throws ReflectionException
     */
    public static function allProperties($entity): array
    {
        $reflector = new ReflectionClass($entity);
        return $reflector->getProperties();
    }
}