<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\LoggerService\LoggerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use SymfonyBundles\JsonRequestBundle\JsonRequestBundle;

/**
 * @Route("/login", name="login")
 */
class Login extends AbstractController
{
    /**
     * __invoke()
     * @param AuthenticationUtils $authenticationUtils
     * @param LoggerService $loggerService
     * @return false|Response
     */
    public function __invoke(AuthenticationUtils $authenticationUtils, LoggerService $loggerService)
    {
        // check current user
        try {
            if ($this->getUser()) {
                return $this->redirectToRoute('dashboard');
            }

            $error        = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            $loggerService->error('Admin::Auth', 'getLastAuthenticationError', [$error]);


            return $this->render('@EasyAdmin/page/login.html.twig', [
                // parameters usually defined in Symfony login forms
                'error'         => $error,
                'last_username' => $lastUsername,

                // OPTIONAL parameters to customize the login form:

                // the translation_domain to use (define this option only if you are
                // rendering the login template in a regular Symfony controller; when
                // rendering it from an EasyAdmin Dashboard this is automatically set to
                // the same domain as the rest of the Dashboard)
                'translation_domain' => 'admin',

                // the title visible above the login form (define this option only if you are
                // rendering the login template in a regular Symfony controller; when rendering
                // it from an EasyAdmin Dashboard this is automatically set as the Dashboard title)
                'page_title' => 'DeepInsigihtDiscovery',

                // the string used to generate the CSRF token. If you don't define
                // this parameter, the login form won't include a CSRF token
                'csrf_token_intention' => 'authenticate',

                // the URL users are redirected to after the login (default: '/admin')
                'target_path' => $this->generateUrl('dashboard'),

                // the label displayed for the username form field (the |trans filter is applied to it)
                'username_label' => 'E-Mail address',

                // the label displayed for the password form field (the |trans filter is applied to it)
                'password_label' => 'Password',

                // the label displayed for the Sign In form button (the |trans filter is applied to it)
                'sign_in_label' => 'Sign in',

                // the 'name' HTML attribute of the <input> used for the username field (default: '_username')
                #'username_parameter' => 'email',

                // the 'name' HTML attribute of the <input> used for the password field (default: '_password')
                #'password_parameter' => 'password',
            ]);
        }
        catch (\Exception $ex) {
            $loggerService->error('Admin::Auth', 'Unable to log-in', [$ex]);
        }
        return false;
    }
}
