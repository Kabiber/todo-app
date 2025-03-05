<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('description', TextareaType::class, ['label' => 'Описание', 'required' => false])
            ->add('completed', CheckboxType::class, ['label' => 'Готовность', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'csrf_protection' => true, // Убедитесь, что CSRF включён
            'csrf_field_name' => '_token', // Имя поля токена
            'csrf_token_id' => 'task_form', // ID токена для формы
        ]);
    }
}