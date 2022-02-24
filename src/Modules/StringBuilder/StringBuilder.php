<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 07.02.2022
 * Time: 13:09
*/

namespace App\Modules\StringBuilder;

class StringBuilder
{
    private string $str = '';

    public function append(?string $data): self
    {
        $this->str .= $data;
        return $this;
    }
    public function addList(array $list): self
    {
        foreach ($list as $item) {
            $this->str .= " " . $item;
        }
        return $this;
    }

    public function reset(): StringBuilder
    {
        $this->str = '';
        return $this;
    }

    public function str(): string
    {
        return $this->str;
    }

    public function __toString(): string
    {
        return $this->str;
    }
}
