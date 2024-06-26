<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\ORM\OptimisticLockException;
// use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Level|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Level|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Level> findAll()
 * @method array<Level> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends ServiceEntityRepository<Level>
 */
final class LevelRepository extends ServiceEntityRepository
{
    /**
     * Fonction qui est le constructeur de la classe LevelRepository.
     *
     * Cette fonction permet de contruire l'objet LevelRepository
     * en reprenant les fonctions de sa classe parent
     * qui est ServiceEntityRepository
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Level::class);
    }

    // /**
    //  * @throws ORMException
    //  * @throws OptimisticLockException
    //  */
    public function add(Level $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity); // $this->_em->persist($entity);
        if ($flush) {
            $em->flush(); // $this->_em->flush();
        }
    }

    // /**
    //  * @throws ORMException
    //  * @throws OptimisticLockException
    //  */
    public function remove(Level $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity); // $this->_em->remove($entity);
        if ($flush) {
            $em->flush(); // $this->_em->flush();
        }
    }

    // /**
    // * @return Level[] Returns an array of Level objects
    // */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Level
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
