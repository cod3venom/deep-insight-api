<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.02.2022
 * Time: 16:43
*/

namespace App\Service\ContactsService\AbstractResource\ContactsImporter;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\Contact\ImportedContact;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Modules\Sheet\SheetReader;
use App\Modules\StringBuilder\StringBuilder;
use App\Repository\ContactProfileRepository;
use App\Repository\ImportedContactRepository;
use App\Repository\UserRepository;
use App\Service\SubUserService\AbstractResource\SubUsersImporter\Exceptions\MainColumnsAreEmptyException;
use DateTime;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ContactsImporter
{
    private const EXT_WHITE_LIST = ['csv', 'txt', 'xlsx', 'xls'];

    public function __construct(
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function import (
        string                     $targetDir,
        User                       $owner,
        UploadedFile               $file,
        ContactProfileRepository   $contactProfileRepository,
        ImportedContactRepository $importedContactRepository
    ): void
    {

        if (empty($targetDir)) {
            $this->logger->error('SubUsersImporter:: Wrong target dir path was provided');
            throw new InvalidArgumentException('SubUsersImporter:: Wrong target dir was provided');
        }
        if (!file_exists($targetDir)) {
            $this->logger->error('SubUsersImporter:: target dir not found');
            throw new InvalidArgumentException('SubUsersImporter:: target dir not found');
        }

        if (!$this->verifyExtension($file)) {
            $this->logger->error('SubUsersImporter:: provided extension is not allowed');
            throw new InvalidArgumentException('SubUsersImporter:: provided extension is not allowed');
        }


        gc_disable();
        $extension = $file->guessExtension();
        $newName = (new StringBuilder())
            ->append($file->getClientOriginalName())
            ->append('-')
            ->append(uniqid())
            ->append('.')
            ->append($file->guessExtension())->str();

        $file->move($targetDir, $newName);
        $uploadedFile = $targetDir . '/' . $newName;


        $contacts = [];
        switch ($extension) {
            case 'csv':
            case 'txt':
                $contacts = SheetReader::csvReader($uploadedFile)->getActiveSheet()->toArray();;
                break;
            case 'xsl':
                $contacts = SheetReader::xlsRead($uploadedFile)->getActiveSheet()->toArray();
                break;
            case 'xlsx':
                $contacts = SheetReader::xlsxRead($uploadedFile)->getActiveSheet()->toArray();;
        }


        $totalContacts = count($contacts);
        for ($i = 0; $i < $totalContacts; $i++) {
            if ($i === 0) {
                continue;
            }

            $contact = new ContactProfile();
            $company = new ContactCompany();
            $importedContact = new ImportedContact();

            $contact->genContactId();

            $entry = $contacts[$i];
            $email = $entry[array_search('email', $contacts[0])];
            $firstName = $entry[array_search('first_name', $contacts[0])];
            $lastName = $entry[array_search('last_name', $contacts[0])];


            if (is_null($email)) {
                $email = $contact->getContactId() . '@imported.deepinsight.pl';
            }

            $phone = $entry[array_search('phone', $contacts[0])];
            $birthDay = new DateTime($entry[array_search('birth_day', $contacts[0])]);
            $placeOfBirth = $entry[array_search('place_of_birth', $contacts[0])];
            $avatar = $entry[array_search('avatar', $contacts[0])] || $entry[array_search('photo', $contacts[0])];
            $positionInTheCompany = $entry[array_search('position_in_the_company', $contacts[0])];
            $linksToProfiles = $entry[array_search('links_to_profiles', $contacts[0])];
            $notesDescriptionsComments = $entry[array_search('notes_descriptions_comments', $contacts[0])];
            $country = $entry[array_search('country', $contacts[0])];

            $companyName = $entry[array_search('company_name', $contacts[0])];
            $companyWww = ($entry[array_search('company_www', $contacts[0])]);
            $companyIndustry = ($entry[array_search('company_industry', $contacts[0])]);
            $wayToEarnMoney = ($entry[array_search('way_to_earn_money', $contacts[0])]);
            $regon = (int)($entry[array_search('regon', $contacts[0])]);
            $krs = (int)($entry[array_search('krs', $contacts[0])]);
            $nip = (int)($entry[array_search('nip', $contacts[0])]);
            $districts = ($entry[array_search('districts', $contacts[0])]);
            $headQuartersCity = ($entry[array_search('head_quarters_city', $contacts[0])]);
            $businessEmails = ($entry[array_search('business_emails', $contacts[0])]);
            $businessPhones = ($entry[array_search('business_phones', $contacts[0])]);
            $revenue = ($entry[array_search('revenue', $contacts[0])]);
            $profit = ($entry[array_search('revenue', $contacts[0])]);
            $growthYearToYear = ($entry[array_search('growth_year_to_year', $contacts[0])]);
            $categories = ($entry[array_search('categories', $contacts[0])]);

            if (is_null($firstName) || is_null($lastName)) {
                throw new MainColumnsAreEmptyException('Main columns are empty');
            }

            $contact
                ->setOwnerUserId($owner->getUserId())
                ->genContactId()
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPhone($phone)
                ->setBirthDay($birthDay)
                ->setPlaceOfBirth($placeOfBirth)
                ->setPhoto($avatar)
                ->setPositionInTheCompany($positionInTheCompany)
                ->setLinksToProfiles($linksToProfiles)
                ->setNotes($notesDescriptionsComments)
                ->setCountry($country)
                ->setCreatedAt();

            $company
                ->setCompanyName($companyName)
                ->setCompanyWww($companyWww)
                ->setCompanyIndustry($companyIndustry)
                ->setWayToEarnMoney($wayToEarnMoney)
                ->setRegon($regon)
                ->setKrs($krs)
                ->setNip($nip)
                ->setDistricts($districts)
                ->setHeadQuartersCity($headQuartersCity)
                ->setBusinessEmails($businessEmails)
                ->setBusinessPhones($businessPhones)
                ->setRevenue($revenue)
                ->setProfit($profit)
                ->setGrowthYearToYear($growthYearToYear)
                ->setCategories($categories)
                ->setCreatedAt();


            $importedContact
                ->setContactId($contact->getContactId())
                ->setUserOwnerId($owner->getUserId())
                ->setSource($uploadedFile)
                ->setSourceType(ImportedContact::IMPORTED_FROM_FILE);

            $contact->setContactCompany($company);

            $contactProfileRepository->save($contact);
            $importedContactRepository->save($importedContact);
        }
    }

    /**
     * Verify extension of
     * uploaded file
     * @param UploadedFile $file
     * @return bool
     */
    private function verifyExtension(UploadedFile $file): bool
    {
        foreach (self::EXT_WHITE_LIST as $whiteExt) {
            if ($whiteExt === $file->guessExtension()) {
                return true;
            }
        }
        return false;
    }
}
