<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 15:30
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersExporter;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\ContactCompany;
use App\Entity\User\UserProfile;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use App\Service\HumanTraitServices\HumanTraitsService;
use GdImage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;

class SubUsersExporter
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserProfileRepository
     */
    private UserProfileRepository $userProfileRepository;

    /**
     * @var TraitAnalysisRepository
     */
    private TraitAnalysisRepository $traitAnalysisRepository;

    /**
     * @var TraitItemRepository
     */
    private TraitItemRepository $traitItemRepository;

    /**
     * @var TraitColorRepository
     */
    private TraitColorRepository $traitColorRepository;

    /**
     * @var HumanTraitsService
     */
    private HumanTraitsService $humanTraitsService;

    private ?SchemaBuilder $schemaBuilder;

    private Spreadsheet $spreadsheet;

    private Xlsx $writer;
    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param UserProfileRepository $userProfileRepository
     * @param TraitAnalysisRepository $traitAnalysisRepository
     * @param TraitItemRepository $traitItemRepository
     * @param TraitColorRepository $traitColorRepository
     * @param HumanTraitsService $humanTraitsService
     * @param SchemaBuilder|null $schemaBuilder
     */

    public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService,
    )
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;

        $this->spreadsheet = new Spreadsheet();
        $this->writer = new Xlsx($this->spreadsheet);
    }

    private function applyStyles() {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Deep Insight Discovery - Export');
        $sheet->getStyle('A1:BZ1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1f49ff');
        $sheet->getStyle('A1:BZ1')->getFont()->getColor()->setRGB('ffffff');
        $sheet ->getRowDimension(1)->setRowHeight(50);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function calibrateImage(UserProfile $profile, int $rowIndex): void {
        if (!is_null($profile->getAvatar())) {
            $sheet = $this->spreadsheet->getActiveSheet();
            $linkSegments = explode('.', $profile->getAvatar());
            $imgType = end($linkSegments);
            $createdImg = null;

            $avatarUrl =   str_replace($_ENV['BACKEND_URL'], '',$profile->getAvatar());

            if ($imgType === 'png') {
                $createdImg = imagecreatefrompng($avatarUrl);
            }
            if ($imgType === 'jpg') {
                $createdImg = imagecreatefromjpeg($avatarUrl);
            }

            if (($createdImg instanceof GdImage)) {
                $drawing = new MemoryDrawing();
                $drawing->setImageResource($createdImg);
                $drawing->setCoordinates($sheet->getCell('A'.$rowIndex)->getCoordinate());
                $drawing->setWidthAndHeight(50, 50);
                $drawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
                $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
                $drawing->setWorksheet($sheet);
            }
            $sheet->getRowDimension($rowIndex)->setRowHeight(50);
        }
    }

    private function createHeaders() {
        $headers = ['photo', 'first_name', 'last_name', 'email', 'phone', 'birth_day', 'place_of_birth', 'links_to_profiles', 'notes_descriptions_comments', 'country', 'company_name', 'company_www', 'company_industry', 'way_to_earn_money', 'regon', 'krs', 'nip', 'districts', 'head_quarters_city', 'business_phones', 'business_emails', 'revenue', 'profit', 'growth_year_to_year', 'categories', '', 'The driving force', 'The Matrix of Excellence', 'The Moral Code', 'Goals & Wants', 'Behaviors & Needs', 'Seeks & Mindset', 'React & Motive to Action', 'Joins & Desire', 'Polarisation', 'Expression', 'Keyword', 'Visual | See it | Intuition', 'Auditory | Hear it | Thinking', 'Kinesteric | Do it | Sensation', 'Emotive | Feel it | Feeling', 'Initializing & antithesis', 'Stabilizing & synthesis', 'Finishing & thesis', 'Doer - Control', 'Thinker - Order', 'Water - Peace', 'Talker - Fun', 'The Value of', 'Belief', 'Communication', '', 'Strength', 'Tactic', 'Objective', 'World of Action', 'World of Matter', 'World of Information', 'World of Feeling', 'World of Fun', 'World of Usability', 'World of Relations', 'World of Desire&Power', 'World of Seek&Explore', 'World of Career', 'World of Future', 'World of Spirituality', 'P1S', 'P2M', 'P3MY', 'P4W', 'P5M', 'P6J', 'P7S', 'P8U', 'P9N', 'P10N', 'PTNde'];
        $this->spreadsheet->getActiveSheet()->fromArray($headers, NULL, 'A1');
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function createRows(array $subUsers) {
        // Force COLUMN headers
        $subUsers = array_merge([1,2], $subUsers);
        for ($i = 0; $i < count($subUsers); $i ++) {

            $subUser = $subUsers[$i];
            if (!($subUser instanceof User)) {
                continue;
            }
            $profile = &$subUser->profile;
            $company = &$subUser->company;
            $analysis = &$subUser->profile->traitAnalysis;

            $row = [  $profile->getFirstName(), $profile->getLastName(), $subUser->getEmail(), $profile->getPhone(), $profile->getBirthDay(), $profile->getPlaceOfBirth(), $profile->getLinksToProfiles(), $profile->getNotesDescriptionsComments(), $profile->getCountry(), $company->getCompanyName(), $company->getCompanyWww(), $company->getCompanyIndustry(), $company->getWayToEarnMoney(), $company->getRegon(), $company->getKrs(), $company->getNip(), $company->getDistricts(), $company->getHeadQuartersCity(), $company->getBusinessPhones(), $company->getBusinessEmails(), $company->getRevenue(), $company->getProfit(), $company->getGrowthYearToYear(), $company->getCategories(), $analysis->getLifePath(),  $analysis->getTheDrivingForce(), $analysis->getTheMatrixOfExellence(), $analysis->getTheMoralCode(), $analysis->getGoalAndWants(), $analysis->getBehavioursAndNeeds(), $analysis->getSeekAndMindset(), $analysis->getReactAndMotivationToAction(), $analysis->getJoinsAndDesire(), $analysis->getPolarisation(), $analysis->getExpression(), $analysis->getKeyword(), $analysis->getVisualSeeItIntuition(), $analysis->getAuditoryHearItThinking(), $analysis->getKinestericDoItSensation(), $analysis->getEmotiveFeelItFeeling(), $analysis->getInitializingAndAntithesis(), $analysis->getStabilizingAndSynthesis(), $analysis->getFinishingThesis(), $analysis->getDoerControl(), $analysis->getThinkerOrder(), $analysis->getWaterPeace(), $analysis->getTalkerFun(), $analysis->getTheValueOf(), $analysis->getBelief(), $analysis->getCommunication(), $analysis->getStyle(),  $analysis->getStrength(), $analysis->getTactic(), $analysis->getObjective(), $analysis->getWorldOfAction(), $analysis->getWorldOfMatter(), $analysis->getWorldOfInformation(), $analysis->getWorldOfFeeling(), $analysis->getWorldOfFun(), $analysis->getWorldOfUsability(), $analysis->getWorldOfRelations(), $analysis->getWorldOfDesireAndPower(), $analysis->getWorldOfSeekAndExplore(), $analysis->getWorldOfCareer(), $analysis->getWorldOfFuture(), $analysis->getWorldOfSpirituality(), $analysis->getP1S(), $analysis->getP2M(), $analysis->getP3My(), $analysis->getP4W(), $analysis->getP5M(), $analysis->getP6J(), $analysis->getP7S(), $analysis->getP8U(), $analysis->getP9N(), $analysis->getP10N(), $analysis->getPTNde() ];
            $this->spreadsheet->getActiveSheet()->fromArray($row, NULL, 'B'.$i);
            $this->calibrateImage($profile, $i);
        }
    }

    /**
     * @param string $authorUserId
     * @return array
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(string $authorUserId): array
    {

        $fileUniqName = $_ENV['BACKEND_UPLOADS_DIR'] .'/sheets/'.microtime().'.xlsx';
        $subUsers = $this->userRepository->getEntityManager()
            ->createQueryBuilder('user')
            ->select(['user, profile, company, analysis'])
            ->from(User::class, 'user')
            ->innerJoin(UserProfile::class, 'profile', 'WITH', 'user.userId = profile.userId')
            ->leftJoin(ContactCompany::class, 'company', 'WITH', 'user.userId = company.userId')
            ->leftJoin(TraitAnalysis::class, 'analysis', 'WITH', "DATE_FORMAT(profile.birthDay, '%d/%m/%Y') = DATE_FORMAT(cast(analysis.birthDay as date), '%d/%m/%Y')")
            ->where('user.userAuthorId = :authorId')
            ->orWhere('user.userId = :authorId')
            ->addOrderBy('user.userAuthorId', 'desc')
            ->setParameter('authorId', $authorUserId)
            ->getQuery()
            ->getResult();

        $tmpUser = new User();
        $tmpProfile = new UserProfile();
        $tmpCompany = new ContactCompany();
        $tmpTrait = new TraitAnalysis();

        $calculatedSubUsers = [];
        $chunkedList = array_chunk($subUsers, 4);


        foreach ($chunkedList as $chunk) {
            foreach ($chunk as $obj) {
                if ($obj instanceof User) {
                    $tmpUser = $obj;
                }
                else if ($obj instanceof UserProfile) {
                    $tmpProfile = $obj;
                }
                else if ($obj instanceof ContactCompany) {
                    $tmpCompany = $obj;
                }
                else if ($obj instanceof TraitAnalysis) {
                    $tmpTrait = $obj;
                }
            }
            $tmpUser->profile = $tmpProfile;
            $tmpUser->company = $tmpCompany;
            $tmpUser->profile->traitAnalysis = $tmpTrait;

            if (!in_array($tmpUser, $calculatedSubUsers)) {
                $calculatedSubUsers[] = $tmpUser;
            }
        }
        $this->applyStyles();;
        $this->createHeaders();;
        $this->createRows($calculatedSubUsers);

        $this->writer->save($fileUniqName);

        return ['file' => $_ENV['BACKEND_URL']. $fileUniqName];
    }
}
