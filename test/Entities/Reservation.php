<?php
declare(strict_types=1);

namespace Entities;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('reservations')]
class Reservation extends BaseEntity
{
    #[ORM\Column(name: 'title', type: Types::STRING, length: 255, nullable: false)]
    protected string $title;

    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false)]
    protected \DateTime $date;

    #[ORM\Column(name: 'time', type: Types::TIME_MUTABLE, nullable: false)]
    protected \DateTime $time;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id', nullable: false)]
    protected Location $location;
}