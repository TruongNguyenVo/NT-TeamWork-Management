<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Email đã được sử dụng.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    // /**
    //  * @var list<string> The user roles
    //  */
    // #[ORM\Column]
    // private array $roles = [];

    #[ORM\Column(length: 180)]
    private ?string $firstname = null;

    #[ORM\Column(length: 180)]
    private ?string $lastname = null;


    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    /**
     * @var Collection<int, UserRoom>
     */
    #[ORM\OneToMany(targetEntity: UserRoom::class, mappedBy: 'user')]
    private Collection $userRooms;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'leader')]
    private Collection $leader;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'member')]
    private Collection $member;

    public function __construct()
    {
        $this->userRooms = new ArrayCollection();
        $this->leader = new ArrayCollection();
        $this->member = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // /**
    //  * @see UserInterface
    //  *
    //  * @return list<string>
    //  */
    // public function getRoles(): array
    // {
    //     $roles = $this->roles;
    //     // guarantee every user at least has ROLE_USER
    //     $roles[] = 'ROLE_USER';

    //     return array_unique($roles);
    // }

    // ROLE de trong bang userroom
    public function getRoles(): array
        {
            return [];
        }
    // /**
    //  * @param list<string> $roles
    //  */
    // public function setRoles(array $roles): static
    // {
    //     $this->roles = $roles;

    //     return $this;
    // }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $userRoom->setUser($this);
        }

        return $this;
    }

    public function removeUserRoom(UserRoom $userRoom): static
    {
        if ($this->userRooms->removeElement($userRoom)) {
            // set the owning side to null (unless already changed)
            if ($userRoom->getUser() === $this) {
                $userRoom->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getLeader(): Collection
    {
        return $this->leader;
    }

    public function addLeader(Task $leader): static
    {
        if (!$this->leader->contains($leader)) {
            $this->leader->add($leader);
            $leader->setLeader($this);
        }

        return $this;
    }

    public function removeLeader(Task $leader): static
    {
        if ($this->leader->removeElement($leader)) {
            // set the owning side to null (unless already changed)
            if ($leader->getLeader() === $this) {
                $leader->setLeader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(Task $member): static
    {
        if (!$this->member->contains($member)) {
            $this->member->add($member);
            $member->setMember($this);
        }

        return $this;
    }

    public function removeMember(Task $member): static
    {
        if ($this->member->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getMember() === $this) {
                $member->setMember(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->lastname . ' ' . $this->firstname;
    }
}
