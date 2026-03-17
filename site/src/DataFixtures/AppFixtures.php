<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // super-admin
        $sadmin = new user();
        $sadmin->setLogin('admin')
                ->setRoles(['ROLE_SUPER_ADMIN'])
                ->setNom('Admin')
                ->setPrenom('Super')
                ->setDateNaissance(new \DateTime('1980-01-01'));
        $sadmin->setPassword($this->hasher->hashPassword($sadmin, 'nimbas'));
        $manager->persist($sadmin);

        // admin
        $gilles = new User();
        $gilles->setLogin('gilles')
                ->setRoles(['ROLE_ADMIN'])
                ->setNom('Subrenat')
                ->setPrenom('Gilles')
                ->setDateNaissance(new \DateTime('1980-01-01'));
        $gilles->setPassword($this->hasher->hashPassword($gilles, 'sellig'));
        $manager->persist($gilles);

        // cliente
        $rita = new User();
        $rita->setLogin('rita')
                ->setRoles(['ROLE_USER'])
                ->setNom('Zrour')
                ->setPrenom('Rita')
                ->setDateNaissance(new \DateTime('1980-01-01'));
        $rita->setPassword($this->hasher->hashPassword($rita, 'atir'));
        $manager->persist($rita);

        // client
        $mathieu = new User();
        $mathieu->setLogin('mathieu')
                ->setRoles(['ROLE_USER'])
                ->setNom('XXX')
                ->setPrenom('Mathieu')
                ->setDateNaissance(new \DateTime('1980-01-01'));
        $mathieu->setPassword($this->hasher->hashPassword($mathieu, 'ueihtam'));
        $manager->persist($mathieu);

        $manager->flush();
    }
}
