<?php

namespace App\Entity;

use App\Repository\XRateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=XRateRepository::class)
 */
class XRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $usd;

    /**
     * @ORM\Column(type="float")
     */
    private $cdf;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsd(): ?float
    {
        return $this->usd;
    }

    public function setUsd(float $usd): self
    {
        $this->usd = $usd;

        return $this;
    }

    public function getCdf(): ?float
    {
        return $this->cdf;
    }

    public function setCdf(float $cdf): self
    {
        $this->cdf = $cdf;

        return $this;
    }
}
