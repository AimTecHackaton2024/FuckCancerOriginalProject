<?php declare(strict_types=1);


namespace Core\Service;

use Core\Repository\OrganizationCategoryRepository;
use Doctrine\ORM\EntityRepository;

class QueryParser
{
    /**
     * @var OrganizationCategoryRepository
     */
    private $categoryRepository;

    public function __construct(EntityRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function parse(string $queryString): array
    {
        if ($category = $this->categoryRepository->findOneByQueryString($queryString)) {
            $queryString = str_replace($category->getTitle(), '', $queryString);
        }

        return [
            'query_string' => trim($queryString),
            'category' => $category,
        ];
    }
}