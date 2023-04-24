<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('existing_address', EntityType::class, [
                'label' => false,
                'class'=> Address::class,
                'choices' => $options['userAddresses'],
                'choice_label' => function ($choice) {
                    return $choice->getStreet() . ' ' . $choice->getZip() . ' ' . $choice->getCity();
                },
                'placeholder' => "Choisir une adresse",
                'empty_data' => null,
                'multiple'=>false,
                'expanded'=>false,
                'mapped' => false,
                'required' => false,
            ])
            ->add('street', TextType::class, [
                'label' => "N° et Nom de rue",
                'attr' => ['placeholder' => "1 rue machin"],
                'mapped' => false,
                'required' => false,
            ])
            ->add('zip', TextType::class, [
                'label' => "Code postal",
                'attr' => ['placeholder' => "75000"],
                'mapped' => false,
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => ['placeholder' => "Paris"],
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Acheter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
            'userAddresses' => array(),
            'allow_extra_fields' => true,
        ]);
    }
}
