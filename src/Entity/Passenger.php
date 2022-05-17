<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passenger
 *
 * @ORM\Table(name="passenger", indexes={@ORM\Index(name="fk_passenger_id_person", columns={"id_person"})}, indexes={@ORM\Index(name="fk_passenger_id_loan", columns={"id_loan"})})
 * @ORM\Entity
 */
class Passenger
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_loan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLoan;

    /**
     * @var int
     *
     * @ORM\Column(name="id_person", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idPerson;

    public function getIdLoan(): ?int
    {
        return $this->idLoan;
    }

    public function getIdPerson(): ?int
    {
        return $this->idPerson;
    }


}
