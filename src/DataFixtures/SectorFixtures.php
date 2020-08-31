<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sectorNames = ['Recrutement', 'Informatique', 'Comptabilité', 'Direction'];
        foreach ($sectorNames as $name)
        {
            $sector = new Sector();
            $sector->setName($name);
            $manager->persist($sector);
        }

        $manager->flush();
    }
}
