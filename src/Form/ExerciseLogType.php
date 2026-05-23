<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exercise', EntityType::class, [
                'class' => Exercise::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose an exercise',
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration (minutes)',
            ])
            ->add('caloriesBurned', IntegerType::class, [
                'label' => 'Calories burned',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExerciseLog::class,
        ]);
    }
}
