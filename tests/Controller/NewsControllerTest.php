<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\NewsController;
use PHPUnit\Framework\TestCase;

class NewsControllerTest extends TestCase
{
    //Home page accessible sans authentification
    /*public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }*/

    public function testVerificationAuthentification()
    {
        /*$controller = $this->getMockBuilder('App\NewsController')->disableOriginalConstructor()->getMock();

        $clientSignIn=static::createClient([], [
        'PHP_AUTH_USER' => 'admin',
        'PHP_AUTH_PW'   => '$2y$13$PvtxkEDvBRQhptesuoMP4u9v092tGz0sU3bHt2hfcgn/k6OejSUVi',
        ]);

        $clientSignIn->request('GET', '/news');
        $this->assertEquals(200, $clientSignIn->getResponse()->getStatusCode());

        $client=static::createClient();

        $client->request('GET', '/news');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $controller
            ->except($this->once())
            ->method('verificationAuthentification')
            ->with($client)
            ->will($this->returnValue($clientSignIn));*/

        /*$controller = $this->getMockBuilder('App\NewsController')->disableOriginalConstructor()->getMock();
        $this->exceptException('HttpException');

        $controller->recupereAuthentification();*/
    }
}
