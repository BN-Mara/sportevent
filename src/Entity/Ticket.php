<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="text")
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValidated;

    /**
     * @ORM\ManyToOne(targetEntity=TicketPrice::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prices;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="tickets")
     */
    private $payment;


    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(?bool $isValidated): self
    {
        $this->isValidated = $isValidated;
        return $this;
    }

    public function getPrices(): ?TicketPrice
    {
        return $this->prices;
    }

    public function setPrices(?TicketPrice $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }


 
}
