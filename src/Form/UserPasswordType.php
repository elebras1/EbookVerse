<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('mot_de_passe', PasswordType::class, [
            'attr' => [
                'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'maxlength' => '60',
                'minlength' => '15'
            ],
            'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4'
                ],
                'constraints' => [
                    new Length(['min' => 15, 'max' => 60]),
                    new NotBlank()
                ],
                'invalid_message' => 'Le mot de passe ne correspond pas.'
        ])

        ->add('new_mot_de_passe', RepeatedType::class, [
            'type' =>  PasswordType::class,
            'first_options' => [
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'maxlength' => '60',
                    'minlength' => '15'
                ],
                'label' => 'Nouveau Mot de passe',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4'
                ],
                'constraints' => [
                    new Length(['min' => 15, 'max' => 60]),
                    new NotBlank()
                ]
            ],
            'second_options' => [
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'maxlength' => '60',
                    'minlength' => '15'
                ],
                'label' => 'Confirmation du nouveau mot de passe',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4'
                ],
                'constraints' => [
                    new Length(['min' => 15, 'max' => 60]),
                    new NotBlank()
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ]
        ])

        ->add('envoyer', SubmitType::class, [
            'attr' => [
                'class' => 'bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded mt-5'
            ]
        ]);
        
    }
}

?>