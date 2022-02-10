<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.02.2022
 * Time: 16:43
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersImporter;

use App\Controller\API\User\Exceptions\UserAlreadyExistsException;
use App\Entity\User\ImportedSubUsers;
use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Entity\User\UserProfile;
use App\Modules\Sheet\SheetReader;
use App\Modules\StringBuilder\StringBuilder;
use App\Repository\ImportedSubUsersRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SubUsersImporter
{
    private LoggerInterface $logger;
    private const EXT_WHITE_LIST = [
        'csv', 'txt', 'xlsx', 'xls'
    ];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function import(
        string                     $authorUserId,
        string                     $targetDir,
        UploadedFile               $file,
        UserRepository             $userRepository,
        ImportedSubUsersRepository $importedSubUsersRepository
    )
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


        $extension = $file->guessExtension();
        $newName = (new StringBuilder())
            ->append($file->getClientOriginalName())
            ->append('-')
            ->append(uniqid())
            ->append('.')
            ->append($file->guessExtension())->str();

        $file->move($targetDir, $newName);
        $uploadedFile = $targetDir . '/' . $newName;


        $allSubUsers = [];
        switch ($extension) {
            case 'csv':
            case 'txt':
                $allSubUsers = SheetReader::csvReader($uploadedFile)->getActiveSheet()->toArray();;
                break;
            case 'xsl':
                $allSubUsers = SheetReader::xlsRead($uploadedFile)->getActiveSheet()->toArray();
                break;
            case 'xlsx':
                $allSubUsers = SheetReader::xlsxRead($uploadedFile)->getActiveSheet()->toArray();;
        }


        for ($i = 0; $i < count($allSubUsers); $i++) {
            if ($i === 0) {
                continue;
            }

            $user = new User();
            $user->profile = new UserProfile();
            $user->company = new UserCompanyInfo();
            $importedUserEntity = new ImportedSubUsers();

            $entry = $allSubUsers[$i];

            $userId = Uuid::uuid4()->toString();
            $email = $entry[array_search('email', $allSubUsers[0])];
            $password = password_hash(microtime(), PASSWORD_DEFAULT);

            if (is_null($email)) {
                $email = $userId . '@imported.deepinsight.pl';
            }


            $userExists = $userRepository->findByEmail($email);
            if ($userExists->getUserId()) {
                continue;
            }

            $firstName = $entry[array_search('first_name', $allSubUsers[0])];
            $lastName = $entry[array_search('last_name', $allSubUsers[0])];
            $phone = $entry[array_search('phone', $allSubUsers[0])];
            $birthDay = new DateTime($entry[array_search('birth_day', $allSubUsers[0])]);
            $placeOfBirth = $entry[array_search('place_of_birth', $allSubUsers[0])];
            $avatar = $entry[array_search('avatar', $allSubUsers[0])];
            $positionInTheCompany = $entry[array_search('avatar', $allSubUsers[0])];
            $linksToProfiles = $entry[array_search('links_to_profiles', $allSubUsers[0])];
            $notesDescriptionsComments = $entry[array_search('notes_descriptions_comments', $allSubUsers[0])];
            $country = $entry[array_search('country', $allSubUsers[0])];

            $companyName = $entry[array_search('company_name', $allSubUsers[0])];
            $companyWww = ($entry[array_search('company_www', $allSubUsers[0])]);
            $companyIndustry = ($entry[array_search('company_industry', $allSubUsers[0])]);
            $wayToEarnMoney = ($entry[array_search('way_to_earn_money', $allSubUsers[0])]);
            $regon = (int)($entry[array_search('regon', $allSubUsers[0])]);
            $krs = (int)($entry[array_search('krs', $allSubUsers[0])]);
            $nip = (int)($entry[array_search('nip', $allSubUsers[0])]);
            $districts = ($entry[array_search('districts', $allSubUsers[0])]);
            $headQuartersCity = ($entry[array_search('head_quarters_city', $allSubUsers[0])]);
            $businessEmails = ($entry[array_search('business_emails', $allSubUsers[0])]);
            $businessPhones = ($entry[array_search('business_phones', $allSubUsers[0])]);
            $revenue = ($entry[array_search('revenue', $allSubUsers[0])]);
            $profit = ($entry[array_search('revenue', $allSubUsers[0])]);
            $growthYearToYear = ($entry[array_search('growth_year_to_year', $allSubUsers[0])]);
            $categories = ($entry[array_search('categories', $allSubUsers[0])]);

            $user
                ->setUserId($userId)
                ->setUserAuthorId($authorUserId)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();

            $user->profile
                ->setUserId($userId)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setPhone($phone)
                ->setBirthDay($birthDay)
                ->setPlaceOfBirth($placeOfBirth)
                ->setAvatar($avatar)
                ->setPositionInTheCompany($positionInTheCompany)
                ->setLinksToProfiles($linksToProfiles)
                ->setNotesDescriptionsComments($notesDescriptionsComments)
                ->setCountry($country)
                ->setCreatedAt();

            $user->company
                ->setUserId($userId)
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


            $importedUserEntity
                ->setUserId($user->getUserId())
                ->setUserAuthorId($user->getUserAuthorId())
                ->setSource($uploadedFile)
                ->setSourceType(ImportedSubUsers::IMPORTED_FROM_FILE);

            $userRepository->save($user);
            $importedSubUsersRepository->save($importedUserEntity);
    }
}

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
