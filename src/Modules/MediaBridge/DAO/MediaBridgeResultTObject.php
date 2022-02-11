<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 11.02.2022
 * Time: 12:14
*/

namespace App\Modules\MediaBridge\DAO;

class MediaBridgeResultTObject
{
    public string $asset_id = '';

    public string $public_id = "";

    public string $signature = "";

    public string $width = "";

    public string $height = "";

    public string $format = "";

    public string $resource_type = "";

    public string $bytes = "";

    public string $type = "";

    public string $etag = "";

    public string $placeholder = "";

    public string $url = "";

    public string $secure_url = "";

    public function __construct(?array $inputs)
    {
        if (is_null($inputs)){
            return;
        }
        foreach ($inputs as $k=>$v) {
            $this->{$k} = $v;
        }
    }
}
