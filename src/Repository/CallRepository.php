<?php

namespace App\Repository;

use App\Entity\Call;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Call|null find($id, $lockMode = null, $lockVersion = null)
 * @method Call|null findOneBy(array $criteria, array $orderBy = null)
 * @method Call[]    findAll()
 * @method Call[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CallRepository extends ServiceEntityRepository {
    
    private $doctrine;
    
    private $index = [
        'custumer_id' => 0,
        'timestamp' => 1,
        'duration' => 2,
        'phone' => 3,
        'ip' => 4,
    ];

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Call::class);
        $this->doctrine = $registry;
    }

    public function store(array $records): int {
        
        $entityManager = $this->doctrine->getManager();
        $total = [];

        foreach ($records as $record) {
            $call = new Call();
            $call->setCustumerId($record[$this->index['custumer_id']]);
            $call->setDuration($record[$this->index['duration']]);
            $call->setPhone($record[$this->index['phone']]);
            $call->setIp($record[$this->index['ip']]);
            $call->setTimestamp(new \DateTime($record[$this->index['timestamp']]));

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($call);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            
            $total[] = $call->getId();
        }
        return count($total);
    }
}
