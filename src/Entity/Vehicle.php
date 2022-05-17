<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicle
 *
 * @ORM\Table(name="vehicle", indexes={@ORM\Index(name="fk_vehicle_id_site", columns={"id_site"})})
 * @ORM\Entity
 */
class Vehicle
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
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=32, nullable=false)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=32, nullable=false)
     */
    private $model;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_places", type="integer", nullable=false)
     */
    private $nbPlaces;

    /**
     * @var int
     *
     * @ORM\Column(name="id_site", type="integer", nullable=false)
     */
    private $idSite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getIdSite(): ?int
    {
        return $this->idSite;
    }

    public function setIdSite(int $idSite): self
    {
        $this->idSite = $idSite;

        return $this;
    }


}
