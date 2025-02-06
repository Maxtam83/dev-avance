<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface The password hasher service for securing user passwords.
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher service.
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
	{
		// Création d'un utilisateur de test
		$user = new User();
		$user->setUsername('admin');
        $user->setEmail('admin@admin.fr');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);

		$manager->persist($user);

		// Création de plusieurs utilisateurs
		for ($i = 1; $i <= 5; $i++) {
			$user = new User();
			$user->setUsername('user' . $i);
            $user->setEmail('userpass' . $i . '@admin.fr');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'userpass' . $i));
            $user->setRoles(['ROLE_USER']);
			$manager->persist($user);
		}

		$manager->flush();
	}
}
