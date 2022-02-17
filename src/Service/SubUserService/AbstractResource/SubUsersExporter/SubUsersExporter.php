<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 15:30
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersExporter;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use App\Service\HumanTraitServices\HumanTraitsService;
use Doctrine\DBAL\Schema\Schema;
use GdImage;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
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
    }

    /**
     * @throws Exception
     */
    public function export(string $authorUserId): array
    {

        $this->schemaBuilder = new SchemaBuilder();
        $spreadSheet = new Spreadsheet();
        $writer = new Xlsx($spreadSheet);

        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setTitle('Deep Insight Discovery - Export');

        $sheet->getStyle('A1:BZ1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1f49ff');
        $sheet->getStyle('A1:BZ1')->getFont()->getColor()->setRGB('ffffff');
        $sheet ->getRowDimension(1)->setRowHeight(50);

        $sheet->getCell('A1')->setValue('photo');
        $sheet->getCell('B1')->setValue('first_name');
        $sheet->getCell('C1')->setValue('last_name');
        $sheet->getCell('D1')->setValue('email');
        $sheet->getCell('E1')->setValue('phone');
        $sheet->getCell('F1')->setValue('birth_day');
        $sheet->getCell('G1')->setValue('place_of_birth');
        $sheet->getCell('H1')->setValue('links_to_profiles');
        $sheet->getCell('I1')->setValue('notes_descriptions_comments');
        $sheet->getCell('J1')->setValue('country');
        $sheet->getCell('K1')->setValue('company_name');
        $sheet->getCell('L1')->setValue('company_www');
        $sheet->getCell('M1')->setValue('company_industry');
        $sheet->getCell('N1')->setValue('way_to_earn_money');
        $sheet->getCell('O1')->setValue('regon');
        $sheet->getCell('P1')->setValue('krs');
        $sheet->getCell('Q1')->setValue('nip');
        $sheet->getCell('R1')->setValue('districts');
        $sheet->getCell('S1')->setValue('head_quarters_city');
        $sheet->getCell('T1')->setValue('business_phones');
        $sheet->getCell('U1')->setValue('business_emails');
        $sheet->getCell('V1')->setValue('revenue');
        $sheet->getCell('W1')->setValue('profit');
        $sheet->getCell('X1')->setValue('growth_year_to_year');
        $sheet->getCell('Y1')->setValue('categories');
        $sheet->getCell('Z1')->setValue('Life Path');

        $sheet->getCell('AA1')->setValue('The driving force');
        $sheet->getCell('AB1')->setValue('The Matrix of Excellence');
        $sheet->getCell('AC1')->setValue('The Moral Code');
        $sheet->getCell('AD1')->setValue('Goals & Wants');
        $sheet->getCell('AE1')->setValue('Behaviors & Needs');
        $sheet->getCell('AF1')->setValue('Seeks & Mindset');
        $sheet->getCell('AG1')->setValue('React & Motive to Action');
        $sheet->getCell('AH1')->setValue('Joins & Desire');
        $sheet->getCell('AI1')->setValue('Polarisation');
        $sheet->getCell('AJ1')->setValue('Expression');
        $sheet->getCell('AK1')->setValue('Keyword');
        $sheet->getCell('AL1')->setValue('Visual | See it | Intuition');
        $sheet->getCell('AM1')->setValue('Auditory | Hear it | Thinking');
        $sheet->getCell('AN1')->setValue('Kinesteric | Do it | Sensation');
        $sheet->getCell('AO1')->setValue('Emotive | Feel it | Feeling');
        $sheet->getCell('AP1')->setValue('Initializing & antithesis');
        $sheet->getCell('AQ1')->setValue('Stabilizing & synthesis');
        $sheet->getCell('AR1')->setValue('Finishing & thesis');
        $sheet->getCell('AS1')->setValue('Doer - Control');
        $sheet->getCell('AT1')->setValue('Thinker - Order');
        $sheet->getCell('AU1')->setValue('Water - Peace');
        $sheet->getCell('AV1')->setValue('Talker - Fun');
        $sheet->getCell('AW1')->setValue('The Value of');
        $sheet->getCell('AX1')->setValue('Belief');
        $sheet->getCell('AY1')->setValue('Communication');
        $sheet->getCell('AZ1')->setValue('Style');

        $sheet->getCell('BA1')->setValue('Strength');
        $sheet->getCell('BB1')->setValue('Tactic');
        $sheet->getCell('BC1')->setValue('Objective');
        $sheet->getCell('BD1')->setValue('World of Action');
        $sheet->getCell('BE1')->setValue('World of Matter');
        $sheet->getCell('BF1')->setValue('World of Information');
        $sheet->getCell('BG1')->setValue('World of Feeling');
        $sheet->getCell('BH1')->setValue('World of Fun');
        $sheet->getCell('BI1')->setValue('World of Usability');
        $sheet->getCell('BJ1')->setValue('World of Relations');
        $sheet->getCell('BK1')->setValue('World of Desire&Power');
        $sheet->getCell('BL1')->setValue('World of Seek&Explore');
        $sheet->getCell('BM1')->setValue('World of Career');
        $sheet->getCell('BN1')->setValue('World of Future');
        $sheet->getCell('BO1')->setValue('World of Spirituality');
        $sheet->getCell('BP1')->setValue('P1S');
        $sheet->getCell('BQ1')->setValue('P2M');
        $sheet->getCell('BR1')->setValue('P3MY');
        $sheet->getCell('BS1')->setValue('P4W');
        $sheet->getCell('BT1')->setValue('P5M');
        $sheet->getCell('BU1')->setValue('P6J');
        $sheet->getCell('BV1')->setValue('P7S');
        $sheet->getCell('BW1')->setValue('P8U');
        $sheet->getCell('BX1')->setValue('P9N');
        $sheet->getCell('BY1')->setValue('P10N');
        $sheet->getCell('BZ1')->setValue('PTNde');

        $emptyIndexes = [1, 2];
        $subUsers = $this->userRepository->setStartFrom(-1)->allSubUsers($authorUserId);
        $subUsers = array_merge($emptyIndexes, $subUsers);

        for ($i = 2; $i < count($subUsers); $i ++) {

            $subUser = $subUsers[$i];
            if (!($subUser instanceof User)) {
                continue;
            }

            $birthDay = date_format($subUser->profile->getBirthDay(), UserProfile::BirthDayFormat);
            $userTraits = $this->traitAnalysisRepository->findTraitsByBirthDay($birthDay);

            $profile = &$subUser->profile;
            $company = &$subUser->company;
            $analysis = &$userTraits;


           if (!is_null($profile->getAvatar())) {

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
                   $drawing->setCoordinates($sheet->getCell('A'.$i)->getCoordinate());
                   $drawing->setWidthAndHeight(50, 50);
                   $drawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
                   $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
                   $drawing->setWorksheet($sheet);
               }

               $sheet->getRowDimension($i)->setRowHeight(50);
           }

            $sheet->getCell('A'. $i)->setValue('');
            $sheet->getCell('B'. $i)->setValue($profile->getFirstName());
            $sheet->getCell('C'. $i)->setValue($profile->getLastName());
            $sheet->getCell('D'. $i)->setValue($subUser->getEmail());
            $sheet->getCell('E'. $i)->setValue($profile->getPhone());
            $sheet->getCell('F'. $i)->setValue($profile->getBirthDay());
            $sheet->getCell('G'. $i)->setValue($profile->getPlaceOfBirth());
            $sheet->getCell('H'. $i)->setValue($profile->getLinksToProfiles());
            $sheet->getCell('I'. $i)->setValue($profile->getNotesDescriptionsComments());
            $sheet->getCell('J'. $i)->setValue($profile->getCountry());
            $sheet->getCell('K'. $i)->setValue($company->getCompanyName());
            $sheet->getCell('L'. $i)->setValue($company->getCompanyWww());
            $sheet->getCell('M'. $i)->setValue($company->getCompanyIndustry());
            $sheet->getCell('N'. $i)->setValue($company->getWayToEarnMoney());
            $sheet->getCell('O'. $i)->setValue($company->getRegon());
            $sheet->getCell('P'. $i)->setValue($company->getKrs());
            $sheet->getCell('Q'. $i)->setValue($company->getNip());
            $sheet->getCell('R'. $i)->setValue($company->getDistricts());
            $sheet->getCell('S'. $i)->setValue($company->getHeadQuartersCity());
            $sheet->getCell('T'. $i)->setValue($company->getBusinessPhones());
            $sheet->getCell('U'. $i)->setValue($company->getBusinessEmails());
            $sheet->getCell('V'. $i)->setValue($company->getRevenue());
            $sheet->getCell('W'. $i)->setValue($company->getProfit());
            $sheet->getCell('X'. $i)->setValue($company->getGrowthYearToYear());
            $sheet->getCell('Y'. $i)->setValue($company->getCategories());
            $sheet->getCell('Z'. $i)->setValue($analysis->getLifePath());

            $sheet->getCell('AA'. $i)->setValue($analysis->getTheDrivingForce());
            $sheet->getCell('AB'. $i)->setValue($analysis->getTheMatrixOfExellence());
            $sheet->getCell('AC'. $i)->setValue($analysis->getTheMoralCode());
            $sheet->getCell('AD'. $i)->setValue($analysis->getGoalAndWants());
            $sheet->getCell('AE'. $i)->setValue($analysis->getBehavioursAndNeeds());
            $sheet->getCell('AF'. $i)->setValue($analysis->getSeekAndMindset());
            $sheet->getCell('AG'. $i)->setValue($analysis->getReactAndMotivationToAction());
            $sheet->getCell('AH'. $i)->setValue($analysis->getJoinsAndDesire());
            $sheet->getCell('AI'. $i)->setValue($analysis->getPolarisation());
            $sheet->getCell('AJ'. $i)->setValue($analysis->getExpression());
            $sheet->getCell('AK'. $i)->setValue($analysis->getKeyword());
            $sheet->getCell('AL'. $i)->setValue($analysis->getVisualSeeItIntuition());
            $sheet->getCell('AM'. $i)->setValue($analysis->getAuditoryHearItThinking());
            $sheet->getCell('AN'. $i)->setValue($analysis->getKinestericDoItSensation());
            $sheet->getCell('AO'. $i)->setValue($analysis->getEmotiveFeelItFeeling());
            $sheet->getCell('AP'. $i)->setValue($analysis->getInitializingAndAntithesis());
            $sheet->getCell('AQ'. $i)->setValue($analysis->getStabilizingAndSynthesis());
            $sheet->getCell('AR'. $i)->setValue($analysis->getFinishingThesis());
            $sheet->getCell('AS'. $i)->setValue($analysis->getDoerControl());
            $sheet->getCell('AT'. $i)->setValue($analysis->getThinkerOrder());
            $sheet->getCell('AU'. $i)->setValue($analysis->getWaterPeace());
            $sheet->getCell('AV'. $i)->setValue($analysis->getTalkerFun());
            $sheet->getCell('AW'. $i)->setValue($analysis->getTheValueOf());
            $sheet->getCell('AX'. $i)->setValue($analysis->getBelief());
            $sheet->getCell('AY'. $i)->setValue($analysis->getCommunication());
            $sheet->getCell('AZ'. $i)->setValue($analysis->getStyle());

            $sheet->getCell('BA'. $i)->setValue($analysis->getStrength());
            $sheet->getCell('BB'. $i)->setValue($analysis->getTactic());
            $sheet->getCell('BC'. $i)->setValue($analysis->getObjective());
            $sheet->getCell('BD'. $i)->setValue($analysis->getWorldOfAction());
            $sheet->getCell('BE'. $i)->setValue($analysis->getWorldOfMatter());
            $sheet->getCell('BF'. $i)->setValue($analysis->getWorldOfInformation());
            $sheet->getCell('BG'. $i)->setValue($analysis->getWorldOfFeeling());
            $sheet->getCell('BH'. $i)->setValue($analysis->getWorldOfFun());
            $sheet->getCell('BI'. $i)->setValue($analysis->getWorldOfUsability());
            $sheet->getCell('BJ'. $i)->setValue($analysis->getWorldOfRelations());
            $sheet->getCell('BK'. $i)->setValue($analysis->getWorldOfDesireAndPower());
            $sheet->getCell('BL'. $i)->setValue($analysis->getWorldOfSeekAndExplore());
            $sheet->getCell('BM'. $i)->setValue($analysis->getWorldOfCareer());
            $sheet->getCell('BN'. $i)->setValue($analysis->getWorldOfFuture());
            $sheet->getCell('BO'. $i)->setValue($analysis->getWorldOfSpirituality());
            $sheet->getCell('BP'. $i)->setValue($analysis->getP1S());
            $sheet->getCell('BQ'. $i)->setValue($analysis->getP2M());
            $sheet->getCell('BR'. $i)->setValue($analysis->getP3My());
            $sheet->getCell('BS'. $i)->setValue($analysis->getP4W());
            $sheet->getCell('BT'. $i)->setValue($analysis->getP5M());
            $sheet->getCell('BU'. $i)->setValue($analysis->getP6J());
            $sheet->getCell('BV'. $i)->setValue($analysis->getP7S());
            $sheet->getCell('BW'. $i)->setValue($analysis->getP8U());
            $sheet->getCell('BX'. $i)->setValue($analysis->getP9N());
            $sheet->getCell('BY'. $i)->setValue($analysis->getP10N());
            $sheet->getCell('BZ'. $i)->setValue($analysis->getPTNde());
        }

        $fileUniqName = $_ENV['BACKEND_UPLOADS_DIR'] .'/sheets/'.microtime().'.xlsx';
        $writer->save($fileUniqName);
        return ['file' => $_ENV['BACKEND_URL']. $fileUniqName];
    }
}
