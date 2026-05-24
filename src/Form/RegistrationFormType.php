<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Full Name',
                'attr'        => [
                    'class'       => 'form-input',
                    'placeholder' => 'John Doe',
                    'autofocus'   => true,
                ],
                'constraints' => [
                    new NotBlank(message: 'Please enter your name.'),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Your name must be at least {{ limit }} characters.',
                        maxMessage: 'Your name cannot exceed {{ limit }} characters.',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr'  => [
                    'class'       => 'form-input',
                    'placeholder' => 'you@example.com',
                ],
                'constraints' => [
                    new NotBlank(message: 'Please enter your email.'),
                    new Email(message: 'Please enter a valid email address.'),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'           => PasswordType::class,
                'mapped'         => false,          // NOT a DB field – hashed in controller
                'first_options'  => [
                    'label' => 'Password',
                    'attr'  => [
                        'class'       => 'form-input',
                        'placeholder' => 'Min. 8 characters',
                        'autocomplete'=> 'new-password',
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Please enter a password.'),
                        new Length(
                            min: 8,
                            max: 4096,
                            minMessage: 'Your password must be at least {{ limit }} characters.',
                        ),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr'  => [
                        'class'       => 'form-input',
                        'placeholder' => 'Repeat your password',
                        'autocomplete'=> 'new-password',
                    ],
                ],
                'invalid_message' => 'The password fields do not match.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
