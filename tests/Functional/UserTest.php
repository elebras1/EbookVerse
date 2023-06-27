<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    public function testLoginClient()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/utilisateur/connexion');

        $form = $crawler->selectButton('envoyer')->form();
        $form['_pseudo'] = 'Pseudo0';
        $form['_motdepasse'] = 'password1234567';

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/');

        return $client;
    }

    
}
