<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 21.02.2022
 * Time: 18:41
*/

namespace App\DAO;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class UserMapTObject
{
    public User $user;

    public ContactProfile $contact;

    public function __construct(
        User $user,
        ContactProfile $contact,
    )
    {
        $this->user = $user;
        $this->contact = $contact;
    }

}
