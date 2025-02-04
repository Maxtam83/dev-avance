<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255, unique: true)]
	private ?string $login = null;

	#[ORM\Column(length: 255)]
	private ?string $password = null;

	#[ORM\Column]
	private array $roles = [];

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getLogin(): ?string
	{
		return $this->login;
	}

	public function setLogin(string $login): static
	{
		$this->login = $login;
		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = $password;
		return $this;
	}

	public function getRoles(): array
	{
		return $this->roles ?: ['ROLE_USER'];
	}

	public function setRoles(array $roles): static
	{
		$this->roles = $roles;
		return $this;
	}

	public function eraseCredentials(): void
	{
		// On laisse vide (utilisé pour supprimer des données sensibles après connexion)
	}

	public function getUserIdentifier(): string
	{
		return $this->login;
	}
}
