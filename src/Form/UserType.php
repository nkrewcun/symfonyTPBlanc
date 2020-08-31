<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $class = 'form-control';
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.add.employee.email.label',
                'attr' => [
                    'class' => $class,
                    'placeholder' => 'form.add.employee.email.input'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'form.add.employee.password.label',
                'attr' => [
                    'class' => $class,
                    'placeholder' => 'form.add.employee.password.input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.add.employee.password.required',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'form.add.employee.password.minLength',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern'=> "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/",
                        'match' => true,
                        'message'=> "form.add.employee.password.format"
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'form.add.employee.firstname.label',
                'attr' => [
                    'class' => $class,
                    'placeholder' => 'form.add.employee.firstname.input'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'form.add.employee.lastname.label',
                'attr' => [
                    'class' => $class,
                    'placeholder' => 'form.add.employee.lastname.input'
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'form.add.employee.picture',
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ])
            ->add('sector', EntityType::class, [
                'label' => 'form.add.employee.sector.label',
                'class' => Sector::class,
                'attr' => [
                    'class' => $class
                ]
            ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'form.add.employee.submit',
                    'attr' => [
                        'class' => 'btn btn-secondary',
                    ]
                ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
