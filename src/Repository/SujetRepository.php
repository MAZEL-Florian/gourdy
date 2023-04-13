<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    public function findByDateCreation(\DateTime $date){
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->where('s.dateCreation = :date');
        $qb->setParameter('date', $date->format('Y-m-d'));
        return $qb->getQuery()->getResult();
    }

    public function findByTitre($titre){
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->where('s.titre = :titre');
        $qb->setParameter('titre', $titre);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findOneByIdJoinedToCategory($nom)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s.titre, s.contenu, u.roles, s.dateCreation, s.id, c.image, MAX(co.dateCommentaire) as d, u.pseudo, COUNT(co.sujet) as n
            FROM App\Entity\Sujet s
            INNER JOIN s.categories c
            INNER JOIN s.User u
            LEFT JOIN s.commentaires co
            WHERE c.nomCategorie = :nom
            GROUP BY s.id
            ORDER BY s.dateCreation DESC
            '
            
        )->setParameter('nom', $nom);

        return $query->getArrayResult();
    }

    public function findOneById($id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s.titre, s.contenu, s.dateCreation, s.id
            FROM App\Entity\Sujet s
            WHERE s.id = :id'
        )->setParameter('id', $id);

        return $query->getArrayResult();
    }

    public function findByUserId($id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s.titre, s.contenu, u.roles, s.dateCreation, s.id, c.image, MAX(co.dateCommentaire) as d, u.pseudo, COUNT(co.sujet) as n
            FROM App\Entity\Sujet s
            INNER JOIN s.categories c
            INNER JOIN s.User u
            LEFT JOIN s.commentaires co
            WHERE u.id = :id
            GROUP BY s.id
            ORDER BY s.dateCreation DESC'
        )->setParameter('id', $id);

        return $query->getArrayResult();
    }


    public function findCommentaires($id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(s.commentaires) as n
            FROM App\Entity\Sujet s
            WHERE s.id = :id'
        )->setParameter('id', $id);

        return $query->getArrayResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sujet $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sujet $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Sujet[] Returns an array of Sujet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sujet
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
