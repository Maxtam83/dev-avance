<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		// Création d'un utilisateur de test
		$user = new User();
		$user->setLogin('admin');
		$user->setPassword('password123'); // Mot de passe en clair
        $user->setRoles(['ROLE_ADMIN']);

		$manager->persist($user);

		// Création de plusieurs utilisateurs
		for ($i = 1; $i <= 5; $i++) {
			$user = new User();
			$user->setLogin('user' . $i);
			$user->setPassword('userpass' . $i); // Mot de passe en clair
            $user->setRoles(['ROLE_USER']);
			$manager->persist($user);
		}

		$manager->flush();
	}
}
