<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class IdTrait
 * @package App\Entity\Traits
 */
trait IdTrait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var mixed
     */
    protected $id;

    /**
     * Sometimes we need to parse&assign
     * id to the user en
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
}
