<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 

use App\Form\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use App\Form\CommentType;
use App\Entity\Comment;
use App\Repository\CommentRepository;

class NewsController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //page de description du projet
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/news", name="actus")
     */
    public function news(ArticleRepository $articleReposiory): Response
    {
        /*$user = $this->getUser();
        

        $newComment = new Comment();
        $formComment= $this->createForm(CommentType::class, $newComment);

         $formComment->handleRequest($request);

        //si envoie du formulaire et qu'il est valide
        if($formComment->isSubmitted() && $formComment->isValid() ){
            $article = $articleRepository->find($id);
            $em= $this->getDoctrine()->getManager();
            $newComment->setAuthor($user);
            $newComment->setArticle($article);
            //ajout de le commentaire en bdd
            $em->persist($newComment);
            $em->flush();
            //redirection vers le fil d'actualité
            return $this->redirect($this->generateUrl('actus'));
        }*/

        return $this->render('news/index.html.twig', 
        array("articles" => $articleReposiory->findAll()));
    }

    /**
     * @Route("/new/{id}", name="new.view")
     */
    public function newView(ArticleRepository $articleRepository, CommentRepository $commentRepository, $id, Request $request) : Response
    {
        $user = $this->getUser();
        $article = $articleRepository->find($id);
        $comments = $commentRepository->findBy(["article" => $id]);
        $nbComment= $commentRepository->nbCommentaireArticle($article);

        $newComment = new Comment();
        $formComment= $this->createForm(CommentType::class, $newComment);

         $formComment->handleRequest($request);

        //si envoie du formulaire et qu'il est valide
        if($formComment->isSubmitted() && $formComment->isValid() ){
            $em= $this->getDoctrine()->getManager();
            $newComment->setAuthor($user);
            $newComment->setArticle($articleRepository->find($request->get('idArticle')));
            //ajout de le commentaire en bdd
            $em->persist($newComment);
            $em->flush();
            //redirection vers le fil d'actualité
            return $this->redirect($this->generateUrl('new.view', ['id' => $id]));
        }

        if($user===NULL){
            //lancer une exception comme quoi l'utilisateur doit etre co 
        }
        return $this->render('news/new.html.twig', 
            array("article" => $article,
            "commentaires" => $comments,
            "nbComment" => $nbComment[0]["nb"],
            "ajoutComment" => $formComment->createView()));
    }

    /**
     * @Route("/add/new", name="new.add")
     */
    public function newAdd(Request $request)
    {
        $user = $this->getUser();
        //var_dump($user);
        if($user===NULL){
            //lancer une exception comme quoi l'utilisateur doit etre co 
        }
        $new = new Article();
        $form = $this->createForm(ArticleType::class, $new);

        $form->handleRequest($request);

        //si envoie du formulaire et qu'il est valide
        if($form->isSubmitted() && $form->isValid() ){
            $em= $this->getDoctrine()->getManager();
            $new->setAuthor($user);
            //ajout de l'article en bdd
            $em->persist($new);
            $em->flush();
            //redirection vers le fil d'actualité
            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('news/add.html.twig', array('form' => $form->createView()));
    }
}