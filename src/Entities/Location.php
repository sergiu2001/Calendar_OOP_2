<?php
declare(strict_types=1);

namespace App\Entities;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('locations')]
class Location extends BaseEntity
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(name: 'place', type: Types::STRING, length: 255, nullable: false)]
    protected string $place;

    #[ORM\Column(name: 'max_res', type: Types::INTEGER)]
    protected int $max_res;

}