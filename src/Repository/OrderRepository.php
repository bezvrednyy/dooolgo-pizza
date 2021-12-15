<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function nextId(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function maxNumber(): int
    {
        $query = $this->_em->createQuery(
            'SELECT MAX(o.number) as number
            FROM App\Entity\Order o'
        );
        $answer = $query->getOneOrNullResult();
        if ($answer['number'])
        {
            return (int)$answer['number'];
        }
        return 1000;
    }

    public function findByMultipleId(array $idList): array
    {
        $idListForSql = $this->toStringWithQuote($idList);
        $sql = "SELECT o
            FROM App\Entity\Order o
            WHERE o.id IN (${idListForSql})";
        $query = $this->_em->createQuery($sql);
        return $query->getResult();
    }

    public function add(Order $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    public function del(Order $order): void
    {
        $this->_em->remove($order);
        $this->_em->flush();
    }

    private function toStringWithQuote(array $array): ?string
    {
        if ($array)
        {
            return '\'' . implode('\', \'', $array) . '\'';
        }
        return null;
    }
}
