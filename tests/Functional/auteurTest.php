<?php

namespace App\Tests\Functional;

use App\Repository\AuteurRepository;
use App\Tests\Functional\UserTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class auteurTest extends WebTestCase
{
    public function testAuteurForm(): void
    {
        $user = new UserTest;
        $client = $user->testloginClient();
        $crawler = $client->request('GET', '/listelivre/newauteur');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Ajouter un auteur');

        $submitButton = $crawler->filter('#auteur_envoyer')->first();
        $form = $submitButton->form();

        $form["auteur[nom]"] = "Jornet";
        $form["auteur[prenom]"] = "Mathieu";
        $form["auteur[description]"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ac ultricies metus, id fermentum nibh. In at turpis vitae urna dignissim pulvinar. Nam a aliquam turpis. Aliquam maximus quam ex, ac ultricies turpis aliquam sed. Phasellus varius leo in sapien varius, in bibendum arcu tincidunt. Pellentesque at enim orci. Maecenas molestie luctus tempus.";

        $client->submit($form);

        $auteurRepository = $client->getContainer()->get(AuteurRepository::class);
        $dernierAuteur = $auteurRepository->findOneBy([], ['id' => 'DESC']);
        $dernierAuteurId = $dernierAuteur->getId();

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
        $redirectUrl = $response->headers->get('Location');
        $this->assertEquals('/listelivre/displayauteur/'.$dernierAuteurId, $redirectUrl);   
    }

    public function testAuteurFormInvalid()
    {
        $user = new UserTest;
        $client = $user->testloginClient();
        $crawler = $client->request('GET', '/listelivre/newauteur');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span', 'Ajouter un auteur');

        $submitButton = $crawler->filter('#auteur_envoyer')->first();
        $form = $submitButton->form();

        $form["auteur[nom]"] = "/";
        $form["auteur[prenom]"] = "Mathieu";
        $form["auteur[description]"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ac ultricies metus, id fermentum nibh. In at turpis vitae urna dignissim pulvinar. Nam a aliquam turpis. Aliquam maximus quam ex, ac ultricies turpis aliquam sed. Phasellus varius leo in sapien varius, in bibendum arcu tincidunt. Pellentesque at enim orci. Maecenas molestie luctus tempus.";

        $client->submit($form);

        $response = $client->getResponse();
        $this->assertFalse($response->isRedirect());
    }
}
