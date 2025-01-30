<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;

    /**
     * @var Collection<int, UserRoom>
     */
    #[ORM\OneToMany(targetEntity: UserRoom::class, mappedBy: 'room')]
    private Collection $userRooms;

    public function __construct()
    {
        $this->userRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = new \DateTime();

        return $this;
    }

    /**
     * @return Collection<int, UserRoom>
     */
    public function getUserRooms(): Collection
    {
        return $this->userRooms;
    }

    public function addUserRoom(UserRoom $userRoom): static
    {
        if (!$this->userRooms->contains($userRoom)) {
            $this->userRooms->add($userRoom);
            $userRoom->setRoom($this);
        }

        return $this;
    }

    public function removeUserRoom(UserRoom $userRoom): static
    {
        if ($this->userRooms->removeElement($userRoom)) {
            // set the owning side to null (unless already changed)
            if ($userRoom->getRoom() === $this) {
                $userRoom->setRoom(null);
            }
        }

        return $this;
    }
}
