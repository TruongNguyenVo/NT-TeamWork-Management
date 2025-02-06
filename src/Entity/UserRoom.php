<?php

namespace App\Entity;

use App\Repository\UserRoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRoomRepository::class)]
class UserRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userRooms')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userRooms')]
    private ?Room $room = null;

    #[ORM\Column(length: 100)]
    #[Assert\Regex(
        pattern: "/^(active|pending|left)$/i",
        message: "Trạng thái không đúng. Chỉ hỗ trợ các trạng thái: active, left, pending"
    )]
    private ?string $status = null;

    #[ORM\Column(length: 100)]
    private ?string $role = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $joinDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }
    public function setJoinDate(?\DateTimeInterface $joinDate): static
    {
        $this->joinDate = new \DateTime();
        return $this;
    }
}
