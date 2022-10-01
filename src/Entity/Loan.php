<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\Column]
    private ?bool $returnVehicle = null;

    #[ORM\Column]
    private ?bool $returnKey = null;

    #[ORM\Column(length: 128)]
    private ?string $destinationAddress = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $driver = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $affectedVehicle = null;

    #[ORM\Column(length: 128)]
    private ?string $statut = "created";

    #[ORM\Column(length: 255)]
    private ?string $destination_city = null;

    #[ORM\Column(length: 5)]
    private ?string $destination_cp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;
    
    #[ORM\ManyToMany(targetEntity: Person::class)]
    private $passengers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?String
    {
        return $this->statut;
    }
    
    public function getDepartDate(): ?\DateTimeInterface
    {
        return $this->departDate;
    }

    public function setDepartDate(\DateTimeInterface $departDate): self
    {
        $this->departDate = $departDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function isReturnVehicle(): ?bool
    {
        return $this->returnVehicle;
    }

    public function setReturnVehicle(bool $returnVehicle): self
    {
        $this->returnVehicle = $returnVehicle;

        return $this;
    }

    public function isReturnKey(): ?bool
    {
        return $this->returnKey;
    }

    public function setReturnKey(bool $returnKey): self
    {
        $this->returnKey = $returnKey;

        return $this;
    }

    public function getDestinationAddress(): ?string
    {
        return $this->destinationAddress;
    }

    public function setDestinationAddress(string $destinationAddress): self
    {
        $this->destinationAddress = $destinationAddress;

        return $this;
    }

    public function getDriver(): ?Person
    {
        return $this->driver;
    }

    public function setDriver(?Person $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function setStatut(String $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAffectedVehicle(): ?Vehicle
    {
        return $this->affectedVehicle;
    }

    public function setAffectedVehicle(?Vehicle $affectedVehicle): self
    {
        $this->affectedVehicle = $affectedVehicle;

        return $this;
    }

    public function getDestinationCity(): ?string
    {
        return $this->destination_city;
    }

    public function setDestinationCity(string $destination_city): self
    {
        $this->destination_city = $destination_city;

        return $this;
    }

    public function getDestinationCp(): ?string
    {
        return $this->destination_cp;
    }

    public function setDestinationCp(string $destination_cp): self
    {
        $this->destination_cp = $destination_cp;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPassengers()
    {
        return $this->passengers;
    }

    public function setPassengers($passengers)
    {
        $this->passengers = $passengers;

        return $this;
    }

    public function userInPassengers($user)
    {
        foreach ($this->passengers as $passenger) {
            if($passenger->getId() == $user->getId()){
                return true;
            }
        }
        return false;
    }

    public function deletePassanger($user)
    {
        foreach ($this->passengers as $key => $passenger) {
            if($passenger->getId() == $user->getId()){
                unset($this->passengers[$key]);
                break;
            }
        }

    }

}
