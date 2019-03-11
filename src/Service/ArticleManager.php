<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;

use App\BlogInterface;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleManager implements BlogInterface
{
    public function __construct(ObjectManager $em)
    {
        $this->em=$em;
    }

    public function add($data)
    {
        $article = $data['article'];
        $article->setAuthor($data['user']);
        //add article to DataBase
        if ($article->getPicture()==null) {
            $article->setPicture(Article::IMAGE_ARTICLE_DEFAULT);
        }
        $this->em->persist($article);
        $this->em->flush();
    }

    public function delete($data)
    {
        $article = $this->em->getRepository('App:Article')->find($data['id']);
        $comments=$this->em->getRepository('App:Comment')->findBy(["article" => $data['id']]);
        //code replaced by attribut cascade on entity Article
        /*foreach ($comments as $comment) {
            $this->em->remove($comment);
        }*/
        //suppression de l'article
        $this->em->remove($article);
        $this->em->flush();
    }

    public function update($data)
    {
        $article = $data['article'];
        //$article->setAuthor($data['user']);
        if ($article->getPicture()==null) {
            $article->setPicture(Article::IMAGE_ARTICLE_DEFAULT);
        }
        //ajout de l'article en bdd
        $this->em->persist($article);
        $this->em->flush();
    }
}
