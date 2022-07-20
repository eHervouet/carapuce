<?php

namespace App\Entity;

use App\Repository\VehicleKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleKeyRepository::class)]
class VehicleKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $idVehicle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdVehicle(): ?Vehicle
    {
        return $this->idVehicle;
    }

    public function setIdVehicle(?Vehicle $idVehicle): self
    {
        $this->idVehicle = $idVehicle;

        return $this;
    }
}
