<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'annonce *",
                'attr' => ['placeholder' => "Lot de carte Magic"],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description *",
                'attr' => ['placeholder' => "Bon état général, lot de X cartes, ...", 'rows' => 10],
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => "Prix (euros) *",
                'attr' => ['placeholder' => "42"],
                'scale' => 2,
                'required' => true,
            ])
            ->add('isVisible', ChoiceType::class, [
                'label' => "Publier l'annonce directement ?",
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'empty_data' => true,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
