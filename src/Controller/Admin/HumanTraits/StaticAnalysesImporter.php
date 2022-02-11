<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 07.02.2022
 * Time: 12:58
*/

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Form\Type\SheetImportType;
use App\Modules\Sheet\SheetReader;
use App\Modules\StringBuilder\StringBuilder;
use App\Repository\TraitAnalysisRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use UnexpectedValueException;
use Exception;

class StaticAnalysesImporter extends AbstractController
{
    /**
     *
     */
    private const EXTENSIONS_WHITELIST = ['xls', 'xlsx', 'csv', 'txt'];

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var TraitAnalysisRepository
     */
    private TraitAnalysisRepository $traitAnalysisRepository;


    public function __construct(
        LoggerInterface $logger,
        TraitAnalysisRepository $traitAnalysisRepository
    ) {
        $this->logger = $logger;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
    }

    /**
     * @Route("/admin/analysis/import-from-sheet", name="admin_import_analysis_from_sheet")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function __invoke(Request $request, SluggerInterface $slugger): Response
    {
        $message = '';
        $form = $this->createForm(SheetImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if (!($file instanceof UploadedFile)) {
                return $this->render('forms/sheet-import-form/sheetImportForm.html.twig', [ 'message' => 'Something went wrong', 'new_form' => $form->createView()]);
            }

            if (!in_array($file->guessExtension(), self::EXTENSIONS_WHITELIST)) {
                return $this->render('forms/sheet-import-form/sheetImportForm.html.twig', [ 'message' => 'Only xlsx, xls and csv extensions are allowed',
                    'new_form' => $form->createView()
                ]);
            }

            try {

                $fullPath = $this->uploadFile($this->getParameter('sheets_upload_dir'), $file, $slugger);
                if (empty($fullPath) || !file_exists($fullPath)) {
                    $message = 'Something went wrong during the uploading process';
                }
                else {
                    $this->startImportingProcess($fullPath);
                }

            } catch (Exception $exception) {
                $message = $exception->getMessage();
                $this->logger->error('Excel importer', [$exception]);
            }
        }

        return $this->render('forms/sheet-import-form/sheetImportForm.html.twig', [
            'new_form' => $form->createView(),
            'message' => $message
        ]);
    }

    /**
     * @param string $uploadDir
     * @param UploadedFile $uploadedFile
     * @param SluggerInterface $slugger
     * @return string
     */
    private function uploadFile(string $uploadDir, UploadedFile $uploadedFile, SluggerInterface $slugger): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newName = (new StringBuilder())
            ->append($slugger->slug($originalFilename))
            ->append('-')
            ->append(uniqid())
            ->append('.')
            ->append($uploadedFile->guessExtension());

        try {
            $uploadedFile->move($uploadDir, $newName);
            return $uploadDir . '/' . $newName;

        } catch (Exception $e) {
            $this->logger->error('Excel importer', [$e]);
            throw new UnexpectedValueException('Something went wrong: ' . $e->getMessage());
        }
    }

    private function startImportingProcess(string $filePath): array
    {
        $fullData = [];
        $fileParts = explode('.', $filePath);
        $extension =  end($fileParts);
        switch ($extension) {
            case 'csv':
            case 'txt':
                $fullData = SheetReader::csvReader($filePath)->getActiveSheet()->toArray();;
                break;
            case 'xsl':
                $fullData = SheetReader::xlsRead($filePath)->getActiveSheet()->toArray();
                break;
            case 'xlsx':
                $fullData = SheetReader::xlsxRead($filePath)->getActiveSheet()->toArray();;
        }

        for ($i = 0; $i < count($fullData); $i++) {
            if ($i === 0) {
                continue;
            }
            $entry = $fullData[$i];
            $staticAnalysisEntity = new TraitAnalysis();
            $staticAnalysisEntity->setBirthDay($entry[array_search('birth_day', $fullData[0])]);
            $staticAnalysisEntity->setLifePath($entry[array_search('life_path', $fullData[0])]);
            $staticAnalysisEntity->setTheDrivingForce($entry[array_search('the_driving_force', $fullData[0])]);
            $staticAnalysisEntity->setTheMatrixOfExellence($entry[array_search('the_matrix_of_exellence', $fullData[0])]);
            $staticAnalysisEntity->setTheMoralCode($entry[array_search('the_moral_code', $fullData[0])]);
            $staticAnalysisEntity->setGoalAndWants($entry[array_search('goal_and_wants', $fullData[0])]);
            $staticAnalysisEntity->setBehavioursAndNeeds($entry[array_search('behaviours_and_needs', $fullData[0])]);
            $staticAnalysisEntity->setSeekAndMindset($entry[array_search('seek_and_mindset', $fullData[0])]);
            $staticAnalysisEntity->setReactAndMotivationToAction($entry[array_search('react_and_motivation_to_action', $fullData[0])]);
            $staticAnalysisEntity->setJoinsAndDesire($entry[array_search('joins_and_desire', $fullData[0])]);
            $staticAnalysisEntity->setPolarisation($entry[array_search('polarisation', $fullData[0])]);
            $staticAnalysisEntity->setExpression($entry[array_search('expression', $fullData[0])]);
            $staticAnalysisEntity->setKeyword($entry[array_search('keyword', $fullData[0])]);
            $staticAnalysisEntity->setVisualSeeItIntuition($entry[array_search('visual_see_it_intuition', $fullData[0])]);
            $staticAnalysisEntity->setAuditoryHearItThinking($entry[array_search('auditory_hear_it_thinking', $fullData[0])]);
            $staticAnalysisEntity->setKinestericDoItSensation($entry[array_search('kinesteric_do_it_sensation', $fullData[0])]);
            $staticAnalysisEntity->setEmotiveFeelItFeeling($entry[array_search('emotive_feel_it_feeling', $fullData[0])]);
            $staticAnalysisEntity->setInitializingAndAntithesis($entry[array_search('initializing_and_antithesis', $fullData[0])]);
            $staticAnalysisEntity->setStabilizingAndSynthesis($entry[array_search('stabilizing_and_synthesis', $fullData[0])]);
            $staticAnalysisEntity->setFinishingThesis($entry[array_search('finishing_thesis', $fullData[0])]);
            $staticAnalysisEntity->setDoerControl($entry[array_search('doer_control', $fullData[0])]);
            $staticAnalysisEntity->setThinkerOrder($entry[array_search('thinker_order', $fullData[0])]);
            $staticAnalysisEntity->setWaterPeace($entry[array_search('water_peace', $fullData[0])]);
            $staticAnalysisEntity->setTalkerFun($entry[array_search('talker_fun', $fullData[0])]);
            $staticAnalysisEntity->setTheValueOf($entry[array_search('the_value_of', $fullData[0])]);
            $staticAnalysisEntity->setBelief($entry[array_search('belief', $fullData[0])]);
            $staticAnalysisEntity->setCommunication($entry[array_search('communication', $fullData[0])]);
            $staticAnalysisEntity->setStyle($entry[array_search('style', $fullData[0])]);
            $staticAnalysisEntity->setStrength($entry[array_search('strength', $fullData[0])]);
            $staticAnalysisEntity->setReward($entry[array_search('reward', $fullData[0])]);
            $staticAnalysisEntity->setTactic($entry[array_search('tactic', $fullData[0])]);
            $staticAnalysisEntity->setObjective($entry[array_search('objective', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfMatter($entry[array_search('world_of_matter', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfInformation($entry[array_search('world_of_information', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfFeeling($entry[array_search('world_of_feeling', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfFun($entry[array_search('world_of_fun', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfUsability($entry[array_search('world_of_usability', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfRelations($entry[array_search('world_of_relations', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfDesireAndPower($entry[array_search('world_of_desire_and_power', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfSeekAndExplore($entry[array_search('world_of_seek_and_explore', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfCareer($entry[array_search('world_of_career', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfFuture($entry[array_search('world_of_future', $fullData[0])]);
            $staticAnalysisEntity->setWorldOfSpirituality($entry[array_search('world_of_spirituality', $fullData[0])]);
            $staticAnalysisEntity->setP1S($entry[array_search('p1_s', $fullData[0])]);
            $staticAnalysisEntity->setP2M($entry[array_search('p2_m', $fullData[0])]);
            $staticAnalysisEntity->setP3My($entry[array_search('p3_my', $fullData[0])]);
            $staticAnalysisEntity->setP4W($entry[array_search('p4_w', $fullData[0])]);
            $staticAnalysisEntity->setP5M($entry[array_search('p5_m', $fullData[0])]);
            $staticAnalysisEntity->setP6J($entry[array_search('p6_j', $fullData[0])]);
            $staticAnalysisEntity->setP7S($entry[array_search('p7_s', $fullData[0])]);
            $staticAnalysisEntity->setP8U($entry[array_search('p8_u', $fullData[0])]);
            $staticAnalysisEntity->setP9N($entry[array_search('p9_n', $fullData[0])]);
            $staticAnalysisEntity->setP10N($entry[array_search('p10_n', $fullData[0])]);
            $staticAnalysisEntity->setPTNde($entry[array_search('ptnde', $fullData[0])]);

            $this->traitAnalysisRepository->save($staticAnalysisEntity);;


        }
        return $fullData;
    }

}
