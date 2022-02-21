<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 20.02.2022
 * Time: 23:10
*/


namespace App\Controller\API\Contact;

use App\Modules\VirtualController\VirtualController;
use App\Repository\ContactProfileRepository;
use App\Service\LoggerService\LoggerService;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (path="/me/contact")
 */
class ContactController extends VirtualController
{

    public function __construct(
        private SerializerInterface $serializer,
        private LoggerService $loggerService,
        private ContactProfileRepository $profileRepository
    )
    {
        parent::__construct($this->serializer);
    }



    public function create(Request $request) {
        try {
            $profile = $request->get('profile');
            $company = $request->get('company');

            $result = $this->profileRepository->create($this->user(), $profile, $company);
            return $this->responseBuilder->addPayload([$result])->jsonResponse();
        }
        catch (Exception $ex){
            $this->loggerService->error('ContactController', 'Create', [$this->user()->getEmail(), $ex]);
        }
    }


    public function edit() {
        try {

        }
        catch (Exception $ex){
            $this->loggerService->error('ContactController', 'Edit', [$this->user()->getEmail(), $ex]);
        }
    }

    public function delete() {
        try {

        }
        catch (Exception $ex){
            $this->loggerService->error('ContactController', 'Delete', [$this->user()->getEmail(), $ex]);
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