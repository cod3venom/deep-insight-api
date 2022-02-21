<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 21.02.2022
 * Time: 08:17
*/


namespace App\Modules\VirtualController\Virtuals\ResponseBuilder\DAO;

class ResponseBuilderTObject
{
    public int $status;
    public $data;

    public function __construct(int $status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus(): int {
        return $this->status;
    }
}