<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Entity\Tuteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Tuteur', EntityType::class, [
                'class' => Tuteur::class,
                'choice_label' =>  'User.fullname',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('Niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' =>  'libelle',
                'multiple' => false,
                'expanded' => true,
            ])

            ->add('Matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' =>  'libelle',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('libelle')
            ->add('description')
            ->add('documents',FileType::class,[
               'required'=>false,
               'label'=>false,
                'multiple'=>true,
                'mapped'=>false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
