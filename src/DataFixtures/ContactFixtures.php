<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use libphonenumber\PhoneNumberUtil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        
        $users = [
            ['Elena', 'MORAVI', 'elena.m@gmail.com', '+33658745896', 'secretaire', 'elena-moravi'],
            ['Paul', 'DUPONT', 'paul.dupont@gmail.com', '+33612345678', 'manager', 'paul-dupont'],
            ['Sophie', 'LEROY', 'sophie.leroy@gmail.com', '+33687654321', 'assistante', 'sophie-leroy'],
            ['Jean', 'MARTIN', 'jean.martin@gmail.com', '+33623456789', 'directeur', 'jean-martin'],
            ['Laura', 'BERTRAND', 'laura.bertrand@gmail.com', '+33634567890', 'developpeur', 'laura-bertrand'],
            ['Thomas', 'MOREAU', 'thomas.moreau@gmail.com', '+33645678901', 'designer', 'thomas-moreau'],
            ['Emma', 'ROUSSEAU', 'emma.rousseau@gmail.com', '+33656789012', 'comptable', 'emma-rousseau'],
            ['Lucas', 'FONTAINE', 'lucas.fontaine@gmail.com', '+33667890123', 'technicien', 'lucas-fontaine'],
            ['Clara', 'DURAND', 'clara.durand@gmail.com', '+33678901234', 'chef de projet', 'clara-durand'],
            ['Nicolas', 'BLANCHARD', 'nicolas.blanchard@gmail.com', '+33689012345', 'responsable RH', 'nicolas-blanchard'],
            ['Julie', 'GARNIER', 'julie.garnier@gmail.com', '+33690123456', 'stagiaire', 'julie-garnier'],
            ['Maxime', 'GUILLAUME', 'maxime.guillaume@gmail.com', '+33601234567', 'consultant', 'maxime-guillaume'],
            ['Camille', 'LEGRAND', 'camille.legrand@gmail.com', '+33698765432', 'commercial', 'camille-legrand'],
            ['Antoine', 'ROBIN', 'antoine.robin@gmail.com', '+33687654329', 'développeur', 'antoine-robin'],
            ['Isabelle', 'CHEVALIER', 'isabelle.chevalier@gmail.com', '+33676543218', 'administratrice', 'isabelle-chevalier'],
            ['Hugo', 'RENAUD', 'hugo.renaud@gmail.com', '+33665432109', 'analyste', 'hugo-renaud']
        ];

        $user = $this->getReference('user');

        foreach ($users as $data) {
            $phoneNumber = $phoneNumberUtil->parse($data[3], 'FR');

            $contact = new Contact();
            $contact->setFirstname($data[0])
                    ->setLastname($data[1])
                    ->setEmail($data[2])
                    ->setPhone($phoneNumber)
                    ->setPosition($data[4])
                    ->setSlug($data[5])
                    ->setIsMain((false))
                    ->setUser($user);

            $manager->persist($contact);
        }

        $manager->flush();
    }
}