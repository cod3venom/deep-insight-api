<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.01.2022
 * Time: 05:59
*/


namespace App\Modules\Cloudinary;

use App\Modules\Cloudinary\DAO\CloudinaryResultTObject;
use Cloudinary\Cloudinary;
use InvalidArgumentException;

class CloudinaryBridge extends AbstractResource
{

    /**
     * Data URL scheme for base64 data.
     */
    CONST BASE64_URL_SCHEMA = 'data:image/jpeg;base64';

    /**
     * Cloudinary object
     */
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->startup();
    }

    /**
     * This method is used to
     * configure Cloudinary object
     * with required variables.
     * @example CLOUDINARY_CLOUD_NAME=MyCloud
     * @example CLOUDINARY_API_KEY=XXXXXX
     * @example CLOUDINARY_API_SECRET=YYYYY
     *
     * @important @secure flag is used to
     * determine whether we need to use
     * image links with HTTPS or HTTP protocols.
     */
    private function startup(): void{
        $this->cloudinary = new Cloudinary([ 'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key'  => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
            'url' => [
                'secure' => false
            ]
        ]]);
    }

    public function imageToBase64(): string
    {

        return '';
    }
    /**
     * This method is used to upload
     * image file to the Cloudinary service
     * @param string $path
     * @return CloudinaryBridge
     */
    public function uploadImage(string $path): CloudinaryBridge{
        if (empty($path)){
            throw new InvalidArgumentException("File path can't be empty");
        }
        if (!file_exists($path)){
            throw new InvalidArgumentException("File not found");
        }
        $this->call($path);
        return $this;
    }

    /**
     * This method is used to upload
     * Base64 image to the Cloudinary service.
     * @param string $base64
     * @return CloudinaryBridge
     * @throw InvalidArgumentException
     */
    public function uploadBase64Image(string $base64): CloudinaryBridge {
        if (!base64_decode($base64)){
            throw new InvalidArgumentException('File is not image');
        }
        $compiledDate = sprintf('%s,%s', self::BASE64_URL_SCHEMA, $base64);

        return $this->call($compiledDate);
    }


    private function call(string $input): CloudinaryBridge{
        try {
            $response = $this->cloudinary->uploadApi()->upload($input);
            $this->results[] = new CloudinaryResultTObject($response);
        }
        catch (\Exception $ex){}
        return $this;
    }
}
