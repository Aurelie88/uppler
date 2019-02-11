<?php

namespace App\Entity;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;


use App\ManagerInterface;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Repository\ArticleRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article implements ManagerInterface
{
    protected $em;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    public function __construct(EntityManager $em)
    {
        $this->comments = new ArrayCollection();
        $this->em=$em;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function __toString() {
        return $this->titre;
    }

    public function ajouter($data) {
        //lancer une exception si l'utiliser n'est pas connecter
        if($data['user']===NULL){
            throw new Exception("Veuillez vous connecter", 1);            
        }
            $this->setAuthor($data['user']);
            //ajout de l'article en bdd
            $this->em->persist($this);
            $this->em->flush();
    }

    public function supprimer($data){
        $article = $this->em->getRepository('App:Article')->find($data['id']);
        $comments=$this->em->getRepository('App:Comment')->findBy(["article" => $data['id']]);
        //supprime un commentaire à la fois
        foreach ($comments as $comment) {
            $this->em->remove($comment);
        }
        //suppression de l'article
        $this->em->remove($article);
        $this->em->flush();
    }

    public function modifier(){

    }
}
