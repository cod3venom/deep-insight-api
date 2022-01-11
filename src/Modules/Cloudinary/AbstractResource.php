<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.01.2022
 * Time: 06:00
*/


namespace App\Modules\Cloudinary;

use App\Modules\Cloudinary\DAO\CloudinaryResultTObject;

abstract class AbstractResource
{


    /**
     * All responses will be
     * pushed into this
     * variable
     * @var array
     */
    protected array $results = [];


    public function getOnlyImages(): array
    {
        return $result = array_map(function (CloudinaryResultTObject $result){
            return [
                'url' => $result->url,
                'asset_id' => $result->asset_id,
                'secure_url'=>$result->secure_url
            ];
        }, $this->results);
    }

    /**
     * @return CloudinaryResultTObject
     */
    public function getSingleResult(): CloudinaryResultTObject{
        foreach ($this->results as $result){
            if (!($result instanceof CloudinaryResultTObject)){
                continue;
            }
            return $result;
        }
        return new CloudinaryResultTObject(null);
    }
}