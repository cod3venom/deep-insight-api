<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/logout", name="logout")
 */
class Logout
{
    /**
     * __invoke()
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        throw new \InvalidArgumentException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
