<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 20.02.2022
 * Time: 23:10
*/


namespace App\Controller\API\Contact;

use App\DAO\RequestTOContactProfile;
use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Modules\VirtualController\VirtualController;
use App\Repository\ContactProfileRepository;
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

    /**
     * @param SerializerInterface $serializer
     * @param LoggerService $loggerService
     * @param ContactProfileRepository $contactProfileRepository
     */
    public function __construct
    (
        private SerializerInterface      $serializer,
        private LoggerService            $loggerService,
        private ContactProfileRepository $contactProfileRepository
    ) {
        parent::__construct($this->serializer);
    }


    /**
     * @Route (path="/me/contacts", methods={"GET"})
     * @return JsonResponse
     */
    public function all(): JsonResponse
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
     * @param Request $request
     * @return JsonResponse
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
     * @param Request $request
     * @param string $contactId
     * @return JsonResponse
     */
    public function findById(Request $request, string $contactId): JsonResponse
    {
        try {

            $contact = $this->contactProfileRepository->findContactPackById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addPayload([])->addMessage('Contact does not exists')
                    ->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }

            return $this->responseBuilder->addObject($contact)->setStatus(Response::HTTP_OK)->objectResponse();
        } catch (\Doctrine\DBAL\Driver\Exception | \Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/search", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByText(Request $request): JsonResponse
    {
        try {
            $searchText = (string)$request->get('searchText');
            if (!$searchText) {
                return $this->responseBuilder->addPayload([])->addMessage('Searching text cant be empty')
                    ->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }

            $profile = $this->contactProfileRepository->search($this->user(), $searchText);
            return $this->responseBuilder->addObject($profile)->setStatus(Response::HTTP_OK)->objectResponse();
        } catch (\Doctrine\DBAL\Driver\Exception | \Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }


    /**
     * @Route (path="/me/contacts/filter-by/world", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function filterByWorlds(Request $request): JsonResponse
    {
        try {
            $result = $this->contactProfileRepository->filterByWorld($this->user(), (string)$request->get('world'));
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

            $contact = $this->contactProfileRepository->findContactById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }

            $contact->setPhoto((string)$request->get('avatar'));
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
			$contactInput = $request->get('contact')['profile'];
			$companyInput = $request->get('contact')['company'];
			
            $contact = (new ContactProfile())->arrayToEntity($contactInput );
            $company = (new ContactCompany())->arrayToEntity( $companyInput );

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
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(Request $request, string $contactId, SerializerInterface $serializer): JsonResponse
    {
        try {
            $contact = $this->contactProfileRepository->findContactById($this->user(), $contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }

            $contact = RequestTOContactProfile::toEntity($contact, $request->get('contact'));
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
     * @param string  $contactId
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
     * @Route (path="/me/contacts/export", methods={"POST"})
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
