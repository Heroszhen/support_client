<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\CustomerType;
use App\Form\CreateType;

class CustomerWithUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CustomerType::class, [
                'label' => false
            ])
            ->add('user', CreateType::class, [
                'label' => false
            ])
            // On enleve les champs password et roles de l'utilisateur 
            // (il sera généré via le controller)
            ->get('user')
                ->remove('password')
                ->remove('roles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
