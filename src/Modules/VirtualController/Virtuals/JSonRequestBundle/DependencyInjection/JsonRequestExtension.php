<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:14
*/

namespace App\Modules\VirtualController\Virtuals\JSonRequestBundle\DependencyInjection;

use App\Modules\VirtualController\Virtuals\JSonRequestBundle\EventListener\RequestTransformerListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class JsonRequestExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $listener = new Definition(RequestTransformerListener::class);
        $listener->addTag('kernel.event_listener', ['event' => KernelEvents::REQUEST, 'priority' => 100]);

        $container->setDefinition(RequestTransformerListener::class, $listener);
    }
}
