<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.02.2022
 * Time: 05:29
*/

namespace App\Controller;

use App\Modules\VirtualController\VirtualController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @link https://symfony.com/doc/current/controller/service.html
 *
 * @Route("/", name="home")
 */
class HomePage extends VirtualController
{
    public function __invoke(): Response
    {
        return $this->render('index.html.twig', []);
    }
}
