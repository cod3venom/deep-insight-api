<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:19
*/

namespace App\Controller\API\Access;

use App\Modules\VirtualController\VirtualController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessController extends VirtualController
{
    /**
     * @Route (path="/api/sign-in", methods={"GET", "POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {

        $email = $request->get('user_email');
        $password = $request->get('user_password');
        return new JsonResponse([$email, $password], Response::HTTP_OK);
    }
}
