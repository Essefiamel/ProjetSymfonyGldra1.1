<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantType extends AbstractType
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
            ->add('Niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'libelle',
            ])
            ->add('Matricule')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
