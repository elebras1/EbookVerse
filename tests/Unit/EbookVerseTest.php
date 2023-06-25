<?php

namespace App\Tests\Unit;

use App\Entity\Type;

use App\Entity\Ecrit;
use App\Entity\Genre;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Discussion;
use function PHPUnit\Framework\assertCount;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EbookVerseTest extends KernelTestCase
{
    public $compte;
    public $profil;
    public $discussion;
    public $livre;
    public $auteur;
    public $ecrit;
    public $genre;
    public $type;

    
    protected function setUp(): void
    {
        parent::setUp();
        $this->compte = new Compte();
        $this->profil = new Profil();
        $this->discussion = new Discussion();
        $this->livre = new Livre();
        $this->auteur = new Auteur();
        $this->ecrit = new Ecrit();
        $this->genre = new Genre();
        $this->type = new Type();
    }

    public function testEntityCompte(): void
    {
        self::bootKernel();
        $this->compte->setMotDePasse('passwordpassword')
            ->setPseudo('utilisateur');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->compte);

        $this->assertCount(0, $errors);
    }

    public function testEntityProfil(): void
    {
        self::bootKernel();
        $this->profil->setCompte($this->compte)
            ->setNom('Le Bert')
            ->setPrenom('Y\'ve');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->profil);

        $this->assertCount(0, $errors);
    }

    public function testEntityDiscussion(): void
    {
        self::bootKernel();
        $this->discussion->setCompte($this->compte)
            ->setMessage('Message de 23/06/2023');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->discussion);

        $this->assertCount(0, $errors);
    }

    public function testEntityLivre(): void
    {
        self::bootKernel();
        $this->livre->setCompte($this->compte)
            ->setTitre('Titre du livre')
            ->setDescription('Description du livre qui devrait...')
            ->setAnnee('2002')
            ->setImage('/iamges.jpeg')
            ->setEbook('/ebook.pub');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->livre);

        $this->assertCount(0, $errors);
    }

    public function testEntityAuteur(): void
    {
        self::bootKernel();
        $this->auteur->setNom('Prijio')
            ->setPrenom('Vladmir')
            ->setDescription('C\est une description de l\'auteur');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->auteur);

        $this->assertCount(0, $errors);
    }

    public function testEntityEcrit(): void
    {
        self::bootKernel();
        $this->ecrit->setAuteur($this->auteur)
            ->setLivre($this->livre);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->ecrit);

        $this->assertCount(0, $errors);
    }

    public function testEntityGenre(): void
    {
        self::bootKernel();
        $this->genre->setNom('Action');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->genre);

        $this->assertCount(0, $errors);
    }

    public function testEntityType(): void
    {
        self::bootKernel();
        $this->type->setGenre($this->genre)
            ->setLivre($this->livre);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->type);

        $this->assertCount(0, $errors);
    }
    
    public function testEntityInvalidDiscussion(): void
    {
        self::bootKernel();

        $this->discussion->setMessage('')
            ->setCompte($this->compte);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->discussion);

        $this->assertCount(2, $errors);
    }

    public function testEntityInvalidProfil(): void
    {
        self::bootKernel();

        $this->profil->setNom('')
            ->setPrenom('');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->profil);

        $this->assertCount(3, $errors);
    }

    public function testEntityInvalidCompte(): void
    {
        self::bootKernel();

        $this->compte->setMotDePasse('')
            ->setPseudo('')
            ->setProfil(null);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->compte);

        $this->assertCount(4, $errors);
    }

    public function testEntityInvalidLivre(): void
    {
        self::bootKernel();

        $this->livre->setCompte($this->compte)
            ->setTitre('')
            ->setDescription('')
            ->setAnnee('-5000')
            ->setImage('')
            ->setEbook('');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->livre);

        $this->assertCount(7, $errors);
    }

    public function testEntityInvalidType(): void
    {
        self::bootKernel();

        $this->type->setGenre(null)
            ->setLivre(null);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->type);

        $this->assertCount(2, $errors);
    }

    public function testEntityInvalidEcrit(): void
    {
        self::bootKernel();

        $this->ecrit->setAuteur(null)
            ->setLivre(null);

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->ecrit);

        $this->assertCount(2, $errors);
    }

    public function testEntityInvalidAuteur(): void
    {
        self::bootKernel();

        $this->auteur->setPrenom('')
            ->setNom('')
            ->setDescription('');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->auteur);

        $this->assertCount(5, $errors);
    }

    public function testEntityInvalidGenre(): void
    {
        self::bootKernel();

        $this->genre->setNom('');

        $container = static::getContainer();
        $errors = $container->get('validator')->validate($this->genre);

        $this->assertCount(2, $errors);
    }
}
