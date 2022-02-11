<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:39
*/


namespace App\Controller\API\User;

use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Entity\User\UserProfile;
use App\Helpers\DateHelper\DateHelper;
use App\Modules\VirtualController\VirtualController;
use App\Repository\ImportedSubUsersRepository;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\HumanTraitServices\HumanTraitsService;
use App\Service\SubUserService\SubUserService;
use DateTime;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/me/sub-users")
 */
class SubUserCrudController extends VirtualController
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

    /**
     * @var SubUserService
     */
    private SubUserService $subUserService;

    /**
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param UserProfileRepository $userProfileRepository
     * @param TraitAnalysisRepository $traitAnalysisRepository
     * @param TraitItemRepository $traitItemRepository
     * @param TraitColorRepository $traitColorRepository
     * @param HumanTraitsService $humanTraitsService
     * @param SubUserService $subUserService
     */
    public function __construct(
        LoggerInterface $logger,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService,
        SubUserService $subUserService
    )
    {
        parent::__construct($serializer);
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;
        $this->subUserService = $subUserService;
    }

    /**
     * @Route (path="/add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $password = password_hash($request->get('password'), PASSWORD_DEFAULT);


        $profile = $request->get('profile');
        $company = $request->get('company');

        try {

            $userAuthorId = $this->user()->getUserId();
            $userId = Uuid::uuid4()->toString();

            if ($this->userRepository->exists($email)) {
                return $this->responseBuilder->addMessage('User already exists')
                    ->setStatus(Response::HTTP_ALREADY_REPORTED)
                    ->jsonResponse();
            }


            $subUserAcc = new User();
            $subUserAcc->profile = new UserProfile();
            $subUserAcc->company = new UserCompanyInfo();

            $subUserAcc
                ->setUserId($userId)
                ->setUserAuthorId($userAuthorId)
                ->setEmail($email)
                ->setPassword($password)
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();

            $subUserAcc->profile->setUserId($userId)->setCreatedAt();
            $subUserAcc->company->setUserId($userId)->setCreatedAt();

            $subUserAcc->profile->arrayToEntity($profile);
            $subUserAcc->company->arrayToEntity($company);

            $this->userRepository->save($subUserAcc);

            return $this->responseBuilder
                ->addObject($subUserAcc->profile)
                ->objectResponse();

        }
        catch (\InvalidArgumentException $ex){
            return $this->responseBuilder
                ->addMessage($ex->getMessage())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/update/{subUserId}", methods={"PUT"})
     * @param Request $request
     * @param $subUserId
     * @return JsonResponse
     */
    public function update(Request $request, $subUserId): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $profile = $request->get('profile');
        $company = $request->get('company');

        try {


            if (!$this->userRepository->existsAsSubUser($subUserId)) {
                return $this->responseBuilder->addMessage('User does not  exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->jsonResponse();
            }

            $subUserAcc = $this->userRepository->findSubUserById($subUserId);

            if (is_null($subUserAcc->profile)) {
                $subUserAcc->profile = new UserProfile();
            }
            if (is_null($subUserAcc->company)) {
                $subUserAcc->company = new UserCompanyInfo();
            }

            if (!empty($password) && $subUserAcc->getPassword() !== $password) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($email) {
                $subUserAcc
                    ->setEmail($email)
                    ->profile->setEmail($email);
            }
            if ($password) {
                $subUserAcc->setPassword($password);
            }

            $subUserAcc
                ->setUserId($subUserId)
                ->setUpdatedAt();
            $subUserAcc->profile
                ->setUserId($subUserId)
                ->setCreatedAt();;

            $subUserAcc->company
                ->setUserId($subUserId)
                ->setUpdatedAt();

            $subUserAcc->profile->arrayToEntity($profile);
            $subUserAcc->company->arrayToEntity($company);

            $this->userRepository->update($subUserAcc);

            $this->responseBuilder->addObject($subUserAcc);
            return $this->responseBuilder->objectResponse();
        }
        catch (\InvalidArgumentException $ex){
            return $this->responseBuilder
                ->addMessage($ex->getMessage())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/delete/{subUserId}", methods={"DELETE"})
     * @param string $subUserId
     * @return JsonResponse
     */
    public function delete(string $subUserId): JsonResponse
    {
        try {
            $accExists = $this->userRepository->existByUserId($subUserId);
            if (!$accExists) {
                return $this->responseBuilder->addMessage('User does not  exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->jsonResponse();
            }

            $user = $this->userRepository->findSubUserById($subUserId);
            $profile = $this->userProfileRepository->findSubUserById($subUserId);

            $this->userRepository->delete($user);
            $this->userProfileRepository->delete($profile);

            return $this->responseBuilder->addPayload([])
                ->setStatus(Response::HTTP_OK)
                ->jsonResponse();
        }
        catch (NoResultException){
            return $this->responseBuilder->addMessage('User does not exists')
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->jsonResponse();
        }
        catch (\Exception $ex){
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/all", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function mySubUsers(Request $request): JsonResponse
    {
        try {
            $startFrom = (int)$request->get('startFrom');
            $limit = (int)$request->get('limit');

            $userId = $this->user()->getUserId();
            $subUsers = $this->userRepository
                ->setStartFrom($startFrom)
                ->setLimit($limit)
                ->allSubUsers($userId, true);

            $this->responseBuilder->addPayload($subUsers);
            return $this->responseBuilder->jsonResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/{subUserId}", methods={"GET"})
     * @param string $subUserId
     * @return JsonResponse
     */
    public function getSubUser(string $subUserId): JsonResponse
    {
        try {
            $profile = $this->userRepository->findSubUserById($subUserId);
            return $this->responseBuilder->addObject($profile)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('SubUserCrudController::error', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/search/{keyword}", methods={"POST"})
     * @param string $keyword
     * @return JsonResponse
     */
    public function searchForSubUser(string $keyword): JsonResponse
    {
        try {
            $profile = $this->userProfileRepository->searchForSubUser($keyword);
            return $this->responseBuilder->addObject($profile)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('SubUserCrudController::error', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/filter-by/worlds/{world}", methods={"GET"})
     * @param Request $request
     * @param string $world
     * @return JsonResponse
     */
    public function filterByWorld(Request $request, string $world): JsonResponse
    {
        try{

            $authorUserId = $this->user()->getUserId();
            $startFrom = (int)$request->get('startFrom');
            $limit = (int)$request->get('limit');

            $result = $this->subUserService
                ->SubUserFilter()
                ->byWorld($authorUserId, $world)
                ->handle();


            return $this->responseBuilder->addPayload($result)->jsonResponse();
        }
        catch (\Exception $ex){
            $this->logger->error('SubUserCrudController::error', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/{userId}/set-avatar", methods={"POST"})
     * @param Request $request
     * @param string $userId
     * @return JsonResponse
     */
    public function setAvatar(Request $request, string $userId): JsonResponse
    {
        try {
            $avatarUrl = $request->get('avatar');

            $user = $this->userRepository->findUserById($userId);
            $user->profile->setAvatar($avatarUrl);

            $this->userProfileRepository->update($user->profile);
            $this->responseBuilder->addObject($user);
            return $this->responseBuilder->objectResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/import-from-sheet", methods={"POST"})
     * @param Request $request
     * @param SubUserService $subUserService
     * @param ImportedSubUsersRepository $importedSubUsersRepository
     * @return JsonResponse
     */
    public function importFromSheet(Request $request, SubUserService $subUserService, ImportedSubUsersRepository $importedSubUsersRepository): JsonResponse
    {
        try {
            $authorId = $this->user()->getUserId();
            $file = $request->files->get('file');

            if (!($file instanceof UploadedFile)) {
                return $this->responseBuilder->somethingWentWrong()->jsonResponse();
            }

            $subUserService->SubUserImporter()->import(
                $authorId,
                $_ENV['BACKEND_UPLOADS_DIR'] .'/sheets/',
                $file,
                $this->userRepository,
                $importedSubUsersRepository
            );

            return $this->responseBuilder->setStatus(Response::HTTP_OK)->jsonResponse();
        } catch (\Exception $ex) {
            $this->logger->error('SubUserCrudController::error', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/export-to-sheet", methods={"POST"})
     * @param SubUserService $subUserService
     * @return JsonResponse|Response
     */
    public function exportToSheet(SubUserService $subUserService,): JsonResponse|Response
    {
        try {
            $authorId = $this->user()->getUserId();
            $result = $subUserService->SubUserExporter()->export($authorId);
            return $this->responseBuilder->addPayload($result)->setStatus(Response::HTTP_OK)->jsonResponse();

        } catch (\Exception $ex) {
            $this->logger->error('SubUserCrudController::error', [$ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
