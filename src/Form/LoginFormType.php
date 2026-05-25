<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'    => 'Email Address',
                'attr'     => [
                    'placeholder'  => 'jane@example.com',
                    'autocomplete' => 'email',
                    'class'        => 'form-input has-icon-left',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Email address is required.']),
                    new Email(['message'   => 'Please enter a valid email address.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr'  => [
                    'placeholder'  => 'Your password',
                    'autocomplete' => 'current-password',
                    'class'        => 'form-input has-icon-left has-icon-right',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Password is required.']),
                ],
            ])
            ->add('remember', CheckboxType::class, [
                'label'    => 'Remember me for 30 days',
                'required' => false,
                'attr'     => ['class' => ''],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
            'csrf_token_id'   => 'login_form',
        ]);
    }
}
