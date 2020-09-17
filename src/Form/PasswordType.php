<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType as PWTType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password',PWTType::class,[
                'label'=>"Nouveau mot de passe",
                'attr' => ['placeholder' => "nouveau mot de passe"],
                'constraints'=> [
                    new NotBlank(['message'=> 'Le mot de passe est obligatoire']),
                    new Length([
                        'min'=>8,
                        'max'=>20,
                        'minMessage'=>'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        'maxMessage'=>'Le mot de passe doit contenir au plus {{ limit }} caractères'
                    ])
                ],
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
