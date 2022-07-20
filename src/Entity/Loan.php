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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAffectedVehicle(): ?Vehicle
    {
        return $this->affectedVehicle;
    }

    public function setAffectedVehicle(?Vehicle $affectedVehicle): self
    {
        $this->affectedVehicle = $affectedVehicle;

        return $this;
    }
}
