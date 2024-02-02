<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, ['label' => false])
            ->add('email', EmailType::class, ['label' => false,  'constraints' => [
        new NotBlank(),  new Length([
            'min' => 2,
            'max' => 180,

        ])]])
            ->add('agreeTerms', CheckboxType::class, ['label' => false,
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Tick me :)",
                    ]),
                ],
            ])
            ->add('plainPassword',  RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => false],
                'second_options' => ['label' => false],
                'invalid_message' => "the passwords don't match",
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'your password should contain at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,

                    ])
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Student' => 'ROLE_STUDENT',
                    'Teacher' => 'ROLE_TEACHER',
                ],

            ])
            ->get('roles')->addModelTransformer(new CallbackTransformer(
                fn($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0] : null,
                fn($rolesAsString) => [$rolesAsString]
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
