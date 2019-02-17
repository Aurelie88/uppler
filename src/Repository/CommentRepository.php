<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    //Retourne le nombre de commentaire sur un article donné
    public function nbCommentaireArticle($article){
        $query = $this->_em->createQuery('SELECT count(c) as nb FROM App:Comment c WHERE c.article = :article')
        ->setParameter('article', $article);
        return $query->getResult();
    }

    //récupère le dernier commentaire de l'utilisateur connecté
    public function findLastCommentUser($user){
        $query= $this->_em->createQuery('SELECT c 
            FROM App:Comment c 
            WHERE c.author= :user 
            AND c.createAt =
                (SELECT MAX(com.createAt)
                FROM App:Comment com
                WHERE com.author = :user)')
        ->setParameter('user', $user);
        return $query->getResult();
    }
}
