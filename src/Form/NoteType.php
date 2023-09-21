<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // - Input pour la note utilisateur
            ->add('userNote', ChoiceType::class, [
                'choices' => $this->createNoteChoices(),
                'label' => 'Note de l\'utilisateur',
                'attr' => ['class' => 'form-control mb-3']
            ])
            // - Input pour la note médias
            ->add('mediaNote', ChoiceType::class, [
                'choices' => $this->createNoteChoices(),
                'label' => 'Note de la presse',
                'attr' => ['class' => 'form-control mb-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
            'attr' => ['class' => 'form']
        ]);
    }

    // - Générer une liste de 0 à 20
    public function createNoteChoices(): array
    {
        $choices = [];
        for ($i = 0; $i <= 20; $i++) {
            $choices[$i] = $i;
        }
        return $choices;
    }
}
