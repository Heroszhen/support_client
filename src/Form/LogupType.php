<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LogupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname',TextType::class,[
                'label'=>"Nom",
                'attr' => ['placeholder' => "nom"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un nom"])
                ],
                'required'=>false
            ])
            ->add('firstname',TextType::class,[
                'label'=>"Prénom",
                'attr' => ['placeholder' => "prénom"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un prénom"])
                ],
                'required'=>false
            ])
            ->add('email',EmailType::class,[
                'label'=>"Email",
                'attr' => ['placeholder' => "mail"],
                'constraints'=> [
                    new NotBlank(['message'=> 'Le mail est obligatoire']),
                    new Email(['message'=>'Indiquez un mail valide'])
                ],
                'required'=>false
            ])
            ->add('password',PasswordType::class,[
                'label'=>"Mot de passe",
                'attr' => ['placeholder' => "mot de passe"],
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
