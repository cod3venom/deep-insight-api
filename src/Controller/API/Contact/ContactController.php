<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 20.02.2022
 * Time: 23:10
*/


namespace App\Controller\API\Contact;

use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Modules\VirtualController\VirtualController;
use App\Repository\ContactProfileRepository;
use App\Service\ContactsService\ContactsService;
use App\Service\LoggerService\LoggerService;
use Doctrine\ORM\ORMException;
use PHPUnit\Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends VirtualController
{

    public function __construct(
        private SerializerInterface      $serializer,
        private LoggerService            $loggerService,
        private ContactProfileRepository $contactProfileRepository
    )
    {
        parent::__construct($this->serializer);
    }


    /**
     * @Route (path="/me/contacts", methods={"GET"})
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $user = $this->user();
            $contacts = $this->contactProfileRepository->all($user);
            return $this->responseBuilder->addPayload([$contacts])->jsonResponse();
        } catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/lazy", methods={"POST"})
     */
    public function lazyLoading(Request $request): JsonResponse
    {
        try {
            $start = (int)$request->get('start');
            $limit = (int)$request->get('limit');

            $user = $this->user();
            $contacts = $this->contactProfileRepository->setStartFrom($start)->setLimit($limit)->all($user);
            return $this->responseBuilder->addObject($contacts)->objectResponse();
        } catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/{contactId}", methods={"GET"})
     */
    public function findById(Request $request, string $contactId): JsonResponse
    {
        try {

            $contact = $this->contactProfileRepository->findContactPackById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addPayload([])->addMessage('Contact does not exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)->objectResponse();
            }

            return $this->responseBuilder->addObject($contact)->setStatus(Response::HTTP_OK)->objectResponse();
        } catch (\Doctrine\DBAL\Driver\Exception | \Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/search", methods={"GET"})
     */
    public function searchByText(Request $request): JsonResponse
    {
        try {
            $searchText = (string)$request->get('searchText');
            if (!$searchText) {
                return $this->responseBuilder->addPayload([])->addMessage('Searching text cant be empty')
                    ->setStatus(Response::HTTP_NOT_FOUND)->objectResponse();
            }


            $profile = $this->contactProfileRepository->search($this->user(), $searchText);
            return $this->responseBuilder->addObject($profile)->setStatus(Response::HTTP_OK)->objectResponse();
        } catch (\Doctrine\DBAL\Driver\Exception | \Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/filter-by/world", methods={"GET"})
     */
    public function filterByWorlds(Request $request): JsonResponse
    {
        try {
            $world = (string)$request->get('world');
            $result = $this->contactProfileRepository->filterByWorld($this->user(), $world);
            return $this->responseBuilder->addObject($result)->objectResponse();
        } catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/set-avatar/{contactId}", methods={"POST"})
     * @param Request $request
     * @param string $contactId
     * @return JsonResponse
     */
    public function setAvatar(Request $request, string $contactId): JsonResponse
    {
        try {
            $avatarUrl = $request->get('avatar');
            $contact = $this->contactProfileRepository->findContactById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }

            $contact->setPhoto($avatarUrl);

            $this->contactProfileRepository->update($contact);
            return $this->responseBuilder->addObject($contact)->objectResponse();
        }
        catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'setAvatar', [$this->user(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $contactProfile = $request->get('contact');
            $contactCompany = $request->get('company');


            $contact = new ContactProfile();
            $company = new ContactCompany();

            $contact->arrayToEntity($contactProfile);
            $company->arrayToEntity($contactCompany);

            $contact = $this->contactProfileRepository->create($this->user(), $contact, $company);
            return $this->responseBuilder->addObject($contact)->objectResponse();
        } catch (ORMException | ReflectionException | Exception $ex) {
            $this->loggerService->error('ContactController', 'Create', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/me/contacts/edit/{contactId}", methods={"POST"})
     * @param Request $request
     * @param string $contactId
     * @return JsonResponse
     */
    public function edit(Request $request, string $contactId, SerializerInterface $serializer): JsonResponse
    {
        try {
            $contactProfile = $request->get('contact');

            $contact = $this->contactProfileRepository->findContactById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }


            $test = $serializer->deserialize($request->getContent(), ContactProfile::class, 'json', [
                'object_to_populate' => $contactProfile, // this still needs to be set, without the "deep_"
                'deep_object_to_populate' => true,
            ]);

            $this->contactProfileRepository->edit($contact);
            return $this->responseBuilder->addObject($contact)->objectResponse();

        } catch (ORMException | ReflectionException | Exception $ex) {
            $this->loggerService->error('ContactController', 'Edit', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/remove/{contactId}", methods={"DELETE"})
     * @param Request $request
     * @param string $contactId
     * @return JsonResponse
     */
    public function remove(Request $request, string $contactId): JsonResponse
    {
        try {
            $contact = $this->contactProfileRepository->findContactPackById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }
            $this->contactProfileRepository->remove($contact);
            return $this->responseBuilder->addPayload([])->jsonResponse();
        } catch (ORMException | ReflectionException | Exception $ex) {
            $this->loggerService->error('ContactController', 'Remove', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/me/contacts/import", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function import(Request $request): JsonResponse
    {
        try {

            $file = $request->files->get('file');
            $targetDir = $_ENV['BACKEND_UPLOADS_DIR'] . '/sheets/';

            if (!($file instanceof UploadedFile)) {
                return $this->responseBuilder->somethingWentWrong()->jsonResponse();
            }

            $this->contactProfileRepository->importFromFile($targetDir, $this->user(), $file);
            return $this->responseBuilder->setStatus(Response::HTTP_OK)->jsonResponse();
        } catch (Exception $ex) {
            $this->loggerService->error('ContactController', 'Import', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/export", methods={"GET"})
     * @return JsonResponse
     */
    public function export(): JsonResponse
    {
        try {

            $result = $this->contactProfileRepository->exportToFile($this->user());
            return $this->responseBuilder->addPayload($result)->jsonResponse();
        } catch (
        \PhpOffice\PhpSpreadsheet\Writer\Exception |
        \PhpOffice\PhpSpreadsheet\Exception |
        Exception $ex
        ) {
            $this->loggerService->error('ContactController', 'Export', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
