<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class NewsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('news/index.html.twig');
    }

    /**
     * @Route("/new/{id}", name="new.view")
     */
    public function new()
    {
        return $this->render('news/new.html.twig');
    }
}