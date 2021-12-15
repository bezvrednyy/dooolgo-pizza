<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="email.error")
     * @Assert\Email(
     *     mode="strict",
     *     message="email.error"
     * )
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="password.error")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9]+[a-zA-Z0-9]{7,}$/",
     *     message="password.error"
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="name.error")
     * @Assert\Regex(
     *     pattern="/^[a-zа-яё]+[a-zа-яё\s\-]*$/ui",
     *     message="name.error"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="address.error")
     * @Assert\Regex(
     *     pattern="/^[a-zа-яё]+[a-zа-яё0-9\s\-\.,]*$/ui",
     *     message="address.error"
     * )
     *
     */
    private string $address;



    public function __construct($id, $email, $name, $password, $address, $roles)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->address = $address;
        $this->roles = $roles;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {

        // not needed when using the "bcrypt" algorithm in bundles.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
