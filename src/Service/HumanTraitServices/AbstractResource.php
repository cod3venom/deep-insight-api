<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 16.01.2022
 * Time: 14:55
*/


namespace App\Service\HumanTraitServices;

use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use JetBrains\PhpStorm\Pure;

abstract class AbstractResource
{
    /**
     * @return SchemaBuilder
     */
    #[Pure] public function schemaBuilder(): SchemaBuilder
    {
        return (new SchemaBuilder());
    }
}
