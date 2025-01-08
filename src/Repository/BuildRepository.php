<?php

namespace App\Repository;

use App\Entity\Build;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Build>
 */
class BuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Build::class);
    }

    public function save(Build $build, ?bool $isSaved = true): void
    {
        $this->getEntityManager()->persist($build);
        
        if($isSaved){
            $this->getEntityManager()->flush();
        }
    }

    public function findBuildsByUser(User $utilisateur): array
    {
        return $this->createQueryBuilder('b') // 'b' est l'alias de Build
            ->leftJoin('b.item', 'i') // Jointure avec Items depuis Build
            ->addSelect('i') // Sélection des Items pour éviter le lazy loading
            ->where('b.utilisateur = :utilisateur') // Filtrer par utilisateur
            ->setParameter('utilisateur', $utilisateur) // Associer l'utilisateur à la requête
            ->getQuery()
            ->getResult();
    }
    
//    /**
//     * @return Build[] Returns an array of Build objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Build
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
