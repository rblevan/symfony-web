<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Product;
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
        $france = new Pays();
        $france->setNom('France');
        $france->setCode('FR');
        $manager->persist($france);

        $germany = new Pays();
        $germany->setNom('Germany');
        $germany->setCode('GE');
        $manager->persist($germany);

        $USA = new Pays();
        $USA->setNom('USA');
        $USA->setCode('US');
        $manager->persist($USA);

        $manager->flush();

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

        $produits = [
            ['libelle' => 'Bloc d\'Herbe', 'prix' => 1.50, 'stock' => 500, 'img' => 'bloc_grass.png'],
            ['libelle' => 'Bloc de Terre', 'prix' => 0.50, 'stock' => 1000,'img' => 'bloc_dirt.png'],
            ['libelle' => 'Bloc de Cobblestone', 'prix' => 2.00, 'stock' => 250,'img' => 'bloc_cobble.png'],
            ['libelle' => 'Bloc de Diamant', 'prix' => 99.99, 'stock' => 5,'img' => 'bloc_diams.png'],
            ['libelle' => 'Bloc d\'Obsidienne', 'prix' => 15.00, 'stock' => 64,'img' => 'bloc_obsi.png'],
            ['libelle' => 'Bloc de TNT', 'prix' => 25.50, 'stock' => 10,'img' => 'tnt.png'],
        ];

        foreach ($produits as $data) {
            $product = new Product();
            $product->setLibelle($data['libelle']);
            $product->setPrixUnitaire($data['prix']);
            $product->setQuantiteStock($data['stock']);
            $product->setImage($data['img']);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
