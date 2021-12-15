<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Pizza::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Pizza $pizza;

    public function __construct($id, $number, $pizza, $user, $status)
    {
        $this->id = $id;
        $this->number = $number;
        $this->pizza = $pizza;
        $this->user = $user;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPizza(): Pizza
    {
        return $this->pizza;
    }

    public function setPizza(Pizza $pizza): self
    {
        $this->pizza = $pizza;

        return $this;
    }
}