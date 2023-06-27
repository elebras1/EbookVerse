<?php

namespace App\Tests\Functional;

use App\Tests\Functional\UserTest;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class livreTest extends WebTestCase
{
    public function testLivreForm(): void
    {
        $user = new UserTest;
        $client = $user->testloginClient();
        $crawler = $client->request('GET', '/listelivre/newlivre');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Ajouter un livre');

        $submitButton = $crawler->filter('#livre_envoyer')->first();
        $form = $submitButton->form();

        $form["livre[titre]"] = "Il est vivant";
        $form["livre[description]"] = "il est vivant ....";
        $form["livre[annee]"] = "2013";
        $form["livre[image]"] = "C:/Users/erwan/Downloads/dev_web_notes.png";
        $form["livre[ebook]"] = "C:/Users/erwan/Downloads/Midi et matin.epub";
        $form["livre[auteur]"] = ["92","93","94"];
        $form["livre[genre]"] = "11";

        $client->submit($form);

        $livreRepository = $client->getContainer()->get(LivreRepository::class);
        $dernierLivre = $livreRepository->findOneBy([], ['id' => 'DESC']);
        $dernierLivreId = $dernierLivre->getId();

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
        $redirectUrl = $response->headers->get('Location');
        $this->assertEquals('/listelivre/displaylivre/'.$dernierLivreId, $redirectUrl);   
    }

    public function testLivreFormInvalid(): void
    {
        $user = new UserTest;
        $client = $user->testloginClient();
        $crawler = $client->request('GET', '/listelivre/newlivre');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Ajouter un livre');

        $submitButton = $crawler->filter('#livre_envoyer')->first();
        $form = $submitButton->form();

        $form["livre[titre]"] = "I";
        $form["livre[description]"] = "il est";
        $form["livre[annee]"] = "2025";
        $form["livre[image]"] = "C:/Users/erwan/Downloads/dev_web_notes.avi";
        $form["livre[ebook]"] = "C:/Users/erwan/Downloads/Midi et matin.azw";
        $form["livre[auteur]"] = ["92","93","94"];
        $form["livre[genre]"] = "11";

        $client->submit($form);

        $livreRepository = $client->getContainer()->get(LivreRepository::class);
        $dernierLivre = $livreRepository->findOneBy([], ['id' => 'DESC']);
        $dernierLivreId = $dernierLivre->getId();

        $response = $client->getResponse();
        $this->assertFalse($response->isRedirect()); 
    }

    public function testlistelivre(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('listelivre.index'));
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('listelivre.index');

    }
}
