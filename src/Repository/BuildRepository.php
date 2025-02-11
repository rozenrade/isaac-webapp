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
        return $this->createQueryBuilder('b') 
            ->leftJoin('b.item', 'i')
            ->addSelect('i') 
            ->leftJoin('b.boss', 'boss') 
            ->addSelect('boss') 
            ->leftJoin('b.character', 'character') 
            ->addSelect('character')
            ->where('b.utilisateur = :utilisateur') 
            ->setParameter('utilisateur', $utilisateur) 
            ->getQuery()
            ->getResult();
    }
}

