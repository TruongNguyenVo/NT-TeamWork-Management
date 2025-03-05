<?php

namespace App\Entity;

use App\Repository\TaskDependencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskDependencyRepository::class)]
class TaskDependency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taskDependencies')]
    private ?Task $task = null;

    #[ORM\ManyToOne(inversedBy: 'taskDependencies')]
    private ?task $subTask = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getSubTask(): ?task
    {
        return $this->subTask;
    }

    public function setSubTask(?task $subTask): static
    {
        $this->subTask = $subTask;

        return $this;
    }
}
