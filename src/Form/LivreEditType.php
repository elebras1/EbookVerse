<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LivreEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = date('Y');

        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'maxlength' => '100'
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints' => [
                    new Length(['max' => 100]),
                    new NotBlank()
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 h-20 w-400',
                    'maxlength' => '1000',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints' => [
                    new Length(['max' => 1000]),
                    new NotBlank()
                ]
            ])

            ->add('annee', IntegerType::class, [
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-16 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 appearance-none',
                    'min' => '-4500',
                    'max' => date('Y')
                ],
                'label' => 'Date de parution',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints' => [
                    new Range(['min' => -4500, 'max' => date('Y')]),
                    new NotBlank()
                ]
            ])

            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'class' => 'block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400',
                    'id' => 'default_size',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white',
                ],
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG de taille maximale 2mo.', // Message d'erreur en cas de type de fichier non autorisé
                    ]),
                ],
            ])

            ->add('ebook', FileType::class, [
                'label' => 'Ebook',
                'required' => false,
                'attr' => [
                    'class' => 'block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400',
                    'id' => 'default_size',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white',
                ],
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/epub+zip',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un ebook au format epub de taille maximale 5mo.', // Message d'erreur en cas de type de fichier non autorisé
                    ]),
                ],
            ])

            ->add('auteur', EntityType::class, [
                'mapped' => false,
                'class' => Auteur::class,
                'multiple' => true,
                'data' => $options['existing_auteurs'],
                'choice_label' => function ($auteur) {
                    return $auteur->getNom() . ' ' . $auteur->getPrenom();
                },
                'attr' => [
                    'class' => 'select-auteur w-64'
                ],
                'label' => 'Auteurs',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
            ])

            ->add('genre', EntityType::class, [
                'mapped' => false,
                'class' => Genre::class,
                'multiple' => true,
                'data' => $options['existing_genres'],
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'select-genre w-64'
                ],
                'label' => 'Genres',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded mt-5'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
            'existing_auteurs' => [],
            'existing_genres' => []
        ]);
    }
}
