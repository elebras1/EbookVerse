<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Type;
use Faker\Generator;
use App\Entity\Ecrit;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Discussion;
use App\Entity\Configuration;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr-FR');
    }
    public function load(ObjectManager $manager): void
    {
        $configuration =  new Configuration();
        $configuration->setNom('EbookVerse');
        $configuration->setDescription('Voici la description du site web EbookVerse.');

        $manager->persist($configuration);

        $tab_genre = array();
        for($k=0; $k < 5; $k++){
            $genre = new Genre();
            $genre->setNom($this->faker->word());
            $tab_genre[$k] = $genre;

            $manager->persist($genre);
        }

        for($i=0; $i < 15; $i++) {
            $compte = new Compte();
            $compte->setMotDePasse('password');
            $compte->setPseudo('Peudo'. $i);

            $profil = new Profil();
            $profil->setCompte($compte);
            $profil->setNom($this->faker->word());
            $profil->setPrenom($this->faker->word());

            if($i % 2 == 0)
            {
                $discussion = new Discussion();
                $discussion->setCompte($compte);
                $discussion->setMessage($this->faker->sentence());
            }

            for ($j = 0; $j < 3; $j++)
            {   
                $livre = new Livre();
                $livre->setCompte($compte);
                $livre->setTitre($this->faker->sentence());
                $livre->setAnnee(random_int(1, 2023));
                $livre->setDescription($this->faker->paragraph(3));
                $livre->setImage('https://picsum.photos/200/300');
                $livre->setEbook('ebook.epub');

                $compte->addLivre($livre);

                $manager->persist($livre);
                
                $auteur = new Auteur();
                $auteur->setNom($this->faker->word());
                $auteur->setPrenom($this->faker->word());
                $auteur->setDescription($this->faker->paragraph(4));

                $manager->persist($auteur);

                $ecrit = new Ecrit();
                $ecrit->setLivre($livre);
                $ecrit->setAuteur($auteur);

                $manager->persist($ecrit);

                $type = new Type();
                $type->setGenre($tab_genre[random_int(0, 4)]);
                $type->setLivre($livre);

                $manager->persist($type);
            }

            $manager->persist($compte);
            $manager->persist($profil);
            $manager->persist($discussion);

        }

        $manager->flush();
    }
}
