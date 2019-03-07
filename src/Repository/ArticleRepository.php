<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //SELECT article.*, count(*) FROM `comment`, article where comment.article_id = article.id group by article_id

    public function getAllArticleWithNbcomment()
    {
        
        // on compte le nombre de commentaire
        $query = $this->_em->createQuery('SELECT a as article, count(c) as nbComment 
            FROM App:Comment c, App:Article a 
            WHERE c.article = a.id
            GROUP BY c.article');
        $requete1 = $query->getResult();
        //les article n'ont pas encore de commentaire
        $query = $this->_em->createQuery('SELECT a as article, 0 as nbComment
            FROM App:Article a
            WHERE a.id NOT IN (
            SELECT IDENTITY(c.article) 
            FROM App:Comment c, App:Article art
            WHERE c.article=art.id)');
        $requete2= $query->getResult();
        $results= array_merge($requete1, $requete2);
        foreach ($results as $key => $row) {
            $date[$key]= $row['article']->getId();
        }
        array_multisort($date, SORT_ASC, $results);
        return $results;
    }
    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
