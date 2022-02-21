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
use App\Service\LoggerService\LoggerService;
use Doctrine\ORM\ORMException;
use PHPUnit\Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends VirtualController
{

    public function __construct(
        private SerializerInterface $serializer,
        private LoggerService $loggerService,
        private ContactProfileRepository $contactProfileRepository
    )
    {
        parent::__construct($this->serializer);
    }


    /**
     * @Route (path="/me/contacts", methods={"GET"})
     */
    public function all(Request $request): JsonResponse {
        try {
            $user = $this->user();
            $contacts = $this->contactProfileRepository->all($user);
            return  $this->responseBuilder->addPayload([$contacts])->objectResponse();
        }
        catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/lazy", methods={"GET"})
     */
    public function lazyLoading(Request $request): JsonResponse
    {
        try {
            $startFrom = (int)$request->get('startFrom');
            $limit = (int)$request->get('limit');

            $user = $this->user();
            $contacts = $this->contactProfileRepository->setStartFrom($startFrom)->setLimit($limit)->all($user);
            return  $this->responseBuilder->addObject($contacts)->jsonResponse();
        }
        catch (\Exception $ex) {
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
            $keyword = (string)$request->get('keyword');
            return  $this->responseBuilder->addObject([])->jsonResponse();
        }
        catch (\Exception $ex) {
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
            $world = (string)$request->get('$world');
            return  $this->responseBuilder->addObject([])->jsonResponse();
        }
        catch (\Exception $ex) {
            $this->loggerService->error('ContactController', 'All', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/me/contacts/create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse|void
     */
    public function create(Request $request) {
        try {
            $contactProfile = $request->get('contact');
            $contactCompany = $request->get('company');


            $contact = new ContactProfile();
            $company = new ContactCompany();

            $contact->arrayToEntity($contactProfile);
            $company->arrayToEntity($contactCompany);

            $contact = $this->contactProfileRepository->create($this->user(), $contact, $company);
            return $this->responseBuilder->addObject($contact)->objectResponse();
        }
        catch (ORMException| ReflectionException | Exception $ex){
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
    public function edit(Request $request, string $contactId): JsonResponse
    {
        try {

            $contact = $this->contactProfileRepository->findByContactId($contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }
            $contactProfile = $request->get('contact');
            $contactCompany = $request->get('company');

            $contact->arrayToEntity($contactProfile);
            $contact->getContactCompany()->arrayToEntity($contactCompany);

            $contact = $this->contactProfileRepository->edit($contact);
            return $this->responseBuilder->addObject($contact)->objectResponse();

        }
        catch (ORMException| ReflectionException | Exception $ex){
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
            $contact = $this->contactProfileRepository->findByContactId($contactId);
            if (!$contact->getId()) {
                return $this->responseBuilder->addMessage('Contact does not exists')->setStatus(Response::HTTP_NOT_FOUND)->jsonResponse();
            }
            $this->contactProfileRepository->remove($contact);
            return $this->responseBuilder->addPayload([])->jsonResponse();
        }
        catch (ORMException| ReflectionException | Exception $ex){
            $this->loggerService->error('ContactController', 'Remove', [$this->user()->getEmail(), $ex]);
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    public function export() {
        try {

        }
        catch (Exception $ex){
            $this->loggerService->error('ContactController', 'Export', [$this->user()->getEmail(), $ex]);
        }
    }

    public function import() {
        try {

        }
        catch (Exception $ex){
            $this->loggerService->error('ContactController', 'Import', [$this->user()->getEmail(), $ex]);
        }
    }
}
