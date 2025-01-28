<?php

namespace App\Repository;

use App\Entity\Synergy;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Synergy>
 */
class SynergyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Synergy::class);
    }

    public function save(Synergy $synergie)
    {
        $this->getEntityManager()->persist($synergie);

        $this->getEntityManager()->flush();
    }

    // Dans votre repository ou contrôleur
    public function findAllWithItems(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.item', 'i')  // Joindre les items associés
            ->addSelect('i')           // Sélectionner aussi les items
            ->getQuery()
            ->getResult();
    }

    public function findSynergiesByUser(User $utilisateur): array
    {
        return $this->createQueryBuilder('synergy')
            ->leftJoin('synergy.utilisateurs', 'u') // Joindre les utilisateurs associés
            ->addSelect('u')                       // Sélectionner aussi les utilisateurs
            ->where(':utilisateur MEMBER OF synergy.utilisateurs') // Filtrer par utilisateur
            ->setParameter('utilisateur', $utilisateur) // Associer l'utilisateur à la requête
            ->getQuery()
            ->getResult();
    }

    public function removeSynergieFromUser(Synergy $synergy, User $user): void
    {
        $synergy->removeUtilisateur($user);

        $this->getEntityManager()->persist($synergy);

        $this->getEntityManager()->flush();
    }
}
