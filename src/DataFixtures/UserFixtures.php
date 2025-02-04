<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

	public function load(ObjectManager $manager): void
	{
		// Création d'un utilisateur de test
		$user = new User();
		$user->setLogin('admin');

		// Hash du mot de passe
		$hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
		$user->setPassword($hashedPassword);

		$manager->persist($user);

		// Ajout d'autres utilisateurs en boucle si nécessaire
		for ($i = 1; $i <= 5; $i++) {
			$user = new User();
			$user->setLogin('user' . $i);
			$user->setPassword($this->passwordHasher->hashPassword($user, 'userpass' . $i));

			$manager->persist($user);
		}

		$manager->flush();
	}
}
