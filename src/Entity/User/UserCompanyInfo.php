<?php

namespace App\Entity\User;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserCompanyInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserCompanyInfoRepository::class)
 */
class UserCompanyInfo
{
    use IdTrait;

    /**
     * @ORM\Column(type="uuid", length=255)
     */
    private string $userId;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $companyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $companyWww;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $companyIndustry;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $wayToEarnMoney;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $regon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $krs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $nip;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $districts = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $headQuartersCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $businessEmail;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $businessPhones = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $revenue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $profit;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $growthYearToYear = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $categories = [];

    /**
     * @ORM\OneToOne (targetEntity=User::class, mappedBy="company",  cascade={"persist", "remove", "detach", "refresh"})
     * @var User|null
     */
    private ?User $user;

    use UpdatedTrait;
    use CreatedTrait;


    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRegon(): ?int
    {
        return $this->regon;
    }

    public function setRegon(?int $regon): self
    {
        $this->regon = $regon;

        return $this;
    }

    public function getKrs(): ?int
    {
        return $this->krs;
    }

    public function setKrs(?int $krs): self
    {
        $this->krs = $krs;

        return $this;
    }

    public function getNip(): ?int
    {
        return $this->nip;
    }

    public function setNip(?int $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyWww(): ?string
    {
        return $this->companyWww;
    }

    public function setCompanyWww(?string $companyWww): self
    {
        $this->companyWww = $companyWww;

        return $this;
    }

    public function getCompanyIndustry(): ?string
    {
        return $this->companyIndustry;
    }

    public function setCompanyIndustry(?string $companyIndustry): self
    {
        $this->companyIndustry = $companyIndustry;

        return $this;
    }

    public function getWayToEarnMoney(): ?string
    {
        return $this->wayToEarnMoney;
    }

    public function setWayToEarnMoney(?string $wayToEarnMoney): self
    {
        $this->wayToEarnMoney = $wayToEarnMoney;

        return $this;
    }

    public function getDistricts(): ?array
    {
        return $this->districts;
    }

    public function setDistricts(?array $districts): self
    {
        $this->districts = $districts;

        return $this;
    }

    public function getHeadQuartersCity(): ?string
    {
        return $this->headQuartersCity;
    }

    public function setHeadQuartersCity(?string $headQuartersCity): self
    {
        $this->headQuartersCity = $headQuartersCity;

        return $this;
    }

    public function getBusinessEmail(): ?string
    {
        return $this->businessEmail;
    }

    public function setBusinessEmail(?string $businessEmail): self
    {
        $this->businessEmail = $businessEmail;

        return $this;
    }

    public function getBusinessPhones(): ?array
    {
        return $this->businessPhones;
    }

    public function setBusinessPhones(?array $businessPhones): self
    {
        $this->businessPhones = $businessPhones;

        return $this;
    }

    public function getRevenue(): ?string
    {
        return $this->revenue;
    }

    public function setRevenue(?string $revenue): self
    {
        $this->revenue = $revenue;

        return $this;
    }

    public function getProfit(): ?string
    {
        return $this->profit;
    }

    public function setProfit(?string $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getGrowthYearToYear(): ?array
    {
        return $this->growthYearToYear;
    }

    public function setGrowthYearToYear(?array $growthYearToYear): self
    {
        $this->growthYearToYear = $growthYearToYear;

        return $this;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
