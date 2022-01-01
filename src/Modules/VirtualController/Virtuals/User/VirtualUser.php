<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 01.01.2022
 * Time: 21:33
*/


namespace App\Modules\VirtualController\Virtuals\User;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VirtualUser extends AbstractController
{

    /**
     * @return User
     */
    public function retrieveUser(): User
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $user;
        }
        return new User();
    }

    /**
     * @return string
     */
    public function getBearer(): string
    {
        $headers = apache_request_headers();
        if (!isset($headers["Authorization"])) {
            return false;
        }
        if (gettype($headers["Authorization"]) !== "string") {
            return false;
        }
        $bearer = str_replace("bearer ", "", $headers["Authorization"]);
        $bearer = str_replace("Bearer ", "", $headers["Authorization"]);
        return $bearer;
    }
}