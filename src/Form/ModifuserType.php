<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ModifuserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [
            'ROLE_ADMIN'            => 'ROLE_ADMIN',
            'ROLE_USER'             => 'ROLE_USER',
        ];
 
        if($options['type_register'] == 'sub_account') {
            $roles = [
                'ROLE_USER'             => 'ROLE_USER',
                'ROLE_CUSTOMER'         => 'ROLE_CUSTOMER',
                'ROLE_CUSTOMER_ADMIN'   => 'ROLE_CUSTOMER_ADMIN',
            ];
        };


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
            ->add('roles', ChoiceType::class, [
                'multiple' => true, 
                'expanded' => true, 
                'choices' => $roles,
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez faire un choix"])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'type_register' => 'account'
        ]);
    }
}
