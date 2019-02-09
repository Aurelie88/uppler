<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 */
class UserController extends AbstractController
{
	/**
     * @Route("/profil", name="profil")
     */
	function index(){
		return $this->render('profil.html.twig');
	}
}