<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RedefinePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'form.add.employee.password.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.add.employee.password.required',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'form.add.employee.password.minLength',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern'=> "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/",
                        'match' => true,
                        'message'=> "form.add.employee.password.format"
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.add.employee.password.input'
                ]
            ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Envoyer',
                    'attr' => [
                        'class' => 'btn btn-secondary',
                    ]
                ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
