<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 15:30
*/

namespace App\Service\SubUserService\AbstractResource\SubUsersExporter\Exceptions;

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
        $drawing = new MemoryDrawing();
        $writer = new Xlsx($spreadSheet);

        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setTitle('Deep Insight Discovery - Export');

        $sheet->getStyle('A1:CD1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1f49ff');
        $sheet->getStyle('A1:CD1')->getFont()->getColor()->setRGB('ffffff');
        $sheet ->getRowDimension(1)->setRowHeight(50);

        $sheet->getCell('A1')->setValue('Photo');
        $sheet->getCell('B1')->setValue('First Name');
        $sheet->getCell('C1')->setValue('Last Name');
        $sheet->getCell('D1')->setValue('Email');
        $sheet->getCell('E1')->setValue('Phone');
        $sheet->getCell('F1')->setValue('BirthDay');
        $sheet->getCell('G1')->setValue('Place of birth');
        $sheet->getCell('H1')->setValue('Links to profiles');
        $sheet->getCell('I1')->setValue('Notes, Descriptions, Comments');
        $sheet->getCell('J1')->setValue('Country');

        $sheet->getCell('K1')->setValue('Company name');
        $sheet->getCell('L1')->setValue('Company WWW');
        $sheet->getCell('M1')->setValue('Company Industry');
        $sheet->getCell('N1')->setValue('Way to Earn');
        $sheet->getCell('O1')->setValue('REGON');
        $sheet->getCell('P1')->setValue('KRS');
        $sheet->getCell('Q1')->setValue('NIP');
        $sheet->getCell('R1')->setValue('NIP');
        $sheet->getCell('S1')->setValue('Districts');
        $sheet->getCell('T1')->setValue('Head quarters');
        $sheet->getCell('U1')->setValue('Business phones');
        $sheet->getCell('V1')->setValue('Business e-mails');
        $sheet->getCell('W1')->setValue('Revenue');
        $sheet->getCell('X1')->setValue('Profit');
        $sheet->getCell('Y1')->setValue('Growth year to year');
        $sheet->getCell('Z1')->setValue('Categories');


        $sheet->getCell('AA1')->setValue('Life path');
        $sheet->getCell('AB1')->setValue('The driving force');
        $sheet->getCell('AC1')->setValue('The Matrix of Excellence');
        $sheet->getCell('AD1')->setValue('The Moral Code');
        $sheet->getCell('AE1')->setValue('Goals & Wants');
        $sheet->getCell('AF1')->setValue('Behaviors & Needs');
        $sheet->getCell('AG1')->setValue('Seeks & Mindset');
        $sheet->getCell('AI1')->setValue('React & Motive to Action');

        $sheet->getCell('AJ1')->setValue('Joins & Desire');
        $sheet->getCell('AK1')->setValue('Polarisation');
        $sheet->getCell('AL1')->setValue('Expression');
        $sheet->getCell('AM1')->setValue('Keyword');
        $sheet->getCell('AN1')->setValue('Visual | See it | Intuition');
        $sheet->getCell('AO1')->setValue('Auditory | Hear it | Thinking');
        $sheet->getCell('AP1')->setValue('Kinesteric | Do it | Sensation');
        $sheet->getCell('AQ1')->setValue('Emotive | Feel it | Feeling');
        $sheet->getCell('AR1')->setValue('Initializing & antithesis');
        $sheet->getCell('AS1')->setValue('Stabilizing & synthesis');
        $sheet->getCell('AT1')->setValue('Finishing & thesis');
        $sheet->getCell('AU1')->setValue('Doer - Control');
        $sheet->getCell('AV1')->setValue('Thinker - Order');
        $sheet->getCell('AW1')->setValue('Water - Peace');
        $sheet->getCell('AX1')->setValue('Talker - Fun');
        $sheet->getCell('AY1')->setValue('The Value of');
        $sheet->getCell('AZ1')->setValue('Belief');

        $sheet->getCell('BA1')->setValue('Belief');
        $sheet->getCell('BB1')->setValue('Communication');
        $sheet->getCell('BC1')->setValue('Style');
        $sheet->getCell('BD1')->setValue('Strength');
        $sheet->getCell('BE1')->setValue('Tactic');
        $sheet->getCell('BF1')->setValue('Objective');
        $sheet->getCell('BG1')->setValue('World of Action');
        $sheet->getCell('BH1')->setValue('World of Matter');
        $sheet->getCell('BI1')->setValue('World of Information');

        $sheet->getCell('BK1')->setValue('World of Feeling');
        $sheet->getCell('BL1')->setValue('World of Fun');
        $sheet->getCell('BM1')->setValue('World of Usability');
        $sheet->getCell('BN1')->setValue('World of Relations');
        $sheet->getCell('BO1')->setValue('World of Desire&Power');
        $sheet->getCell('BP1')->setValue('World of Seek&Explore');
        $sheet->getCell('BQ1')->setValue('World of Career');
        $sheet->getCell('BR1')->setValue('World of Future');
        $sheet->getCell('BS1')->setValue('World of Spirituality');


        $sheet->getCell('BT1')->setValue('P1S');
        $sheet->getCell('BU1')->setValue('P2M');
        $sheet->getCell('BV1')->setValue('P3MY');
        $sheet->getCell('BW1')->setValue('P4W');
        $sheet->getCell('BX1')->setValue('P5M');
        $sheet->getCell('BY1')->setValue('P6J');
        $sheet->getCell('BZ1')->setValue('P7S');
        $sheet->getCell('CA1')->setValue('P8U');
        $sheet->getCell('CB1')->setValue('P9N');
        $sheet->getCell('CC1')->setValue('P10N');
        $sheet->getCell('CD1')->setValue('PTNde');

        $emptyIndexes = [1, 2];
        $subUsers = $this
            ->userRepository
            ->setStartFrom(-1)
            ->allSubUsers($authorUserId);

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
               $drawing->setImageResource(imagecreatefrompng($profile->getAvatar()));
               $drawing->setCoordinates($sheet->getCell('A'.$i)->getCoordinate());
               $drawing->setWidthAndHeight(50, 50);
               $drawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
               $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
               $drawing->setWorksheet($sheet);

               $sheet
                ->getRowDimension($i)->setRowHeight(50);
           }

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
            $sheet->getCell('S'. $i)->setValue($company->getDistricts());
            $sheet->getCell('T'. $i)->setValue($company->getHeadQuartersCity());
            $sheet->getCell('U'. $i)->setValue($company->getBusinessPhones());
            $sheet->getCell('V'. $i)->setValue($company->getBusinessEmails());
            $sheet->getCell('W'. $i)->setValue($company->getRevenue());
            $sheet->getCell('X'. $i)->setValue($company->getProfit());
            $sheet->getCell('Y'. $i)->setValue($company->getGrowthYearToYear());
            $sheet->getCell('Z'. $i)->setValue($company->getCategories());

            $sheet->getCell('AA'. $i)->setValue($analysis->getLifePath());
            $sheet->getCell('AB'. $i)->setValue($analysis->getTheDrivingForce());
            $sheet->getCell('AC'. $i)->setValue($analysis->getTheMatrixOfExellence());
            $sheet->getCell('AD'. $i)->setValue($analysis->getTheMoralCode());
            $sheet->getCell('AE'. $i)->setValue($analysis->getGoalAndWants());
            $sheet->getCell('AF'. $i)->setValue($analysis->getBehavioursAndNeeds());
            $sheet->getCell('AG'. $i)->setValue($analysis->getSeekAndMindset());
            $sheet->getCell('AI'. $i)->setValue($analysis->getReactAndMotivationToAction());

            $sheet->getCell('AJ'. $i)->setValue($analysis->getJoinsAndDesire());
            $sheet->getCell('AK'. $i)->setValue($analysis->getPolarisation());
            $sheet->getCell('AL'. $i)->setValue($analysis->getExpression());
            $sheet->getCell('AM'. $i)->setValue($analysis->getKeyword());
            $sheet->getCell('AN'. $i)->setValue($analysis->getVisualSeeItIntuition());
            $sheet->getCell('AO'. $i)->setValue($analysis->getAuditoryHearItThinking());
            $sheet->getCell('AP'. $i)->setValue($analysis->getKinestericDoItSensation());
            $sheet->getCell('AQ'. $i)->setValue($analysis->getEmotiveFeelItFeeling());
            $sheet->getCell('AR'. $i)->setValue($analysis->getInitializingAndAntithesis());
            $sheet->getCell('AS'. $i)->setValue($analysis->getStabilizingAndSynthesis());
            $sheet->getCell('AT'. $i)->setValue($analysis->getFinishingThesis());
            $sheet->getCell('AU'. $i)->setValue($analysis->getDoerControl());
            $sheet->getCell('AV'. $i)->setValue($analysis->getThinkerOrder());
            $sheet->getCell('AW'. $i)->setValue($analysis->getWaterPeace());
            $sheet->getCell('AX'. $i)->setValue($analysis->getTalkerFun());
            $sheet->getCell('AY'. $i)->setValue($analysis->getTheValueOf());
            $sheet->getCell('AZ'. $i)->setValue($analysis->getTheValueOf());

            $sheet->getCell('BA'. $i)->setValue($analysis->getBelief());
            $sheet->getCell('BB'. $i)->setValue($analysis->getCommunication());
            $sheet->getCell('BC'. $i)->setValue($analysis->getStyle());
            $sheet->getCell('BD'. $i)->setValue($analysis->getStrength());
            $sheet->getCell('BE'. $i)->setValue($analysis->getTactic());
            $sheet->getCell('BF'. $i)->setValue($analysis->getObjective());
            $sheet->getCell('BG'. $i)->setValue($analysis->getWorldOfAction());
            $sheet->getCell('BH'. $i)->setValue($analysis->getWorldOfMatter());
            $sheet->getCell('BI'. $i)->setValue($analysis->getWorldOfInformation());

            $sheet->getCell('BK'. $i)->setValue($analysis->getWorldOfFeeling());
            $sheet->getCell('BL'. $i)->setValue($analysis->getWorldOfFun());
            $sheet->getCell('BM'. $i)->setValue($analysis->getWorldOfUsability());
            $sheet->getCell('BN'. $i)->setValue($analysis->getWorldOfRelations());
            $sheet->getCell('BO'. $i)->setValue($analysis->getWorldOfDesireAndPower());
            $sheet->getCell('BP'. $i)->setValue($analysis->getWorldOfSeekAndExplore());
            $sheet->getCell('BQ'. $i)->setValue($analysis->getWorldOfCareer());
            $sheet->getCell('BR'. $i)->setValue($analysis->getWorldOfFuture());
            $sheet->getCell('B1'. $i)->setValue($analysis->getWorldOfSpirituality());


            $sheet->getCell('BT'. $i)->setValue($analysis->getP1S());
            $sheet->getCell('BU'. $i)->setValue($analysis->getP2M());
            $sheet->getCell('BV'. $i)->setValue($analysis->getP3My());
            $sheet->getCell('BW'. $i)->setValue($analysis->getP4W());
            $sheet->getCell('BX'. $i)->setValue($analysis->getP5M());
            $sheet->getCell('BY'. $i)->setValue($analysis->getP6J());
            $sheet->getCell('BZ'. $i)->setValue($analysis->getP7S());
            $sheet->getCell('CA'. $i)->setValue($analysis->getP8U());
            $sheet->getCell('CB'. $i)->setValue($analysis->getP9N());
            $sheet->getCell('CC'. $i)->setValue($analysis->getP10N());
            $sheet->getCell('CD'. $i)->setValue($analysis->getPTNde());
        }

        $fileUniqName = $_ENV['BACKEND_UPLOADS_DIR'] .'/sheets/'.microtime().'.xlsx';
        $writer->save($fileUniqName);
        return ['file' => $_ENV['BACKEND_URL']. $fileUniqName];
    }
}
