<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:10
*/

namespace App\Modules\VirtualController;

use App\Modules\VirtualController\Virtuals\ResponseBuilder\ResponseBuilder;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

abstract class VirtualResource extends AbstractController
{
    protected ResponseBuilder $responseBuilder;
    public function __construct(SerializerInterface $serializer)
    {
        $this->responseBuilder = new ResponseBuilder($serializer);
    }
}
