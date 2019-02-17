<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\BlogInterface;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\CommentEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;
use App\EventListener\CommentCountListener;
use App\CommentCount;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class CommentManager implements BlogInterface
{

    private $em;
    private $eventDispatcher;

    public function __construct(ObjectManager $em, EventDispatcherInterface $eventDispatcher)
    {

        $this->em= $em;
        $this->dispatcher = $eventDispatcher;
    }

    public function add($data){
        $comment = $data['comment'];
        $comment->setAuthor($data['user']);
        $comment->setArticle($this->em->getRepository('App:Article')->find($data['idArticle']));
        //ajout de le commentaire en bdd
        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    public function delete($data){
        //$comment= $this->em->getRepository('App:Comment')->find($data['id']);
        $this->em->remove($data['comment']);
        $this->em->flush();
    }

    public function update($data){
        $comment = $data['comment'];
        //$comment->setAuthor($data['user']);
        //ajout de l'article en bdd
        $this->em->persist($comment);
        $this->em->flush();
    }
}