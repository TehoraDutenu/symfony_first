<?php

namespace App\Form;

use App\Entity\Age;
use App\Entity\Game;
use App\Form\NoteType;
use App\Entity\Console;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // - Champ input pour le titre
            ->add('title', TextType::class, [
                'label' => 'Titre du jeu',
                'attr' => ['class' => 'form-control mb-3']
            ])
            // - Champ input pour la description
            ->add('description', TextareaType::class, [
                'label' => 'Description du jeu',
                'attr' => ['class' => 'form-control mb-3']
            ])
            // - Champ integer pour le prix
            ->add('price', IntegerType::class, [
                'label' => 'Prix du jeu',
                'attr' => ['class' => 'form-control mb-3']
            ])
            // - Champ pour la date de sortie
            ->add('releaseDate', DateTimeType::class, [
                'label' => 'Date de sortie du jeu : (jj/mm/yyyy)',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'form-control mb-3 datepicker'],
                'input' => 'timestamp'
            ])
            // - Champ pour l'upload de l'image
            ->add('image', FileType::class, [
                'label' => 'Image du jeu (JPG, JPEG, PNG, WEBP)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image valide (JPG, JPEG, PNG, WEBP)'
                    ])
                ]
            ])

            // - Champ pour s'occuper des notes
            // - Imbrique le formulaire dans
            ->add('note', NoteType::class, [
                'label' => false
            ])
            ->add('age', EntityType::class, [
                'label' => 'Restriction d\'age',
                'class' => Age::class,
                'choice_label' => 'label',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('consoles', EntityType::class, [
                'label' => 'Disponible sur : ',
                'class' => Console::class,
                'choice_label' => 'label',
                'attr' => ['class' => 'form-control mb-3'],
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'attr' => ['class' => 'form']
        ]);
    }
}
