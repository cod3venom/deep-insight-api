<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 01.01.2022
 * Time: 18:51
*/


namespace App\Modules\VirtualController\Virtuals\ResponseBuilder;

use Symfony\Component\HttpFoundation\Response;

class VirtualActions
{
    /**
     * @var ResponseBuilder
     */
    private ResponseBuilder $responseBuilder;

    public function __construct(ResponseBuilder &$responseBuilder)
    {
        $this->responseBuilder = &$responseBuilder;
    }

    /**
     * @return ResponseBuilder
     */
    public function blockAccess(): ResponseBuilder
    {
        return $this->responseBuilder->addMessage('You dont have access to this resource')
            ->setStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return ResponseBuilder
     */
    public function wrongRequest(): ResponseBuilder
    {
        return $this->responseBuilder->addMessage('Incoming request was provided wrongly')
            ->setStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return ResponseBuilder
     */
    public function somethingWentWrong(): ResponseBuilder
    {
        return $this->responseBuilder->addMessage('Something went wrong')
            ->setStatus(Response::HTTP_BAD_REQUEST);
    }

    public function ruleViolation(string $fieldName): ResponseBuilder {
        $message = $fieldName . ' cant be null';
        return $this->responseBuilder->addMessage($message)
            ->setStatus(Response::HTTP_BAD_REQUEST);
    }


}