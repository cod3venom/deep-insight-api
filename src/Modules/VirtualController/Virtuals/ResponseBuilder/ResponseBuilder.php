<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 01.01.2022
 * Time: 01:59
*/


namespace App\Modules\VirtualController\Virtuals\ResponseBuilder;

use phpDocumentor\Reflection\PseudoTypes\NumericString;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseBuilder extends VirtualActions
{
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var array
     */
    protected $responseData = [];

     /**
     * @var int
     */
    protected int $status = Response::HTTP_OK;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        parent::__construct($this);
        $this->serializer  = $serializer;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function addMessage(string $message): self
    {
        $this->responseData["message"] = $message;
        return $this;
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function addPayload(array $payload = []): self
    {
        $this->responseData = $payload;
        return $this;
    }

    /**
     * @param $obj
     * @return ResponseBuilder
     */
    public function addObject($obj): self {
        $this->responseData = $this->serializer->serialize($obj, JsonEncoder::FORMAT);
        return $this;
    }
    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function addKeyValue(string $key, $value): self
    {
        $this->responseData[$key] = $value;
        return $this;
    }

    /**
     * @param $payload
     * @return $this
     */
    public function appendPayload($payload): self
    {
        $this->responseData[] = $payload;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->responseData;
    }

    /**
     * @return JsonResponse
     */
    public function objectResponse(): JsonResponse {
        return new JsonResponse($this->responseData,
            $this->status, [], true
        );
    }

    /**
     * @return JsonResponse
     */
    public function jsonResponse(): JsonResponse {
        return new JsonResponse($this->serializer->serialize($this->responseData, JsonEncoder::FORMAT),
            $this->status, [], true
        );
    }
}