<?php declare(strict_types=1);


namespace Core\Repository;

use Core\Entity\Organization;
use Core\Enum\OrganizationStatus;

/**
 * Class OrganizationRepository
 *
 * @method Organization find($id, $lockMode = null, $lockVersion = null)
 * @method Organization findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends AbstractAdminautRepository
{
    public function findAll(): array
    {
        return $this->findBy([
            'status' => OrganizationStatus::ACTIVE
        ]);
    }

    public function search(array $location, array $query = [])
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o.id')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->gte('o.locationLat', ':latMin'),
                $qb->expr()->lte('o.locationLat', ':latMax'),
                $qb->expr()->gte('o.locationLng', ':lngMin'),
                $qb->expr()->lte('o.locationLng', ':lngMax')
            ))
            ->andWhere('o.status = :status')
            ->setParameters([
                'latMin' => $location['from'][0],
                'lngMin' => $location['from'][1],
                'latMax' => $location['to'][0],
                'lngMax' => $location['to'][1],
                'status' => OrganizationStatus::ACTIVE,
            ])
        ;

        if (null !== $query['category']) {
            $qb
                ->andWhere('o.category = :category')
                ->setParameter('category', $query['category'])
            ;
        }

        if (!empty($query['query_string'])) {
            $qb
                ->andWhere($qb->expr()->orX(
                    'LOWER(o.title) LIKE :queryStringLike',
                    'LOWER(o.officialName) LIKE :queryStringLike',
                    'LOWER(o.street) LIKE :queryStringLike',
                    'LOWER(o.city) LIKE :queryStringLike',
                    'LOWER(o.zip) LIKE :queryStringLike',
                    'INSTR(:queryString, LOWER(o.street)) > 0',
                    'INSTR(:queryString, LOWER(o.city)) > 0',
                    'INSTR(:queryString, LOWER(o.zip)) > 0'
                ))
                ->setParameter('queryStringLike', sprintf('%%%s%%', strtolower($query['query_string'])))
                ->setParameter('queryString', strtolower($query['query_string']))
            ;
        }

        return array_column($qb->getQuery()->getResult(), 'id');
    }
}