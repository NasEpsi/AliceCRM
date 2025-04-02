<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            ['Tiph', 'ADMIN', 'po@po.com', 'ROLE_ADMIN', 'tiph-admin'],
            ['Paul', 'DUPONT', 'paul.dupont@gmail.com', 'ROLE_USER', 'paul-dupont'],
            ['Sophie', 'LEROY', 'sophie.leroy@gmail.com', 'ROLE_USER', 'sophie-leroy'],
            ['Jean', 'MARTIN', 'jean.martin@gmail.com', 'ROLE_USER', 'jean-martin'],
            ['Laura', 'BERTRAND', 'laura.bertrand@gmail.com', 'ROLE_USER', 'laura-bertrand'],
            ['Thomas', 'MOREAU', 'thomas.moreau@gmail.com', 'ROLE_USER', 'thomas-moreau'],
            ['Emma', 'ROUSSEAU', 'emma.rousseau@gmail.com', 'ROLE_USER', 'emma-rousseau'],
            ['Lucas', 'FONTAINE', 'lucas.fontaine@gmail.com', 'ROLE_USER', 'lucas-fontaine'],
            ['Clara', 'DURAND', 'clara.durand@gmail.com', 'ROLE_USER', 'clara-durand'],
            ['Nicolas', 'BLANCHARD', 'nicolas.blanchard@gmail.com', 'ROLE_USER', 'nicolas-blanchard'],
            ['Julie', 'GARNIER', 'julie.garnier@gmail.com', 'ROLE_USER', 'julie-garnier'],
            ['Maxime', 'GUILLAUME', 'maxime.guillaume@gmail.com', 'ROLE_USER', 'maxime-guillaume'],
            ['Camille', 'LEGRAND', 'camille.legrand@gmail.com', 'ROLE_USER', 'camille-legrand'],
            ['Antoine', 'ROBIN', 'antoine.robin@gmail.com', 'ROLE_USER', 'antoine-robin'],
            ['Isabelle', 'CHEVALIER', 'isabelle.chevalier@gmail.com', 'ROLE_USER', 'isabelle-chevalier'],
            ['Hugo', 'RENAUD', 'hugo.renaud@gmail.com', 'ROLE_USER', 'hugo-renaud']
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setFirstname($data[0])
                ->setLastname($data[1])
                ->setEmail($data[2])
                ->setRoles([$data[3]])
                ->setIsVerified(true)
                ->setSlug($data[4]);

            $password = $this->hasher->hashPassword($user, 'Pa$$w0rd!');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
        $this->addReference('user', $user);
    }
}
