<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
// use App\Repository\LevelRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\ORM\OptimisticLockException;
// use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
// use App\Enum;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null   find($id, $lockMode = null, $lockVersion = null)
 * @method User|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<User> findAll()
 * @method array<User> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends ServiceEntityRepository<User>
 */
final class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Fonction qui est le constructeur de la classe UserRepository.
     *
     * Cette fonction permet de contruire l'objet UserRepository en reprenant
     * les fonctions de sa classe parent qui est ServiceEntityRepository
     */
    public function __construct(ManagerRegistry $registry, private readonly LevelRepository $levelRepository)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * function add(User $entity, bool $flush = true)
    //  *
    //  * @throws ORMException
    //  * @throws OptimisticLockException
    //  */
    public function add(User $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity); // $this->_em->persist($entity);
        if ($flush) {
            $em->flush(); // $this->_em->flush();
        }
    }

    // /**
    //  * function remove(User $entity, bool $flush = true)
    //  *
    //  * @throws ORMException
    //  * @throws OptimisticLockException
    //  */
    public function remove(User $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity); // $this->_em->remove($entity);
        if ($flush) {
            $em->flush(); // $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    #[\Override]
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class/* \get_class($user) */));
        }

        /* @var User $user */
        $user->setPassword($newHashedPassword);

        $em = $this->getEntityManager();
        $em->persist($user); // $this->_em->persist($user);
        $em->flush(); // $this->_em->flush();
    }

    /**
     * Insert points into field: points.
     */
    public function insertPoints(float $points, User $user): void
    {
        $user->setPoints($points);

        $em = $this->getEntityManager();
        $em->persist($user); // $this->_em->persist($user);
        $em->flush(); // $this->_em->flush();
    }

    /**
     * Get user by DESC number of points
     * return User[] : Returns an array of User objects.
     */ // array<User>
    public function findByPointsDesc(int $limit = 3): mixed
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.points', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fonction qui permet de définir pour un joueur, le score à atteindre pour passer au niveau suivant.
     */
    public function checkLevelByMaxPoints(float $points, User $user): void
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

        $em = $this->getEntityManager();
        $em->persist($user); // $this->_em->persist($user);
        $em->flush(); // $this->_em->flush();
    }

    /**
     * Fonction qui gère le changement de niveau selon le score du joueur.
     */
    public function changeLevel(User $user): float
    {
        if ($user->getPoints() < 20) {
            return 20;
        }// LevelsEnum:ONE

        if ($user->getPoints() >= 20 && $user->getPoints() < 50) {
            return 50;
        }// LevelsEnum:TWO

        if ($user->getPoints() >= 50 && $user->getPoints() < 100) {
            return 100;
        }// LevelsEnum:THREE

        if ($user->getPoints() >= 100 && $user->getPoints() < 200) {
            return 200;
        }// LevelsEnum:FOUR

        if ($user->getPoints() >= 200 && $user->getPoints() < 500) {
            return 500;
        }// LevelsEnum:FIVE

        return 0; // $changeLevelScore;
    }

    // /**
    // * Fonction that is a custom user provider
    // * Remove the property key 'email' from the user provider in security.yaml
    // */
    /* public function loadUserByIdentifier(string $usernameOrEmail): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager
            ->createQuery(
                'SELECT u FROM App\Entity\User u
                WHERE u.username = :query OR u.email = :query'
            )
            ->setParameter('query', $usernameOrEmail)
            ->getOneOrNullResult();
    } */

    // /**
    // * @return User[] Returns an array of User objects
    // */
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
