<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 12.02.2022
 * Time: 16:49
*/

namespace App\Controller\Documentation;

use App\Modules\VirtualController\VirtualController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route (path="/documentation", methods={"GET"})
 */
class DocumentationController extends VirtualController
{
    public function __invoke(): RedirectResponse
    {
        return $this->redirect('/documentation/index.html');
    }
}
