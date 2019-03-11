<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ArticleManager;
use App\Form\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use App\Service\CommentManager;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Repository\CommentRepository;

class NewsController extends AbstractController
{

    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //page without sign in
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/ajax", name="myapp_add_comment")
     */
    public function ajaxAddComment(Request $request, CommentRepository $commentRepository, CommentManager $commentManager)
    {
        
        $user = $this->checkAccessAutorization();
        if ($request->isXmlHttpRequest()) {
            $idArticle =$request->request->get('id_article');
            $newComment = new Comment();
            $content = $request->request->get('content');
            $newComment->setContent($content);
            $commentManager->add(array("user" => $user, "idArticle" => $idArticle, "comment" => $newComment));
        }
        $comments = $commentRepository->findBy(["article" => 2]);
        return $this->render('comment/area_comment.html.twig', array( "commentaires" => $comments));
    }

    /**
     * @Route("/news", name="actus")
     */
    public function news(ArticleRepository $articleReposiory): Response
    {
        $user = $this->checkAccessAutorization();
        return $this->render(
            'news/index.html.twig',
            array("articles" => $articleReposiory->getAllArticleWithNbcomment())
        );
    }

    /**
     * @Route("/new/{id}", name="new.view")
     */
    public function newView($id, Request $request, CommentManager $commentManager, CommentRepository $commentRepository)
    {
        //With login/pwd
        $user = $this->checkAccessAutorization();
        //throw execption if article does not exist
        $article = $this->getArticle($id);
        //get comment linked to article
        $comments = $commentRepository->findBy(["article" => $id]);
        //number of comment published on this article
        $nbComment= $commentRepository->nbCommentaireArticle($article);

        //create form : add Article
        $newComment = new Comment();
        $formComment= $this->createForm(CommentType::class, $newComment);

        $formComment->handleRequest($request);
        //if you add comment
        /*if ($formComment->isSubmitted() && $formComment->isValid()) {
            $commentManager->add(array("user" => $user, "idArticle" => $id, "comment" => $newComment));

            //redirect page to refresh information
            //return $this->redirect($this->generateUrl('new.view', ['id' => $id]));
            //event kernel.view
            return array(
            'template' => 'news/new.html.twig',
            'data' => array(
                'article' => $article,
                "commentaires" => $comments,
                "nbComment" => $nbComment[0]["nb"],
                "ajoutComment" => $formComment->createView()
                )
            );
        }*/
        
        return $this->render(
            'news/new.html.twig',
            array(
                "article" => $article,
                "commentaires" => $comments,
                "nbComment" => $nbComment[0]["nb"],
                "ajoutComment" => $formComment->createView())
        );
    }

    /**
     * @Route("delete/new/{id}", name="new.delete")
     */
    public function newDelete($id, ArticleManager $articleManager, CommentManager $commentManager, Request $request) : Response
    {
        $user = $this->checkAccessAutorization();

        //throw exception if article does not exist
        $article = $this->getArticle($id);
        //check autorization to delete (if you are owner or admin)
        $this->isAuthor($user, $article);
        //send answer
        if ($request->request->get('del')!==null) {
            if ($request->request->get('del')=='Oui') {//par oui
                try {// on supprime d'abord les commentaires liés à l'article puis l'article lui même
                    $articleManager->delete(array('id' => $id));
                } catch (\Doctrine\DBAL\DBALException $e) {
                }
            }
            //back to the news
            return $this->redirect($this->generateUrl('actus'));
        }
        //page confirmation request to delete
        return $this->render('news/delete.html.twig', array('article' => $article));
    }

    /**
     * @Route("delete/comment/{id}", name="comment.delete")
     */
    public function commentDelete(CommentManager $commentManager, $id, Request $request) : Response
    {
        $user = $this->checkAccessAutorization();
        
        //check if comment exist
        $comment = $this->getComment($id, $user);
        //check autorization to delete (if you are owner or admin)
        $this->isAuthor($user, $comment);
        //send answer
        if ($request->request->get('del')!==null) {
            if ($request->request->get('del')=='Oui') {//par oui
                try {
                    //$commentManager->delete(array('id' => $id));
                    $commentManager->delete(['comment' => $comment]);
                } catch (\Doctrine\DBAL\DBALException $e) {
                }
            }
            //back to the news
            return $this->redirect($this->generateUrl('new.view', ['id' => $comment->getArticle()->getId()]));
        }
        //page confirmation request to delete
        return $this->render('comment/delete.html.twig', array('commentaire' => $comment));
    }

    /**
     * @Route("edit/new/{id}", name="new.edit")
     */
    public function newEdit(CommentRepository $commentRepository, ArticleManager $articleManager, $id, Request $request) : Response
    {
        $user = $this->checkAccessAutorization();
        $article = $this->getArticle($id);
        $this->isAuthor($user, $article);
        //create form pre-filled with the value of the selected article
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        //if form is send and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $articleManager->update(['article' => $article, 'user' => $user]);
            //back to the news
            return $this->redirect($this->generateUrl('actus'));
        }
        return $this->render('news/add.html.twig', array('form' => $form->createView(), 'id' => $id));
    }

    /**
     * @Route("edit/comment/{id}", name="comment.edit")
     */
    public function commentEdit(CommentRepository $commentRepository, CommentManager $commentManager, $id, Request $request) : Response
    {
        $user = $this->checkAccessAutorization();
        $comment = $this->getComment($id, $user);
        $this->isAuthor($user, $comment);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentManager->update(['comment' => $comment, 'user' => $user]);
            //back to article linked to article
            return $this->redirect($this->generateUrl('new.view', ['id' => $comment->getArticle()->getId()]));
        }
        return $this->render('comment/edit.html.twig', array('ajoutComment' => $form->createView(), 'id' => $id));
    }

    /**
     * @Route("/add/new", name="new.add")
     */
    public function newAdd(ArticleManager $articleManager, Request $request)
    {
        $user = $this->checkAccessAutorization();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //add article to DataBase
            $articleManager->add(array('user' => $user, 'article' => $article));
            return $this->redirect($this->generateUrl('actus'));
        }

        return $this->render('news/add.html.twig', array('form' => $form->createView()));
    }

    public function checkAccessAutorization()
    {
        try {
            return $user = $this->getAuthentification();
        } catch (HttpException $e) {
            return $this->redirect($this->generateUrl('app_login'))->send();
        }
    }

    public function getAuthentification()
    {
        $user = $this->getUser();
        if ($user===null) {
            //throw expection the user must be connected
            throw new HttpException(403, "Vous devez vous connecter afin de pouvoir accèder au contenu");
        }
        return $user;
    }

    public function getArticle($id)
    {
        $article =$this->em->getRepository('App:Article')->find($id);
        //if article is not found throw exception
        if ($article==null) {
            throw new \Exception("Impossible de trouver l'article n'existe pas");
        }
        return $article;
    }

    public function getComment($id, $user)
    {
        //if it is a temporary comment get the last comment of user
        if ($id==0) {
            $comment=$this->em->getRepository('App:Comment')->findLastCommentUser($user)[0];
        } else {
            $comment=$this->em->getRepository('App:Comment')->find($id);
        }
        //if comment is not found throw exception
        if ($comment==null) {
            throw new \Exception("Impossible de trouver le commentaire n'existe pas");
        }
        return $comment;
    }

    public function isAuthor($user, $Entity)
    {
        //if user is not ADMIN or Author
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($user!==$Entity->getAuthor()) {
                //throw exception to prevent user to update or delete content
                throw new HttpException(403, "Vous n'est pas autorisé a apporter des modification");
            }
        }
    }
}
