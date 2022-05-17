<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VehicleKey
 *
 * @ORM\Table(name="vehicle_key", indexes={@ORM\Index(name="fk_key_id_vehicle", columns={"id_vehicle"})})
 * @ORM\Entity
 */
class VehicleKey
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_vehicle", type="integer", nullable=true)
     */
    private $idVehicle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdVehicle(): ?int
    {
        return $this->idVehicle;
    }

    public function setIdVehicle(?int $idVehicle): self
    {
        $this->idVehicle = $idVehicle;

        return $this;
    }


}
