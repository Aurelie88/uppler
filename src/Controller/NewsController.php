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
     * @Route("delete/new/{id}", name="new.delete")
     */
    public function newDelete(ArticleRepository $articleRepository, CommentRepository $commentRepository, $id, Request $request) : Response
    {
        //on recupere le metier que l'on souhaite supprimé
        $em= $this->getDoctrine()->getManager()->getRepository('App:Article');
        $article=$em->find($id);

        //si on nentrouve pas métier, on lance une exception
        if ($article==null){
            throw new \Exception("Impossible de trouver l'article n'existe pas");
        }

        //si on repond a la confirmation
        if(isset($_POST['del'])){
            if($_POST['del']=='Oui'){//par oui
                try{// on commence par supprimer les commentaire lié a l'article
                    $em= $this->getDoctrine()->getManager();
                    $comments=$em->getRepository('App:Comment')->findBy(["article" => $id]);
                    //supprime un commentaire à la  fois
                    foreach ($comments as $comment) {
                        $em->remove($comment);
                    }
                    $em->remove($article);
                    $em->flush();
                } catch (\Doctrine\DBAL\DBALException $e){
                }
                //retour au fil d'actualité
                return $this->redirect($this->generateUrl('actus'));
            }
            else{// si on ne souhaite pas supprimer on retourne directement au fil d'actualité
                return $this->redirect($this->generateUrl('actus'));
            }
        }
        //on retourne la vue avec les données du métier
        return $this->render('news/delete.html.twig', array('article' => $article));
    }

    /**
     * @Route("edit/new/{id}", name="new.edit")
     */
    public function newEdit(ArticleRepository $articleRepository, CommentRepository $commentRepository, $id, Request $request) : Response
    {
      $user = $this->getUser();
        //var_dump($user);
        if($user===NULL){
            //lancer une exception comme quoi l'utilisateur doit etre co 
        }
        $new = $articleRepository->find($id);
        if($user!=$new->getAuthor()){
            //lancer une exception sur l'autorisation 
        }
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
            return $this->redirect($this->generateUrl('actus'));
        }

        return $this->render('news/add.html.twig', array('form' => $form->createView()));
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