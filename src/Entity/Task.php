<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'leader')]
    private ?User $leader = null;

    #[ORM\ManyToOne(inversedBy: 'member')]
    private ?User $member = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Room $room = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pathAttachment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resultContent = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resultAttachment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finishDate = null;

    #[Assert\Regex(
        pattern: '/^(pending|in_progress|review|done|cancelled)$/',
        message: 'Chỉ có thể nhận trạng thái pending, in_progress, review, done, cancelled.'
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = 'pending';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeader(): ?User
    {
        return $this->leader;
    }

    public function setLeader(?User $leader): static
    {
        $this->leader = $leader;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): static
    {
        $this->member = $member;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPathAttachment(): ?string
    {
        return $this->pathAttachment;
    }

    public function setPathAttachment(?string $pathAttachment): static
    {
        $this->pathAttachment = $pathAttachment;

        return $this;
    }

    public function getResultContent(): ?string
    {
        return $this->resultContent;
    }

    public function setResultContent(?string $resultContent): static
    {
        $this->resultContent = $resultContent;

        return $this;
    }

    public function getResultAttachment(): ?string
    {
        return $this->resultAttachment;
    }

    public function setResultAttachment(?string $resultAttachment): static
    {
        $this->resultAttachment = $resultAttachment;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(?\DateTimeInterface $finishDate): static
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
