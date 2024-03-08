<?php declare(strict_types=1);


namespace Core\Repository;

use Core\Entity\BlogPost;

/**
 * Class BlogPostRepository
 *
 * @method BlogPost find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method BlogPost[] findAll()
 */
class BlogPostRepository extends AbstractAdminautRepository
{
}