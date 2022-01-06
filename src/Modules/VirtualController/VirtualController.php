<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 28.12.2021
 * Time: 22:10
*/

namespace App\Modules\VirtualController;

use App\Entity\User\User;

abstract class VirtualController extends VirtualResource
{
    /**
     * @return User
     */
    protected function user(): User
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $user;
        }
        return new User();
    }

    protected function isAdmin(): bool
    {
        return in_array(User::ROLE_ADMIN, $this->user()->getRoles());
    }

    protected function isUser(): bool
    {
        return in_array(User::ROLE_USER, $this->user()->getRoles());
    }

    protected function isSubUser(): bool
    {
        return in_array(User::ROLE_SUB_USER, $this->user()->getRoles());
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
