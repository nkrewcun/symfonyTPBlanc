<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\SectorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    private $sectorRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, SectorRepository $sectorRepository)
    {
        $this->encoder = $encoder;
        $this->sectorRepository = $sectorRepository;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setEmail('admin@deloitte.com');
        $user->setIsPasswordRedefined(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPicture('admin.svg');
        $user->setPassword($this->encoder->encodePassword(
            $user,
            'admin123@'
        ));
        $sectors = $this->sectorRepository->findBy(['name' => 'Direction']);
        $user->setSector($sectors[0]);

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SectorFixtures::class];
    }
}
