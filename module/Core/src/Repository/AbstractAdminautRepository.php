<?php declare(strict_types=1);


namespace Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractAdminautRepository extends EntityRepository
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $this->applyAdminautProps($criteria);

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        $this->applyAdminautProps($criteria);

        return parent::findOneBy($criteria, $orderBy);
    }

    protected function applyAdminautProps(array &$criteria)
    {
        if (!array_key_exists('active', $criteria)) {
            $criteria['active'] = true;
        }

        if (!array_key_exists('deleted', $criteria)) {
            $criteria['deleted'] = false;
        }
    }

    public function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        $qb = parent::createQueryBuilder($alias, $indexBy);
        $qb
            ->andWhere($alias . '.active = 1')
            ->andWhere($alias . '.deleted = 0')
        ;

        return $qb;
    }
}