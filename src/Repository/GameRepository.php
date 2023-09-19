<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    // - Récupérer un jeu par son id avec les notes et l'âge
    public function getgameWithInfo($id)
    {
        // - Appeler l'entity manager
        $entityManager = $this->getEntityManager();
        // - Créer la query
        $query = $entityManager->createQuery(
            'SELECT
                g.id,
                g.title,
                g.description,
                g.imagePath,
                g.price,
                g.releaseDate,
                n.userNote,
                n.mediaNote,  
                a.label,
                a.imagePath as imgPegi,
                a.id as ageId
            FROM App\Entity\Game g
            JOIN g.note n
            JOIN g.age a
            WHERE g.id = :id'
        )->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    // - Récupérer la liste des consoles par jeu
    public function getConsolesByGame($id)
    {
        // - Appeler l'entity manager
        $entityManager = $this->getEntityManager();
        // $query = $entityManager->createQuery(
        //     'SELECT c.label, c.id
        //     FROM App\Entity\Game g
        //     JOIN g.consoles c
        //     WHERE g.id = :id'
        // )->setParameter('id', $id);

        // return $query->getResult();

        // - DQL
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select([
            'c.label',
            'c.id'
        ])
            ->from(Game::class, 'g')
            ->join('g.consoles', 'c')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    // - Méthode qui récupère toutes les consoles avec le nbre de jeux associés
    public function getCountGameByConsole()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT c.label, c.id, COUNT(g.id) as total
                FROM App\Entity\Game g
                JOIN g.consoles c
                GROUP BY c.id'
        );
        return $query->getResult();
    }

    // - Récupérer la liste de jeux filtrés par console
    public function getGamesByConsole(int $id)
    {
        // - Appeler l'entity manager
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT c.label, g.id, g.title, g.imagePath, g.price
            FROM App\Entity\Game g
            JOIN g.consoles c
            WHERE c.id = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    // - Supprimer un jeu
    public function delete(Game $entity, bool $flush = false)
    {
        $entityManager = $this->getEntityManager();
        $entityManager()->remove($entity);

        if ($flush) {
            $entityManager->flush();
        }
    }

    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Game
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // - Méthode avec requête DQL
    // $qb = $entityManager->createQueryBuilder();

    // // - Créer la query
    //     $query = $qb->select([
    //         'g.id',
    //         'g.title',
    //         'g.description',
    //         'g.imagePath',
    //         'g.price',
    //         'g.releaseDate',
    //         'n.userNote',
    //         'n.mediaNote',
    //         'a.label',
    //         'a.imagePath as imgPegi',
    //         'a.id as ageId'
    //         ])
    //         ->from('App\Entity\Game', 'g')
    //         ->join('g.note', 'n')
    //         ->join('g.age', 'a')
    //         ->where('g.id = :id')
    //         ->setParameter('id', $id)
    //         ->getQuery();
    //         return $query->getResult();

}
