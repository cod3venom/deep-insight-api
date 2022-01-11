<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 09.01.2022
 * Time: 06:01
*/


namespace App\Modules\Cloudinary\DAO;

class CloudinaryResultTObject
{

    /**
     * @var string
     */
    public string $asset_id = "";

    /**
     * @var string
     */
    public string $public_id = "";

    /**
     * @var string
     */
    public string $signature = "";

    /**
     * @var int
     */
    public int $width = 0;

    /**
     * @var int
     */
    public int $height = 0;

    /**
     * @var string
     */
    public string $format = "";

    /**
     * @var string
     */
    public string $resource_type = "";

    /**
     * @var int
     */
    public int $bytes = 0;

    /**
     * @var string
     */
    public string $type = "";

    /**
     * @var string
     */
    public string $etag = "";

    /**
     * @var bool
     */
    public bool $placeholder = false;

    /**
     * @var string
     */
    public string $url = "";

    /**
     * @var string
     */
    public string $secure_url = "";


    public function __construct(object $cloudinaryResult)
    {
        if (!$cloudinaryResult){
            return;
        }

        foreach ($cloudinaryResult as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}
