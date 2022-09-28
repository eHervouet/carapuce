<?php

namespace App\Entity;

use App\Repository\PassengerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassengerRepository::class)]
class Passenger
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Loan $idLoan = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $idPerson = null;

    public function getIdLoan(): ?Loan
    {
        return $this->idLoan;
    }

    public function setIdLoan(?Loan $idLoan): self
    {
        $this->idLoan = $idLoan;

        return $this;
    }

    public function getIdPerson(): ?Person
    {
        return $this->idPerson;
    }

    public function setIdPerson(?Person $idPerson): self
    {
        $this->idPerson = $idPerson;

        return $this;
    }
}
