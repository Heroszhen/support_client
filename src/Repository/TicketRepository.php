<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Service;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }


    public function findByService(Service $service){
        $qb = $this->createQueryBuilder('t');
        $qb
            ->innerJoin('t.services', 's')
            ->where('s.id=:serviceid')
            ->setParameter('serviceid', $service->getId())
        ;
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findByNotService(Service $service){
        $tab = $this->findByService($service);
        $tab2 = [];
        foreach($tab as $one){
            array_push($tab2,$one->getId());
        }

        $qb = $this->createQueryBuilder('t');
        $qb
            ->innerJoin('t.services', 's')
            ->where($qb->expr()->notIn('t.id', $tab2))
        ;
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function moteur($keyword){
        $qb = $this->createQueryBuilder('t');
        $qb
            ->innerJoin('t.services', 's')
            ->where('t.title LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orWhere('t.description LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orWhere('s.name LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->leftJoin('t.messages', 'm')
            ->orWhere('m.content LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
        ;
        $query = $qb->getQuery();
        return $query->getResult();
    }


    // /**
    //  * @return Ticket[] Returns an array of Ticket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ticket
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
