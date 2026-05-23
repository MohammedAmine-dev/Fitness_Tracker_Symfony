<?php
namespace App\Form;

use App\Entity\Goal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('targetWeight', NumberType::class, ['required' => false, 'label' => 'Target Weight (kg)'])
            ->add('dailyCalories', IntegerType::class, ['required' => false, 'label' => 'Daily Calorie Goal'])
            ->add('weeklyWorkouts', IntegerType::class, ['required' => false, 'label' => 'Weekly Workouts']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Goal::class]);
    }
}