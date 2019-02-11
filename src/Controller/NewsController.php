<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Form\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use App\Form\CommentType;
use App\Entity\Comment;
use App\Repository\CommentRepository;

class NewsController extends AbstractController
{

    private $em;

    public function __construct(ObjectManager $em){
        $this->em = $em;
    }


    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //page de description du projet visible sans connexion
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/news", name="actus")
     */
    public function news(ArticleRepository $articleReposiory): Response
    {
        $user = $this->verificationAuthentification();
        
        return $this->render('news/index.html.twig', 
        array("articles" => $articleReposiory->findAll()));
    }

    /**
     * @Route("/new/{id}", name="new.view")
     */
    public function newView($id, Request $request, CommentRepository $commentRepository) : Response
    {   
        //acces par mot de passe     
        $user = $this->verificationAuthentification();
        
        //lance une exception si l'article n'existe pas
        $article = $this->recupererArticle($id);
        //recupere les commentaires lies a l'article
        $comments = $commentRepository->findBy(["article" => $id]);
        //nb de commentaire ecrit sur l'article
        $nbComment= $commentRepository->nbCommentaireArticle($article);

        //création du form pour pouvoir ajouter des commentaire sur l'article
        $newComment = new Comment($this->em);
        $formComment= $this->createForm(CommentType::class, $newComment);

        $formComment->handleRequest($request);

        //si un commentaire est ajouter
        if($formComment->isSubmitted() && $formComment->isValid() ){
            $newComment->ajouter(array("user" => $user, "idArticle" => $id));
            //redirection vers la page de l'article article 
            return $this->redirect($this->generateUrl('new.view', ['id' => $id]));
        }
        
        return $this->render('news/new.html.twig', 
            array(
                "article" => $article,
                "commentaires" => $comments,
                "nbComment" => $nbComment[0]["nb"],
                "ajoutComment" => $formComment->createView()));
    }

    /**
     * @Route("delete/new/{id}", name="new.delete")
     */
    public function newDelete($id, Request $request) : Response
    {
        $user = $this->verificationAuthentification();
        //lance une exception si l'article à supprimer n'existe pas
        $article = $this->recupererArticle($id);
        //verifie qu'on a l'autorisation de le supprimer (uniquement si on est l'auteur)
        $this->isAuthor($user, $article);
        //si on repond a la demande de suppression
        if(isset($_POST['del'])){
            if($_POST['del']=='Oui'){//par oui
                try{// on supprime d'abord les commentaires liés à l'article puis l'article lui même
                    $deleteArticle = new Article($this->em);
                    $deleteArticle->supprimer(array('id' => $id));
                } catch (\Doctrine\DBAL\DBALException $e){
                }
                
            }
            //retour au fil d'actualité
            return $this->redirect($this->generateUrl('actus'));
        }
        //page demande de confirmation pour la suppression 
        return $this->render('news/delete.html.twig', array('article' => $article));
    }

    /**
     * @Route("delete/comment/{id}", name="comment.delete")
     */
    public function commentDelete(ArticleRepository $articleRepository, CommentRepository $commentRepository, $id, Request $request) : Response
    {
        $user = $this->verificationAuthentification();
        //on verifie si le commentaire existe
        $comment = $this->recupererComment($id);
        //verifie qu'on a l'autorisation de le supprimer (uniquement si on est l'auteur)
        $this->isAuthor($user, $comment);
        //si on repond a la confirmation de suppresion
        if(isset($_POST['del'])){
            if($_POST['del']=='Oui'){//par oui
                try{
                    $deleteComment = new Comment($this->em);
                    $deleteComment->supprimer(array('id' => $id));
                } catch (\Doctrine\DBAL\DBALException $e){
                }
            }
            // redirection fil d'actualité
            return $this->redirect($this->generateUrl('new.view',['id' => $comment->getArticle()->getId()]));
        }
        //retourne une page de confirmation pour supprimer le commentaire
        return $this->render('comment/delete.html.twig', array('commentaire' => $comment));
    }

    /**
     * @Route("edit/new/{id}", name="new.edit")
     */
    public function newEdit(CommentRepository $commentRepository, $id, Request $request) : Response
    {
        $user = $this->verificationAuthentification();
        //verifie si l'article existe
        $article = $this->recupererArticle($id);
        //lance une erreur si on est pas propriétaire de l'article
        $this->isAuthor();
        //on creer le form rempli avec les valeur de l'article selectionné
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        //si envoie du formulaire et qu'il est valide
        if($form->isSubmitted() && $form->isValid() ){
            $em= $this->getDoctrine()->getManager();
            $article->setAuthor($user);
            //ajout de l'article en bdd
            $em->persist($article);
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
        $user = $this->verificationAuthentification();
        //création du formulaire d'ajout d'article
        $article = new Article($this->em);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        //si envoie du formulaire et qu'il est valide
        if($form->isSubmitted() && $form->isValid() ){
            //ajout de l'article en BDD à l'aide de l'interface
            $article->ajouter(array('user' => $user));
            //redirection vers le fil d'actualité
            return $this->redirect($this->generateUrl('actus'));            
        }

        return $this->render('news/add.html.twig', array('form' => $form->createView()));
    }

    public function verificationAuthentification(){
        $user = $this->getUser();
        if($user===NULL){
            return $this->redirect($this->generateUrl('app_login'))->send(); 
            //lancer une exception comme quoi l'utilisateur doit etre co           
            //throw new HttpException(403, "veuillez vous connecter");
        }        
        return $user;
    } 

    public function recupererArticle($id){
        $article =$this->em->getRepository('App:Article')->find($id);
        //si on nentrouve pas métier, on lance une exception
        if ($article==null){
            throw new \Exception("Impossible de trouver l'article n'existe pas");
        }
        return $article;
    }

    public function recupererComment($id){
        $comment=$this->em->getRepository('App:Comment')->find($id);
        //si on nentrouve pas métier, on lance une exception
        if ($comment==null){
            throw new \Exception("Impossible de trouver le commentaire n'existe pas");
        }
        return $comment;
    }

    public function isAuthor($user, $Entity){
        if($user!==$Entity->getAuthor()){
            throw new HttpException(403, "Vous n'est pas autorisé a apporter des modification");
        }
    }
}