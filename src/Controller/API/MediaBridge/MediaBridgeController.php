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
use App\Modules\MediaBridge\MediaBridge;
use App\Modules\VirtualController\VirtualController;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route (path="/media-bridge")
 */
class MediaBridgeController extends VirtualController
{

    private ParameterBagInterface $parameterBag;
    public function __construct(
        SerializerInterface $serializer,
        ParameterBagInterface $parameterBag
    )
    {
        parent::__construct($serializer);
        $this->parameterBag = $parameterBag;
    }



    /**
     * @Route (path="/upload/single/image", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        try{
            $image = $request->files->get('image');

            ## Upload image to the Cloudinary service
            $result = (new MediaBridge())
                ->setWhiteList(['png', 'jpeg', 'jpg'])
                ->upload($image, $this->parameterBag->get('user_avatars_upload_dir_base'), $_ENV['BACKEND_URL']);

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
