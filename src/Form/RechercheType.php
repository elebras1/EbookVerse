<?php

namespace App\Form;

use App\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recherche', SearchType::class, [
                'attr' => [
                    'class' => 'block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'placeholder' => 'Entrez le titre d\'un livre, le nom d\'un auteur...'
                ],
                'label' => 'Recherche',
                'label_attr' => [
                    'class' => 'mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white'
                ],
                'required' => false,
            ])
            
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'multiple' => true,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'select-genre w-2/5 z-10'
                ],
                'label' => 'Genres',
                'label_attr' => [
                    'class' => ''
                ],
                'required' => false,
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'bg-blue-700 hover:bg-blue-800 text-white font-bold ml-3 py-2 px-5 rounded-md'
                ],
                'label' => '<img class="w-4" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/VisualEditor_-_Icon_-_Search-big_-_white.svg/1200px-VisualEditor_-_Icon_-_Search-big_-_white.svg.png" alt="Envoyer">',
                'label_html' => true
            ])
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET', // Set the form method to GET
            'csrf_protection' => false,
        ]);
    }
}
