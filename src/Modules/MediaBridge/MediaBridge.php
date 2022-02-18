<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 11.02.2022
 * Time: 10:13
*/

namespace App\Modules\MediaBridge;

use App\Entity\User\User;
use App\Modules\MediaBridge\DAO\MediaBridgeResultTObject;
use App\Modules\StringBuilder\StringBuilder;
use App\Service\LoggerService\LoggerService;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UnexpectedValueException;

class MediaBridge
{
    private array $whiteList = [];

    private LoggerService $loggerService;

    /**
     * @param LoggerService $loggerService
     */
    public function __construct(LoggerService $loggerService)
    {
        $this->loggerService = $loggerService;
    }

    /**
     * @param array $whiteList
     * @return $this
     */
    public function setWhiteList(array $whiteList): self {
        $this->whiteList = $whiteList;
        return $this;
    }

    /**
     * @param UploadedFile|null $uploadedFile
     * @param string $targetPath
     * @param string $publicAddress
     * @return MediaBridgeResultTObject
     */
    public function upload(User $user, ?UploadedFile $uploadedFile, string $targetPath, string $publicAddress): MediaBridgeResultTObject
    {

        if (!file_exists($targetPath)) {
            throw new InvalidArgumentException('MediaBridge:: UploadedFile is null, ' . json_encode(['file' => $_FILES]) );
        }
        if (is_null($uploadedFile)) {
            throw new InvalidArgumentException('MediaBridge:: UploadedFile is null, ' . json_encode(['file' => $_FILES]) );
        }
        if (!($uploadedFile instanceof UploadedFile)) {
            return throw new InvalidArgumentException('MediaBridge:: UploadedFile is not instance of the UploadedFile class. ');
        }




        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $ext =  $uploadedFile->guessExtension();
        $newName = $originalFilename . '_' . uniqid() . '.' .$ext;
        $fullPath = $publicAddress . $targetPath . '/' . $newName;

        $this->loggerService->info('MediaBridge::upload-image',$user->getEmail().' : TargetPath => '.$targetPath);
        $this->loggerService->info('MediaBridge::upload-image',$user->getEmail().' : Uploaded File => '.$uploadedFile);
        $this->loggerService->info('MediaBridge::upload-image',$user->getEmail().' : Full Path => '.$fullPath);

        try {

            if (count($this->whiteList) > 0) {
                foreach ($this->whiteList as $whiteExt) {
                    if ($whiteExt !== $ext) {
                        continue;
                    }
                    $uploadedFile->move($targetPath, $newName);
                    $this->loggerService->info('MediaBridge::upload-image',$user->getEmail().' : Uploading status => OK');
                    return new MediaBridgeResultTObject(['url'=>$fullPath]);
                }
            }
            else {
                $uploadedFile->move($targetPath, $newName);
                $this->loggerService->info('MediaBridge::upload-image',$user->getEmail().' : Uploading status => OK');
            }

            return new MediaBridgeResultTObject(['url'=>$fullPath]);
        } catch (Exception $e) {
            throw new UnexpectedValueException('Something went wrong: ' . $e->getMessage());
        }
    }
}
