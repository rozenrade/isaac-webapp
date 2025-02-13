<?php

// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@example.com')
            ->setUsername('testuser')
            ->setPassword('testpassword'); // Assurez-vous de hacher le mot de passe si nécessaire

        $manager->persist($user);
        $manager->flush();
    }
}

