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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(string $authorUserId) {

        $this->schemaBuilder = new SchemaBuilder();
        $spreadSheet = new Spreadsheet();
        $drawing = new Drawing();
        $writer = new Xlsx($spreadSheet);

        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setTitle('Deep Insight Discovery - Export');

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

        $subUsers = $this
            ->userRepository
            ->setStartFrom(-1)
            ->allSubUsers($authorUserId);

        $counter = 1;
        foreach ($subUsers as $subUser) {

            if (!($subUser instanceof User)) {
                continue;
            }

            $birthDay = date_format($subUser->profile->getBirthDay(), UserProfile::BirthDayFormat);
            $userTraits = $this->traitAnalysisRepository->findTraitsByBirthDay($birthDay);

            $profile = &$subUser->profile;
            $company = &$subUser->company;
            $analysis = &$userTraits;
            $counter += 1;




            if (file_exists($profile->getAvatar())) {
                $drawing->setPath($profile->getAvatar()); /* put your path and image here */
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);
            }
            $sheet->getCell('A1'. $counter)->setValue($profile->getAvatar());
            $sheet->getCell('B1'. $counter)->setValue($profile->getFirstName());
            $sheet->getCell('C1'. $counter)->setValue($profile->getLastName());
            $sheet->getCell('D1'. $counter)->setValue($subUser->getEmail());
            $sheet->getCell('E1'. $counter)->setValue($profile->getPhone());
            $sheet->getCell('F1'. $counter)->setValue($profile->getBirthDay());
            $sheet->getCell('G1'. $counter)->setValue($profile->getPlaceOfBirth());
            $sheet->getCell('H1'. $counter)->setValue($profile->getLinksToProfiles());
            $sheet->getCell('I1'. $counter)->setValue($profile->getNotesDescriptionsComments());
            $sheet->getCell('J1'. $counter)->setValue($profile->getCountry());

            $sheet->getCell('K1'. $counter)->setValue($company->getCompanyName());
            $sheet->getCell('L1'. $counter)->setValue($company->getCompanyWww());
            $sheet->getCell('M1'. $counter)->setValue($company->getCompanyIndustry());
            $sheet->getCell('N1'. $counter)->setValue($company->getWayToEarnMoney());
            $sheet->getCell('O1'. $counter)->setValue($company->getRegon());
            $sheet->getCell('P1'. $counter)->setValue($company->getKrs());
            $sheet->getCell('Q1'. $counter)->setValue($company->getNip());
//            $sheet->getCell('R1'. $counter)->setValue('NIP');
            $sheet->getCell('S1'. $counter)->setValue($company->getDistricts());
            $sheet->getCell('T1'. $counter)->setValue($company->getHeadQuartersCity());
            $sheet->getCell('U1'. $counter)->setValue($company->getBusinessPhones());
            $sheet->getCell('V1'. $counter)->setValue($company->getBusinessEmails());
            $sheet->getCell('W1'. $counter)->setValue($company->getRevenue());
            $sheet->getCell('X1'. $counter)->setValue($company->getProfit());
            $sheet->getCell('Y1'. $counter)->setValue($company->getGrowthYearToYear());
            $sheet->getCell('Z1'. $counter)->setValue($company->getCategories());

            $sheet->getCell('AA1'. $counter)->setValue($analysis->getLifePath());
            $sheet->getCell('AB1'. $counter)->setValue($analysis->getTheDrivingForce());
            $sheet->getCell('AC1'. $counter)->setValue($analysis->getTheMatrixOfExellence());
            $sheet->getCell('AD1'. $counter)->setValue($analysis->getTheMoralCode());
            $sheet->getCell('AE1'. $counter)->setValue($analysis->getGoalAndWants());
            $sheet->getCell('AF1'. $counter)->setValue($analysis->getBehavioursAndNeeds());
            $sheet->getCell('AG1'. $counter)->setValue($analysis->getSeekAndMindset());
            $sheet->getCell('AI1'. $counter)->setValue($analysis->getReactAndMotivationToAction());

            $sheet->getCell('AJ1'. $counter)->setValue($analysis->getJoinsAndDesire());
            $sheet->getCell('AK1'. $counter)->setValue($analysis->getPolarisation());
            $sheet->getCell('AL1'. $counter)->setValue($analysis->getExpression());
            $sheet->getCell('AM1'. $counter)->setValue($analysis->getKeyword());
            $sheet->getCell('AN1'. $counter)->setValue($analysis->getVisualSeeItIntuition());
            $sheet->getCell('AO1'. $counter)->setValue($analysis->getAuditoryHearItThinking());
            $sheet->getCell('AP1'. $counter)->setValue($analysis->getKinestericDoItSensation());
            $sheet->getCell('AQ1'. $counter)->setValue($analysis->getEmotiveFeelItFeeling());
            $sheet->getCell('AR1'. $counter)->setValue($analysis->getInitializingAndAntithesis());
            $sheet->getCell('AS1'. $counter)->setValue($analysis->getStabilizingAndSynthesis());
            $sheet->getCell('AT1'. $counter)->setValue($analysis->getFinishingThesis());
            $sheet->getCell('AU1'. $counter)->setValue($analysis->getDoerControl());
            $sheet->getCell('AV1'. $counter)->setValue($analysis->getThinkerOrder());
            $sheet->getCell('AW1'. $counter)->setValue($analysis->getWaterPeace());
            $sheet->getCell('AX1'. $counter)->setValue($analysis->getTalkerFun());
            $sheet->getCell('AY1'. $counter)->setValue($analysis->getTheValueOf());
            $sheet->getCell('AZ1'. $counter)->setValue($analysis->getTheValueOf());

            $sheet->getCell('BA1'. $counter)->setValue($analysis->getBelief());
            $sheet->getCell('BB1'. $counter)->setValue($analysis->getCommunication());
            $sheet->getCell('BC1'. $counter)->setValue($analysis->getStyle());
            $sheet->getCell('BD1'. $counter)->setValue($analysis->getStrength());
            $sheet->getCell('BE1'. $counter)->setValue($analysis->getTactic());
            $sheet->getCell('BF1'. $counter)->setValue($analysis->getObjective());
            $sheet->getCell('BG1'. $counter)->setValue($analysis->getWorldOfAction());
            $sheet->getCell('BH1'. $counter)->setValue($analysis->getWorldOfMatter());
            $sheet->getCell('BI1'. $counter)->setValue($analysis->getWorldOfInformation());

            $sheet->getCell('BK1'. $counter)->setValue($analysis->getWorldOfFeeling());
            $sheet->getCell('BL1'. $counter)->setValue($analysis->getWorldOfFun());
            $sheet->getCell('BM1'. $counter)->setValue($analysis->getWorldOfUsability());
            $sheet->getCell('BN1'. $counter)->setValue($analysis->getWorldOfRelations());
            $sheet->getCell('BO1'. $counter)->setValue($analysis->getWorldOfDesireAndPower());
            $sheet->getCell('BP1'. $counter)->setValue($analysis->getWorldOfSeekAndExplore());
            $sheet->getCell('BQ1'. $counter)->setValue($analysis->getWorldOfCareer());
            $sheet->getCell('BR1'. $counter)->setValue($analysis->getWorldOfFuture());
            $sheet->getCell('BS1'. $counter)->setValue($analysis->getWorldOfSpirituality());


            $sheet->getCell('BT1'. $counter)->setValue($analysis->getP1S());
            $sheet->getCell('BU1'. $counter)->setValue($analysis->getP2M());
            $sheet->getCell('BV1'. $counter)->setValue($analysis->getP3My());
            $sheet->getCell('BW1'. $counter)->setValue($analysis->getP4W());
            $sheet->getCell('BX1'. $counter)->setValue($analysis->getP5M());
            $sheet->getCell('BY1'. $counter)->setValue($analysis->getP6J());
            $sheet->getCell('BZ1'. $counter)->setValue($analysis->getP7S());
            $sheet->getCell('CA1'. $counter)->setValue($analysis->getP8U());
            $sheet->getCell('CB1'. $counter)->setValue($analysis->getP9N());
            $sheet->getCell('CC1'. $counter)->setValue($analysis->getP10N());
            $sheet->getCell('CD1'. $counter)->setValue($analysis->getPTNde());
        }

        return $writer;
    }
}
