<?php

namespace App\Entity;

use App\Repository\SportEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SportEventRepository::class)
 */
class SportEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $eventTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creationTime;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sportEvents")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sportEvents")
     */
    private $ModifiedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modificationTime;

    /**
     * @ORM\OneToMany(targetEntity=TicketPrice::class, mappedBy="sportEvent")
     */
    private $ticketPrice;


    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->ticketPrice = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEventTime(): ?\DateTimeInterface
    {
        return $this->eventTime;
    }

    public function setEventTime(\DateTimeInterface $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationTime(): ?\DateTimeInterface
    {
        return $this->creationTime;
    }

    public function setCreationTime(?\DateTimeInterface $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getModifiedBy(): ?User
    {
        return $this->ModifiedBy;
    }

    public function setModifiedBy(?User $ModifiedBy): self
    {
        $this->ModifiedBy = $ModifiedBy;

        return $this;
    }

    public function getModificationTime(): ?\DateTimeInterface
    {
        return $this->modificationTime;
    }

    public function setModificationTime(?\DateTimeInterface $modificationTime): self
    {
        $this->modificationTime = $modificationTime;

        return $this;
    }

    /**
     * @return Collection|TicketPrice[]
     */
    public function getTicketPrice(): Collection
    {
        return $this->ticketPrice;
    }

    public function addTicketPrice(TicketPrice $ticketPrice): self
    {
        if (!$this->ticketPrice->contains($ticketPrice)) {
            $this->ticketPrice[] = $ticketPrice;
            $ticketPrice->setSportEvent($this);
        }

        return $this;
    }

    public function removeTicketPrice(TicketPrice $ticketPrice): self
    {
        if ($this->ticketPrice->removeElement($ticketPrice)) {
            // set the owning side to null (unless already changed)
            if ($ticketPrice->getSportEvent() === $this) {
                $ticketPrice->setSportEvent(null);
            }
        }

        return $this;
    }

 
   
    
   

}
