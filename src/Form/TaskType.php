<?php

namespace App\Form;

use App\Entity\Room;
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

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('startDate', null, [
                'widget' => 'single_text',
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ])
            ->add('finishDate', null, [
                'widget' => 'single_text',
            ])
            ->add('status', TextType::class)
            ->add('leader', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
            ])
            ->add('member', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
