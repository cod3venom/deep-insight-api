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
use App\Repository\TraitCategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessController extends VirtualController
{
    /**
     * @Route (path="/sign-in", methods={"GET", "POST"})
     * @param Request $request
     * @param TraitCategoryRepository $rep
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function signIn(Request $request, TraitCategoryRepository $rep): JsonResponse
    {

        $email = $request->get('user_email');
        $password = $request->get('user_password');
        $category = $rep->findByName('Details');

        return new JsonResponse([$email, $password], Response::HTTP_OK);
    }
}
