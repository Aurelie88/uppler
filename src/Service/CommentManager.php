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

use Symfony\Component\EventDispatcher\EventDispatcher;


class CommentManager implements BlogInterface
{

    protected $em;

    public function __construct(ObjectManager $em)
    {

        $this->em= $em;

        //$this->dispatcher = new EventDispatcher();
    }

    public function add($data){
        if($data['user']===NULL){             
            throw new Exception("Veuillez vous connecter", 1);            
        }
        $comment = $data['comment'];
        $comment->setAuthor($data['user']);
        $comment->setArticle($this->em->getRepository('App:Article')->find($data['idArticle']));
        //ajout de le commentaire en bdd
        $this->em->persist($comment);
        $this->em->flush();

    }

    public function delete($data){
        $comment= $this->em->getRepository('App:Comment')->find($data['id']);
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function update($data){

    }
}