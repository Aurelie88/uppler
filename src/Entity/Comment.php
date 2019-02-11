<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use App\Entity\Article;
use App\ManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements ManagerInterface
{
    private $em;
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getArticle(): ?article
    {
        return $this->article;
    }

    public function setArticle(?article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function ajouter($data){
        if($data['user']===NULL){             
            throw new Exception("Veuillez vous connecter", 1);            
        }
        $this->setAuthor($data['user']);
        $this->setArticle($this->em->getRepository('App:Article')->find($data['idArticle']));
        //ajout de le commentaire en bdd
        $this->em->persist($this);
        $this->em->flush();
    }

    public function supprimer($data){
        $comment= $this->em->getRepository('App:Comment')->find($data['id']);
        $this->em->remove($comment);
        $this->em->flush();
    }

    public function modifier(){

    }
}
