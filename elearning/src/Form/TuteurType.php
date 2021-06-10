<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\User;
use App\Entity\Tuteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' =>  'fullname',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('specialite')
            ->add('grade')
            ->add('titulaire',ChoiceType::class,[
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('diplome')
            ->add('Niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' =>  'libelle',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('Matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' =>  'libelle',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tuteur::class,
        ]);
    }
}
