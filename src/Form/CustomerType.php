<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>"nom",
                'attr' => ['placeholder' => "nom"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un nom"])
                ],
                'required'=>false
            ])
            ->add('siret',TextType::class,[
                'label'=>"Siret",
                'attr' => ['placeholder' => "siret"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un siret"])
                ],
                'required'=>false
            ])
            ->add('address1',TextType::class,[
                'label'=>"Address1",
                'attr' => ['placeholder' => "address1"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre une adresse"])
                ],
                'required'=>false
            ])
            ->add('address2',TextType::class,[
                'label'=>"Address2",
                'attr' => ['placeholder' => "address2"],
                'required'=>false
            ])
            ->add('City',TextType::class,[
                'label'=>"Ville",
                'attr' => ['placeholder' => "ville"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre une ville"])
                ],
                'required'=>false
            ])
            ->add('zipcode',TextType::class,[
                'label'=>"Code postal",
                'attr' => ['placeholder' => "code postal"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un code"])
                ],
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
