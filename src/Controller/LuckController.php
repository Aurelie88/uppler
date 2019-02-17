<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LuckController extends AbstractController
{
	
	/**
     * @Route("/test", name="test")
     */
    public function index()
    {
        //page de description du projet visible sans connexion
        return array(
        	'template' => 'parent.html.twig',
        	'data' => array(
        		'valeur' => 'hello world'
    			)
			);
    }
}