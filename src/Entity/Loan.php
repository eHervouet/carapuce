<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loan
 *
 * @ORM\Table(name="loan", indexes={@ORM\Index(name="fk_loan_affected_vehicle", columns={"affected_vehicle"}), @ORM\Index(name="fk_loan_driver", columns={"driver"})})
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


}
