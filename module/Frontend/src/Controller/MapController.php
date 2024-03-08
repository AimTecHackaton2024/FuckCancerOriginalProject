<?php declare(strict_types=1);


namespace Frontend\Controller;

use Core\Repository\OrganizationCategoryRepository;
use Core\Repository\OrganizationRepository;
use Core\Service\QueryParser;
use Doctrine\ORM\EntityRepository;
use Frontend\Serializer\OrganizationSerializer;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class MapController extends AbstractActionController
{
    /**
     * @var OrganizationRepository
     */
    private $organizationRepository;

    /**
     * @var OrganizationCategoryRepository
     */
    private $categoryRepository;

    /**
     * @var OrganizationSerializer
     */
    private $organizationSerializer;

    /**
     * @var QueryParser
     */
    private $queryParser;

    public function __construct(EntityRepository $organizationRepository, EntityRepository $categoryRepository, OrganizationSerializer $organizationSerializer, QueryParser $queryParser)
    {
        $this->organizationRepository = $organizationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->organizationSerializer = $organizationSerializer;
        $this->queryParser = $queryParser;
    }

    public function indexAction(): ViewModel
    {
        return new ViewModel([
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function loadAction(): JsonModel
    {
        return new JsonModel($this->organizationSerializer->serializeAll($this->organizationRepository->findAll()));
    }

    public function searchAction(): JsonModel
    {
        $location = [
            'from' => [
                $this->params()->fromQuery('from_lat', null),
                $this->params()->fromQuery('from_lng', null),
            ],
            'to' => [
                $this->params()->fromQuery('to_lat', null),
                $this->params()->fromQuery('to_lng', null),
            ],
        ];

        return new JsonModel(
            $this->organizationRepository->search(
                $location,
                $this->queryParser->parse($this->params()->fromQuery('q', null))
            )
        );
    }
}