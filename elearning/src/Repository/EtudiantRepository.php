<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function getEtudiant($idu)
    {
        $query = $this->createQueryBuilder('e')
            // Filter by some parameter if you want
            ->select('e.id')
            ->where('e.User = :IDE')
            ->setParameter('IDE',$idu)
            ->getQuery();
        return $query->getSingleScalarResult();
    }
    public function getcountetudiantniveau(int $refe)
    {
        $query = $this->createQueryBuilder('e')
            // Filter by some parameter if you want
            ->select('count(e.id)')
            ->where('e.Niveau = :IDE')
            ->setParameter('IDE',$refe)
            ->getQuery();
        return $query->getSingleScalarResult();
    }


    public function findBytuteurmatiere($value)
    {
        $query = $this->createQueryBuilder('t')

            ->andWhere('t.Matieres = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
            return $query->getSResult();

    }


    /*
    public function findOneBySomeField($value): ?Etudiant
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
