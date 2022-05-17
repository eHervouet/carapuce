<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loan
 *
 * @ORM\Entity
 */
class Loan
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
     * @var \DateTime
     *
     * @ORM\Column(name="depart_date", type="datetime", nullable=false)
     */
    private $departDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_date", type="datetime", nullable=false)
     */
    private $returnDate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="return_vehicle", type="boolean", nullable=true)
     */
    private $returnVehicle;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="return_key", type="boolean", nullable=true)
     */
    private $returnKey;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_address", type="string", length=128, nullable=false)
     */
    private $destinationAddress;

    /**
     * @var int
     *
     * @ORM\Column(name="driver", type="integer", nullable=false)
     */
    private $driver;

    /**
     * @var int
     *
     * @ORM\Column(name="affected_vehicle", type="integer", nullable=false)
     */
    private $affectedVehicle;

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

    public function setReturnVehicle(?bool $returnVehicle): self
    {
        $this->returnVehicle = $returnVehicle;

        return $this;
    }

    public function isReturnKey(): ?bool
    {
        return $this->returnKey;
    }

    public function setReturnKey(?bool $returnKey): self
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

    public function getDriver(): ?int
    {
        return $this->driver;
    }

    public function setDriver(int $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getAffectedVehicle(): ?int
    {
        return $this->affectedVehicle;
    }

    public function setAffectedVehicle(int $affectedVehicle): self
    {
        $this->affectedVehicle = $affectedVehicle;

        return $this;
    }


}
