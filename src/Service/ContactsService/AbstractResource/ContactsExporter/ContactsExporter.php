<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 15:30
*/

namespace App\Service\ContactsService\AbstractResource\ContactsExporter;

use App\Entity\Contact\ContactProfile;
use App\Entity\User\User;
use App\Repository\ContactProfileRepository;
use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use Doctrine\ORM\AbstractQuery;
use GdImage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;

class ContactsExporter
{

    private Spreadsheet $spreadsheet;

    private Xlsx $writer;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
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
    private function calibrateImage(ContactProfile $contact, int $rowIndex): void {
        if (!is_null($contact->getPhoto())) {
            $sheet = $this->spreadsheet->getActiveSheet();
            $linkSegments = explode('.', $contact->getPhoto());
            $imgType = end($linkSegments);
            $createdImg = null;

            $avatarUrl =   str_replace($_ENV['BACKEND_URL'], '', $contact->getPhoto());

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
    private function createRows(array $contacts) {
        // Force COLUMN headers
        $contacts = array_merge([1,2], $contacts);
        for ($i = 0; $i < count($contacts); $i ++) {

            $contact = $contacts[$i];
            if (!($contact instanceof ContactProfile)) {
                continue;
            }

            $company = $contact->getContactCompany();
            $analysis = $contact->getTraitAnalysis();

            $row = [
                $contact->getFirstName(), $contact->getLastName(), $contact->getEmail(), $contact->getPhone(), $contact->getBirthDay(), $contact->getPlaceOfBirth(), $contact->getLinksToProfiles(),
                $contact->getNotes(), $contact->getCountry(), $company->getCompanyName(), $company->getCompanyWww(), $company->getCompanyIndustry(), $company->getWayToEarnMoney(),
                $company->getRegon(), $company->getKrs(), $company->getNip(), $company->getDistricts(), $company->getHeadQuartersCity(), $company->getBusinessPhones(), $company->getBusinessEmails(),
                $company->getRevenue(), $company->getProfit(), $company->getGrowthYearToYear(), $company->getCategories(), $analysis->getLifePath(),  $analysis->getTheDrivingForce(),
                $analysis->getTheMatrixOfExellence(), $analysis->getTheMoralCode(), $analysis->getGoalAndWants(), $analysis->getBehavioursAndNeeds(), $analysis->getSeekAndMindset(),
                $analysis->getReactAndMotivationToAction(), $analysis->getJoinsAndDesire(), $analysis->getPolarisation(), $analysis->getExpression(), $analysis->getKeyword(),
                $analysis->getVisualSeeItIntuition(), $analysis->getAuditoryHearItThinking(), $analysis->getKinestericDoItSensation(), $analysis->getEmotiveFeelItFeeling(), $analysis->getInitializingAndAntithesis(),
                $analysis->getStabilizingAndSynthesis(), $analysis->getFinishingThesis(), $analysis->getDoerControl(), $analysis->getThinkerOrder(), $analysis->getWaterPeace(), $analysis->getTalkerFun(), $analysis->getTheValueOf(),
                $analysis->getBelief(), $analysis->getCommunication(), $analysis->getStyle(),  $analysis->getStrength(), $analysis->getTactic(), $analysis->getObjective(), $analysis->getWorldOfAction(), $analysis->getWorldOfMatter(),
                $analysis->getWorldOfInformation(), $analysis->getWorldOfFeeling(), $analysis->getWorldOfFun(), $analysis->getWorldOfUsability(), $analysis->getWorldOfRelations(), $analysis->getWorldOfDesireAndPower(),
                $analysis->getWorldOfSeekAndExplore(), $analysis->getWorldOfCareer(), $analysis->getWorldOfFuture(), $analysis->getWorldOfSpirituality(), $analysis->getP1S(), $analysis->getP2M(), $analysis->getP3My(), $analysis->getP4W(),
                $analysis->getP5M(), $analysis->getP6J(), $analysis->getP7S(), $analysis->getP8U(), $analysis->getP9N(), $analysis->getP10N(), $analysis->getPTNde() ];

            $this->spreadsheet->getActiveSheet()->fromArray($row, NULL, 'B'.$i);
            $this->calibrateImage($contact, $i);
        }
    }

    /**
     * @param User $owner
     * @param ContactProfileRepository $contactProfileRepository
     * @return array
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(User $owner, ContactProfileRepository $contactProfileRepository): array
    {
        $contactsList = [];
        $ownerUserId = $owner->getUserId();
        $fileUniqName = $_ENV['BACKEND_UPLOADS_DIR'] .'/sheets/'.microtime().'.xlsx';

        $contacts = $contactProfileRepository->contactsSelectorQB()
            ->andWhere('contact.ownerUserId = :ownerUserId')
            ->setParameter('ownerUserId', $ownerUserId)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);


        $chunks = array_chunk($contacts, 3);

        foreach ($chunks as $chunk) {
            $contactsList[] = $contactProfileRepository->mapContactsToTraits($chunk);
        }


        $this->applyStyles();;
        $this->createHeaders();;
        $this->createRows($contactsList);

        $this->writer->save($fileUniqName);

        return ['file' => $_ENV['BACKEND_URL']. $fileUniqName];
    }
}
