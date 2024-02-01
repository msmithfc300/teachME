<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('level')
            ->add('subject')
            ->add('subject_fk', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'name',
                'attr' => [
                    'style' => 'display: none;', // Cela masquera le champ dans le formulaire
                ],
                'label' => false, // Cela masquera le label du champ dans le formulaire
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}