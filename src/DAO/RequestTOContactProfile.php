<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 23.02.2022
 * Time: 10:03
*/

namespace App\DAO;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use DateTime;

class RequestTOContactProfile
{

    /**
     * @throws \Exception
     */
    public static function toEntity(ContactProfile &$entity, array $json): ContactProfile
    {
        $contact = self::parseContact($entity, $json);
        $company = $entity->getContactCompany();

        if (isset($json['contactCompany'])) {
            $company = self::parseCompany($company, $json['contactCompany']);
        }

        $contact->setContactCompany($company);
        return $contact;
    }

    /**
     * @throws \Exception
     */
    private static function parseContact(ContactProfile $contact, array $json): ContactProfile
    {
        if (isset($json['id'])) {
            $contact->setId($json['id']);
        }
        if (isset($json['firstName'])) {
            $contact->setFirstName($json['firstName']);
        }

        if (isset($json['lastName'])) {
            $contact->setLastName($json['lastName']);
        }

        if (isset($json['email'])) {
            $contact->setEmail($json['email']);
        }

        if (isset($json['phone'])) {
            $contact->setPhone($json['phone']);
        }

        if (isset($json['photo'])) {
            $contact->setPhoto($json['photo']);
        }

        if (isset($json['birthDay'])) {
            $contact->setBirthDay(new DateTime($json['birthDay']));
        }

        if (isset($json['placeOfBirth'])) {
            $contact->setPlaceOfBirth($json['placeOfBirth']);
        }

        if (isset($json['positionInTheCompany'])) {
            $contact->setPositionInTheCompany($json['positionInTheCompany']);
        }
        if (isset($json['linksToProfiles'])) {
            $contact->setLinksToProfiles($json['linksToProfiles']);
        }
        if (isset($json['notes'])) {
            $contact->setNotes($json['notes']);
        }
        if (isset($json['country'])) {
            $contact->setCountry($json['country']);
        }

        return $contact;
    }

    private static function parseCompany(ContactCompany $company,array $contactCompany): ContactCompany
    {
        if (isset($contactCompany['id'])) {
            $company->setId($contactCompany['id']);
        }
        if (isset($contactCompany['companyName'])) {
            $company->setCompanyName($contactCompany['companyName']);
        }
        if (isset($contactCompany['companyWww'])) {
            $company->setCompanyWww($contactCompany['companyWww']);
        }
        if (isset($contactCompany['companyIndustry'])) {
            $company->setCompanyIndustry($contactCompany['companyIndustry']);
        }
        if (isset($contactCompany['wayToEarnMoney'])) {
            $company->setWayToEarnMoney($contactCompany['wayToEarnMoney']);
        }
        if (isset($contactCompany['regon'])) {
            $company->setRegon($contactCompany['regon']);
        }
        if (isset($contactCompany['krs'])) {
            $company->setKrs($contactCompany['krs']);
        }
        if (isset($contactCompany['nip'])) {
            $company->setNip($contactCompany['nip']);
        }
        if (isset($contactCompany['districts'])) {
            $company->setDistricts($contactCompany['districts']);
        }
        if (isset($contactCompany['headQuartersCity'])) {
            $company->setHeadQuartersCity($contactCompany['headQuartersCity']);
        }
        if (isset($contactCompany['businessEmails'])) {
            $company->setBusinessEmails($contactCompany['businessEmails']);
        }
        if (isset($contactCompany['businessPhones'])) {
            $company->setBusinessPhones($contactCompany['businessPhones']);
        }
        if (isset($contactCompany['revenue'])) {
            $company->setRevenue($contactCompany['revenue']);
        }
        if (isset($contactCompany['profit'])) {
            $company->setProfit($contactCompany['profit']);
        }
        if (isset($contactCompany['growthYearToYear'])) {
            $company->setGrowthYearToYear($contactCompany['growthYearToYear']);
        }
        if (isset($contactCompany['categories'])) {
            $company->setCategories($contactCompany['categories']);
        }

        return $company;
    }

}
