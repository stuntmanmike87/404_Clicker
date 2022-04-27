<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use App\Repository\LevelRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $levelRepository;

    /**
     * Fonction qui est le constructeur de la classe UserRepository
     * 
     * Cette fonction permet de contruire l'objet UserRepository en reprenant les fonctions de sa classe parent qui est ServiceEntityRepository
     *
     * @param ManagerRegistry $registry
     * @param LevelRepository $levelRepository
     */
    public function __construct(ManagerRegistry$registry, LevelRepository $levelRepository)
    {
        parent::__construct($registry, User::class);
        $this->levelRepository = $levelRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
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
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Insert points into points's field
     *
     * @param [type] $points
     * @return void
     */
    public function insertPoints($points , $user) {
        
        $user->setPoints($points);

        $this->_em->persist($user);
        $this->_em->flush();
    }
    /* Get user by DESC number of points
     *
     * @param integer $limit
     * @return User[] Returns an array of User objects
     */
    public function findByPointsDesc($limit=3)
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.points', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    /**
     * Fonction qui permet de définir pour un joueur, le score à atteindre pour passer au niveau suivant
     *
     * @param [type] $points
     * @param [type] $user
     * @return void
     */
    public function setLevelByNumberOfPoints($points, $user)
    {
        if ($points < 20) {
            $level = $this->levelRepository->find(1);
            $user->setLevel($level);
        }
        if ($points >= 20) {
            $level = $this->levelRepository->find(2);
            $user->setLevel($level);
        }
        if ($points >= 50) {
            $level = $this->levelRepository->find(3);
            $user->setLevel($level);
        }
        if ($points >= 100) {
            $level = $this->levelRepository->find(4);
            $user->setLevel($level);
        }
        if ($points >= 200) {
            $level = $this->levelRepository->find(5);
            $user->setLevel($level);
        }
        if ($points >= 500) {
            $level = $this->levelRepository->find(6);
            $user->setLevel($level);
        }

        $this->_em->persist($user);
        $this->_em->flush();
    }
    /**
     * Fonction qui gère le changement de niveau selon le score du joueur
     *
     * @param [type] $user
     * @return void
     */
    public function ChangeLevel($user)
    {
        //$changeLevelScore = 0;

        if (isset($user)) {
            if ($user->getPoints() < 20) {
                return 20;
            }
            if ($user->getPoints() >= 20 && $user->getPoints() < 50) {
                return 50;
            }
            if ($user->getPoints() >= 50 && $user->getPoints() < 100) {
                return 100;
            }
            if ($user->getPoints() >= 100 && $user->getPoints() < 200) {
                return 200;
            }
            if ($user->getPoints() >= 200 && $user->getPoints() < 500) {
                return 500;
            }

            //return $changeLevelScore;
        }

    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}