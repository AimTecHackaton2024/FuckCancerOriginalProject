<?php declare(strict_types=1);


namespace Frontend\Manager;

use Adminaut\Manager\FileManager;
use Adminaut\Manager\ModuleManager;
use Core\Entity\Organization;
use Core\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Frontend\Form\OrganizationAddForm;

class OrganizationManager extends AbstractManager
{

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(FileManager $fileManager, EntityManager $entityManager)
    {
        $this->fileManager = $fileManager;
        $this->entityManager = $entityManager;
    }

    public function create(OrganizationAddForm $form)
    {
        $data = $form->getData();

        foreach (['photoMain', 'photo1', 'photo2', 'photo3', 'photo4'] as $fileElement) {
            $file = $data[$fileElement];

            if ($file['error'] !== 0) {
                continue;
            }

            $this->fileManager->upload($form->getElements()[$fileElement]);
        }

        $organization = new Organization();
        $this->bind($organization, $form);

        if (!empty($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                if (is_numeric($tag)) {
                    continue;
                }



                $organization->addTags([ new Tag($tag) ]);
            }
        }

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization;
    }
}