<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>"Nom",
                'attr' => ['placeholder' => "nom"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre un nom"])
                ],
                'required'=>false
            ])
            ->add('priority',TextType::class,[
                'label'=>"Priorité",
                'attr' => ['placeholder' => "Priorité"],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre une priorité"])
                ],
                'required'=>false
            ])
            ->add('description',TextareaType::class,[
                "label"=>"Description",
                'attr' => [
                    'placeholder' => "Description",
                    'rows'=>4
                ],
                'constraints' => [
                    new NotBlank(["message"=>"Veuillez mettre une description"])
                ],
                'required'=>false
            ])
            ->add('files',FileType::class,[
                "multiple" => true,
                "mapped" => false,
                'label'=>" ",
                'attr' => [
                    'placeholder' => " ",
                    "class" => "thefile",
                    "multiple" => "multiple"
                ],
                "data_class" => null,
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
