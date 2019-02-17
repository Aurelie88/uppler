<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class NewsControllerTest extends WebTestCase
{
	//Home page accessible sans authentification
	public function testIndex()
	{
		$client = static::createClient();

		$client->request('GET', '/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testNews()
	{
		//Si le client n'est pas connecter on lance une error 403
		$client = static::createClient();

		$client->request('GET', '/news');

		$this->assertEquals(403, $client->getResponse()->getStatusCode());

		//Authentification du client retour 200
		$clientSignIn=static::createClient([], [
    	'PHP_AUTH_USER' => 'admin',
    	'PHP_AUTH_PW'   => 'admin123456',
		]);

		$clientSignIn->request('GET', '/news');

		$this->assertEquals(200, $clientSignIn->getResponse()->getStatusCode());
	}

	public function testNewAdd(){
		//Si le client n'est pas connecter on lance une error 403
		$client = static::createClient();

		$client->request('GET', '/add/news');

		$this->assertEquals(403, $client->getResponse()->getStatusCode());

		//Authentification du client retour 200
		$clientSignIn=static::createClient([], [
    	'PHP_AUTH_USER' => 'admin',
    	'PHP_AUTH_PW'   => 'admin123456',
		]);

		$clientSignIn->request('GET', '/add/news');

		$this->assertEquals(200, $clientSignIn->getResponse()->getStatusCode());
	}

}