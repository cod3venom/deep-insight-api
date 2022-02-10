<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 09:15
*/

namespace App\Modules\StrTransformer;

final class StrTransformer
{
    public static function strToProperty(string $input): string {
        $fullName = '';
        $segments = explode(' ', $input);

        for ($i = 0; $i < count($segments); $i++) {
           if ($i === 0) {
               $fullName .= lcfirst($segments[$i]);
           } else {
               $fullName .= ucfirst($segments[$i]);
           }
        }
        return $fullName;
    }
}
