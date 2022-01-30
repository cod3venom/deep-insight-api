<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 04.01.2022
 * Time: 18:39
*/


namespace App\Controller\API\MediaBridge;

use App\Modules\Cloudinary\CloudinaryBridge;
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/media-bridge")
 */
class MediaBridgeController extends VirtualController
{

    public function __construct(SerializerInterface $serializer)
    {
        parent::__construct($serializer);
    }



    /**
     * @Route (path="/upload/single/image", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        try{
            $image = $_FILES['image']['tmp_name'];

            ## Upload image to the Cloudinary service
            $result = (new CloudinaryBridge())
                ->uploadImage($image)
                ->getSingleResult();

            return $this->responseBuilder->addObject($result)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }

    /**
     * @Route (path="/image/base-64/single", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImageAsBase64(Request $request): JsonResponse
    {
        try{
            $base64 = $request->get('image');

            ## Upload image to the Cloudinary service
            $result = (new CloudinaryBridge())
                ->uploadBase64Image($base64)
                ->getSingleResult();

            return $this->responseBuilder->addObject($result)
                ->setStatus(Response::HTTP_OK)
                ->objectResponse();
        }
        catch (\Exception $ex) {
            return $this->responseBuilder->somethingWentWrong()->jsonResponse();
        }
    }
}
