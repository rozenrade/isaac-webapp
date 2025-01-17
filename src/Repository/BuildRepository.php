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
            ->leftJoin('b.item', 'i') // Jointure avec Items
            ->addSelect('i') // Sélection des Items pour éviter le lazy loading
            ->leftJoin('b.boss', 'boss') // Jointure avec Boss
            ->addSelect('boss') // Sélectionner les Boss pour éviter le lazy loading
            ->leftJoin('b.character', 'character') // Jointure avec Character
            ->addSelect('character') // Sélectionner les Characters pour éviter le lazy loading
            ->where('b.utilisateur = :utilisateur') // Filtrer par utilisateur
            ->setParameter('utilisateur', $utilisateur) // Associer l'utilisateur à la requête
            ->getQuery()
            ->getResult();
    }
}
