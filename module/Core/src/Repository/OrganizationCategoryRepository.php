<?php declare(strict_types=1);


namespace Core\Repository;

use Core\Entity\OrganizationCategory;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class OrganizationCategoryRepository
 *
 * @method OrganizationCategory find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationCategory findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationCategory[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method OrganizationCategory[] findAll()
 */
class OrganizationCategoryRepository extends AbstractAdminautRepository
{
    public function findOneByQueryString(string $queryString): ?OrganizationCategory
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->andWhere('INSTR(:queryString, c.title) > 0')
            ->setParameter('queryString', $queryString)
        ;

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return $qb->getQuery()->getResult()[0];
        }
    }
}