<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full Name',
                'attr'  => [
                    'placeholder'  => 'Jane Doe',
                    'autocomplete' => 'name',
                    'class'        => 'form-input has-icon-left',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Full name is required.']),
                    new Length([
                        'min'        => 2,
                        'minMessage' => 'Name must be at least {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr'  => [
                    'placeholder'  => 'jane@example.com',
                    'autocomplete' => 'email',
                    'class'        => 'form-input has-icon-left',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Email address is required.']),
                    new Email(['message'   => 'Please enter a valid email address.']),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'mapped'          => false,
                'first_options'   => [
                    'label' => 'Password',
                    'attr'  => [
                        'placeholder'  => 'Min. 8 characters',
                        'autocomplete' => 'new-password',
                        'minlength'    => '8',
                        'class'        => 'form-input has-icon-left has-icon-right',
                    ],
                    'constraints' => [
                        new NotBlank(['message' => 'Password is required.']),
                        new Length([
                            'min'        => 8,
                            'minMessage' => 'Password must be at least {{ limit }} characters.',
                        ]),
                    ],
                ],
                'second_options'  => [
                    'label' => 'Confirm Password',
                    'attr'  => [
                        'placeholder'  => 'Repeat your password',
                        'autocomplete' => 'new-password',
                        'class'        => 'form-input has-icon-left',
                    ],
                ],
                'invalid_message' => 'Passwords do not match.',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped'      => false,
                'label'       => false,
                'constraints' => [
                    new IsTrue(['message' => 'Please accept the Terms of Service to continue.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
            'csrf_token_id'   => 'registration_form',
        ]);
    }
}
