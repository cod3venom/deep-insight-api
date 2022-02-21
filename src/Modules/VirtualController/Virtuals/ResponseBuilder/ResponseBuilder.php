<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 01.01.2022
 * Time: 01:59
*/


namespace App\Modules\VirtualController\Virtuals\ResponseBuilder;

use App\Modules\VirtualController\Virtuals\ResponseBuilder\DAO\ResponseBuilderTObject;
use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\PseudoTypes\NumericString;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseBuilder extends VirtualActions
{
    /**
     * @var SerializerInterface
     */
    protected VirtualSerializer $serializer;

    /**
     * @var array
     */
    protected $responseData = [];

    protected $groups = [];
     /**
     * @var int
     */
    protected int $status = Response::HTTP_OK;

    /**
     * @param VirtualSerializer $serializer
     */
    public function __construct(VirtualSerializer $serializer)
    {
        parent::__construct($this);

        $this->serializer = $serializer;
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
        $schema = [
            'status'=> $this->status,
            'data' => $payload
        ];
        $this->responseData = $schema;
        return $this;
    }

    /**
     * @param $obj
     * @return ResponseBuilder
     * @throws ExceptionInterface
     */
    public function addObject($obj): self {
        $schema = new ResponseBuilderTObject($this->status, $obj);
        $this->responseData = $this->serializer->serialize($schema, JsonEncoder::FORMAT, [AbstractObjectNormalizer::ENABLE_MAX_DEPTH=> true]);
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

    public function setGroups(array $groups): self {
        $this->groups= ['groups' => $groups];
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
            $this->status, [],
        );
    }

    /**
     * @return JsonResponse
     */
    public function jsonResponse(): JsonResponse {
        return new JsonResponse($this->serializer->serialize($this->responseData, JsonEncoder::FORMAT, $this->groups),
            $this->status, [], true
        );
    }
}
