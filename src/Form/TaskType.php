<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\UserRoom;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TaskType extends AbstractType
{
    private RoomRepository $roomRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(RoomRepository $roomRepository, EntityManagerInterface $entityManager)
    {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // /** @var Task|null $task */
        // $task = $options['data'] ?? null;
        // $leader = $task ? $task->getLeader($this->entityManager) : null;

        $builder
            ->add('name')
            ->add('content', TextareaType::class)
            ->add('pathAttachment', FileType::class,
            [
                'mapped' => false,
                'required' => false,

            ])
            ->add('resultContent', TextareaType::class,
            [
                'required' => false,

            ])
            ->add('resultAttachment', FileType::class,
            [
                'mapped' => false,
                'required' => false,

            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime('+2 hours'),
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ])
            ->add('finishDate', null, [
                'widget' => 'single_text',
            ])
            ->add('status', TextType::class)

            // ->add('leader', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'fullName',
            // ])
            // ->add('member', EntityType::class, [
            //     'class' => User::class,
            //     // 'choices' => $this->roomRepository->findUserByRoom(),
            //     'choice_label' => 'fullName',
            // ])
            // ->add('room', EntityType::class, [
            //     'class' => Room::class,
            //     'choice_label' => 'name',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
